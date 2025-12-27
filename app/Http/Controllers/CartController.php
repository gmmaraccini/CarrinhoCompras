<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
// Imports do Stripe
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CartController extends Controller
{
    public function shop()
    {
        $products = Product::all();
        return view('shop', compact('products'));
    }

    public function cart()
    {
        return view('cart');
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produto removido!');
    }

    // --- NOVA LÓGICA DE CHECKOUT COM STRIPE ---
    public function checkout()
    {
        // 1. Segurança: Só logado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faça login para finalizar a compra.');
        }

        $cart = session()->get('cart');
        if(!$cart) {
            return redirect()->back()->with('error', 'Seu carrinho está vazio!');
        }

        // 2. Cria o Pedido como "Pendente" no Banco
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pendente' // Começa pendente até o Stripe confirmar
        ]);

        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // 3. Configura o Stripe
        // Atenção: Usando o nome que você colocou no .env
        Stripe::setApiKey(env('STRIPE_SK_KEY'));

        // Prepara os itens para o formato do Stripe
        $lineItems = [];
        foreach($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    // Stripe usa centavos (ex: R$ 10,00 vira 1000)
                    'unit_amount' => intval($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // 4. Cria a Sessão de Pagamento no Stripe
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'order_id' => $order->id // Guardamos o ID do pedido para usar na volta
            ],
        ]);

        // Redireciona o usuário para a página segura do Stripe
        return redirect($checkoutSession->url);
    }

    // --- MÉTODOS DE RETORNO ---

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');

        // Se não tiver ID de sessão, expulsa
        if (!$sessionId) {
            return redirect()->route('cart')->with('error', 'Sessão de pagamento inválida.');
        }

        try {
            // 1. Configura a chave de novo
            Stripe::setApiKey(env('STRIPE_SK_KEY'));

            // 2. Busca os detalhes da sessão no Stripe para ter certeza que foi pago
            $session = Session::retrieve($sessionId);

            // Verificamos se o pagamento foi realmente efetuado no Stripe
            if ($session->payment_status !== 'paid') {
                return redirect()->route('cart')->with('error', 'O pagamento não foi confirmado.');
            }

            // 3. Recupera o ID do pedido que enviamos no metadata
            $orderId = $session->metadata->order_id;

            // 4. Busca o pedido no banco
            $order = Order::find($orderId);

            // 5. Atualiza o pedido e limpa o carrinho
            if ($order && $order->status === 'pendente') {
                $order->update(['status' => 'pago']);

                session()->forget('cart'); // Esvazia o carrinho

                return redirect()->route('my_orders')->with('success', 'Sucesso! Seu pagamento foi confirmado.');
            } else {
                // Caso o pedido já esteja pago ou não exista
                return redirect()->route('my_orders');
            }

        } catch (\Exception $e) {
            // Se der erro de conexão com o Stripe ou algo assim
            return redirect()->route('shop')->with('error', 'Erro ao processar confirmação: ' . $e->getMessage());
        }
    }

    public function paymentCancel()
    {
        return redirect()->route('cart')->with('error', 'O pagamento foi cancelado. O pedido não foi concluído.');
    }
}
