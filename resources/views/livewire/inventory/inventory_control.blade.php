@extends('layouts.theme.app')

@section('content')
    <div class="container mt-5">
        <h1>Control de Inventarios</h1>
        <form action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Importar Productos desde Excel</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>

        <div class="mt-5">
            <h2>Escanear Código de Barras</h2>
            <div id="interactive" class="viewport"></div>
            <form action="{{ route('inventory.saveScanned') }}" method="POST">
                @csrf
                <input type="text" name="barcode" id="barcode" class="form-control mt-3" placeholder="Código de Barras Escaneado o Manual" autofocus>
                <input type="number" name="quantity_counted" class="form-control mt-3" placeholder="Cantidad Contada" required>
                <button type="submit" class="btn btn-success mt-3">Guardar Código y Cantidad</button>
            </form>
            <form action="{{ route('inventory.generateCount') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Generar Conteo</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#interactive')    // Or '#yourElement' (optional)
                },
                decoder: {
                    readers: ["code_128_reader"] // List of active readers
                }
            }, function (err) {
                if (err) {
                    console.log(err);
                    return
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });

            Quagga.onDetected(function (data) {
                document.getElementById('barcode').value = data.codeResult.code;
            });
        });
    </script>
@endsection