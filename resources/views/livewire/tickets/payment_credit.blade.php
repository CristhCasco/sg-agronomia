<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: monospace;
            font-size: 11px;
            width: 226.77px; /* 80mm en puntos */
            padding: 0;
            margin: 0;
        }
        .center {
            text-align: center;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <div class="center">
        <strong>VERDE FERTIL</strong><br>
        <strong>COMPROBANTE DE PAGO</strong><br>
        {{ $fecha }}
    </div>

    <div class="separator"></div>
    
    Cliente: {{ $cliente }}<br>
    Estado: {{ strtoupper($estado_credito) }}<br>
    Venta: #{{ $venta_id }}<br>
    --------------------------------<br>
    Total crédito: Gs. {{ number_format($total_original, 0, ',', '.') }}<br>
    Pagado:        Gs. {{ number_format($total_pagado, 0, ',', '.') }}<br>
    Pendiente:     Gs. {{ number_format($saldo, 0, ',', '.') }}<br>

    <div class="separator"></div>

    Pagos realizados:<br>
    @foreach($pagos as $p)
        - {{ $p['fecha'] }} - Gs. {{ number_format($p['monto'], 0, ',', '.') }}<br>
    @endforeach

    <div class="separator"></div>

    Atendido por: {{ $usuario }}<br>

    <div class="separator"></div>
    <br><br>
    Firma del cliente: ___________________________<br><br>

    <div class="separator"></div>
    <div class="center">Gracias por su pago</div>

</body>
</html>
