<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop') }}">Loja Tech</a>
        <a href="{{ route('shop') }}" class="btn btn-secondary">Continuar Comprando</a>
    </div>
</nav>

<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="mb-4">Seu Carrinho</h2>

    @if(session('cart'))
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">Produto</th>
                        <th>Preço Unit.</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $total = 0; @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr>
                            <td class="ps-4 align-middle">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $details['image'] }}" width="50" class="rounded me-3">
                                    <strong>{{ $details['name'] }}</strong>
                                </div>
                            </td>
                            <td class="align-middle">R$ {{ number_format($details['price'], 2, ',', '.') }}</td>
                            <td class="align-middle">{{ $details['quantity'] }}</td>
                            <td class="align-middle fw-bold">R$ {{ number_format($details['price'] * $details['quantity'], 2, ',', '.') }}</td>
                            <td class="align-middle">
                                <a href="{{ route('remove_from_cart', $id) }}" class="btn btn-sm btn-outline-danger">Remover</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center p-4">
                <h3 class="mb-0">Total: R$ {{ number_format($total, 2, ',', '.') }}</h3>

                @auth
                    <a href="{{ route('checkout') }}" class="btn btn-success btn-lg px-5">Finalizar Compra</a>
                @else
                    <div class="text-end">
                        <p class="mb-1 text-muted">Faça login para finalizar</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
                    </div>
                @endauth
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h4 class="text-muted">Seu carrinho está vazio.</h4>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Ir para a Loja</a>
        </div>
    @endif
</div>

</body>
</html>
