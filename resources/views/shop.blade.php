<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Loja Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 200px; object-fit: contain; padding: 10px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop') }}">Loja Tech</a>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cart') }}" class="btn btn-primary position-relative">
                Carrinho
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count((array) session('cart')) }}
                    </span>
            </a>

            <div class="vr text-white mx-2"></div>

            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Olá, {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('my_orders') }}">Meus Pedidos</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-light">Cadastrar</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text fs-5 text-primary fw-bold">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                        <a href="{{ route('add_to_cart', $product->id) }}" class="btn btn-outline-primary mt-auto w-100">Adicionar ao Carrinho</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
