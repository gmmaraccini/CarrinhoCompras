<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Importante: Para pegar o usuário logado
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

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

    public function checkout()
    {
        $cart = session()->get('cart');

        if(!$cart) {
            return redirect()->back()->with('error', 'Seu carrinho está vazio!');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Cria o Pedido (Order)
        $order = Order::create([
            'user_id' => Auth::id(), // <--- AQUI: Salva o ID do usuário (ou null se não logado)
            'total_price' => $total,
            'status' => 'aprovado'
        ]);

        // Salva os Itens
        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');

        return redirect()->route('shop')->with('success', 'Pedido #' . $order->id . ' realizado com sucesso!');
    }
}
