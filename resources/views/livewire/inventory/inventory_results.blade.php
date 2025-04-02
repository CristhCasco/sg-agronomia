@extends('layouts.theme.app')

@section('title', 'Resultados del Conteo de Inventarios')

@section('content')
    <div class="container mt-5">
        <h1>Resultados del Conteo de Inventarios</h1>
        @if(session('inventory_results'))
            <form action="{{ route('inventory.download') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Descargar Inventario Contado</button>
            </form>
        @endif
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad en Excel</th>
                    <th>Cantidad Contada</th>
                    <th>Diferencia</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{ $result['barcode'] }}</td>
                        <td>{{ $result['description'] }}</td>
                        <td>{{ $result['quantity_excel'] }}</td>
                        <td>{{ $result['quantity_counted'] }}</td>
                        <td>{{ $result['difference'] }}</td>
                        <td>{{ $result['user'] }}</td>
                        <td>{{ $result['date'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection