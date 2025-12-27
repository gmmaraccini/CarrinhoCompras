<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController; // Importamos o controlador da Loja
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;     // Necessário para pegar o ID do usuário
use App\Models\Order;                    // Necessário para buscar os pedidos

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Vitrine e Carrinho)
|--------------------------------------------------------------------------
| Aqui ficam as páginas que qualquer pessoa pode acessar, mesmo sem logar.
*/

// Mudamos a página inicial '/' para ser a sua Loja, em vez da tela de boas-vindas do Laravel
Route::get('/', [CartController::class, 'shop'])->name('shop');

// Rotas de manipulação do carrinho
Route::get('/carrinho', [CartController::class, 'cart'])->name('cart');
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add_to_cart');
Route::get('/remove-from-cart/{id}', [CartController::class, 'remove'])->name('remove_from_cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Área do Cliente)
|--------------------------------------------------------------------------
| Tudo que estiver dentro do 'middleware auth' exige login.
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de Perfil (Criadas pelo Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota "Meus Pedidos" (Adicionada por nós)
    Route::get('/meus-pedidos', function () {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product') // Já traz os itens e produtos juntos (performance)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('my_orders', compact('orders'));
    })->name('my_orders');

    // Rotas de Retorno do Pagamento
    Route::get('/pagamento/sucesso', [CartController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/pagamento/cancel', [CartController::class, 'paymentCancel'])->name('payment.cancel');


});

require __DIR__.'/auth.php';
