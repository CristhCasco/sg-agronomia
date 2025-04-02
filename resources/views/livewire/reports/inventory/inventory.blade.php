
@extends('layouts.theme.app')

@section('content')
<div class="container">
    <h4 class="text-center"><b>Reporte de Inventario de Productos</b></h4>
    <a href="{{ route('export.products') }}" class="btn btn-success mb-3">Exportar a Excel</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio de Compra</th>
                <th>Precio de Venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->barcode }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->cost }}</td>
                <td>{{ $product->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection