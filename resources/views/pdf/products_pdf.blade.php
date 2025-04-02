<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Inventario de Productos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h4>Reporte de Inventario de Productos</h4>
    <p>Fecha de Exportación: {{ $date }}</p>
    <table>
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
</body>
</html>