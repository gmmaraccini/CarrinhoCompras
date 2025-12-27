<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop') }}">Loja Tech</a>
        <div class="d-flex gap-2">
            <a href="{{ route('shop') }}" class="btn btn-outline-light">Voltar à Loja</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Sair</button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <h2 class="mb-4 border-bottom pb-2">Histórico de Pedidos</h2>

    @if($orders->isNotEmpty())
        @foreach($orders as $order)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold fs-5">Pedido #{{ $order->id }}</span>
                        <span class="text-muted ms-2">em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</span>
                    </div>
                    <span class="badge bg-success rounded-pill px-3 py-2">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">Item</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-end pe-3">Preço</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="ps-3">{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end pe-3">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold pt-3">TOTAL:</td>
                            <td class="text-end fw-bold fs-5 text-success pe-3 pt-3">
                                R$ {{ number_format($order->total_price, 2, ',', '.') }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <div>
                Você ainda não realizou nenhuma compra.
                <a href="{{ route('shop') }}" class="alert-link">Começar agora</a>.
            </div>
        </div>
    @endif
</div>

</body>
</html>
