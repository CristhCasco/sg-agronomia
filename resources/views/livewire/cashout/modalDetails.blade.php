<div wire:ignore.self id="modal-details" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Detalle de Operación</b>
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Pestañas para Ventas y Compras -->
                <ul class="nav nav-tabs" id="tab-details">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#salesDetails">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#purchasesDetails">Compras</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- 🔹 TABLA DE VENTAS -->
                    <div id="salesDetails" class="tab-pane fade show active">
                        <h5 class="text-center text-dark">Detalle de Venta</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #620408">
                                    <tr>
                                        <th class="table-th text-white text-center">CLIENTE</th>
                                        <th class="table-th text-white text-center">PRODUCTO</th>
                                        <th class="table-th text-white text-center">CANTIDAD</th>
                                        <th class="table-th text-white text-center">PRECIO</th>
                                        <th class="table-th text-white text-center">IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(empty($detailsSales) || count($detailsSales) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center"><h6>No hay registros</h6></td>
                                        </tr>
                                    @endif

                                    @foreach($detailsSales as $detail)
                                        <tr>
                                            <td class="text-center">{{ $detail->customer }}</td>
                                            <td class="text-center">{{ $detail->product }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-center">{{ number_format($detail->price, 0) }} Gs.</td>
                                            <td class="text-center">{{ number_format($detail->quantity * $detail->price, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td class="text-right"><h5 class="text-info">TOTALES:</h5></td>
                                    <td class="text-center">
                                        <h5 class="text-info">{{ $detailsSales ? $detailsSales->sum('quantity') : 0 }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="text-info">
                                            {{ number_format($detailsSales ? $detailsSales->sum(fn($d) => $d->quantity * $d->price) : 0, 0) }} Gs.
                                        </h5>
                                    </td>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- 🔹 TABLA DE COMPRAS -->
                    <div id="purchasesDetails" class="tab-pane fade">
                        <h5 class="text-center text-dark">Detalle de Compra</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #023b58">
                                    <tr>
                                        <th class="table-th text-white text-center">PROVEEDOR</th>
                                        <th class="table-th text-white text-center">PRODUCTO</th>
                                        <th class="table-th text-white text-center">CANTIDAD</th>
                                        <th class="table-th text-white text-center">PRECIO</th>
                                        <th class="table-th text-white text-center">IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(empty($detailsPurchases) || count($detailsPurchases) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center"><h6>No hay registros</h6></td>
                                        </tr>
                                    @endif

                                    @foreach($detailsPurchases as $detail)
                                        <tr>
                                            <td class="text-center">{{ $detail->supplier }}</td>
                                            <td class="text-center">{{ $detail->product }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-center">{{ number_format($detail->price, 0) }} Gs.</td>
                                            <td class="text-center">{{ number_format($detail->quantity * $detail->price, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td class="text-right"><h5 class="text-info">TOTALES:</h5></td>
                                    <td class="text-center">
                                        <h5 class="text-info">{{ $detailsPurchases ? $detailsPurchases->sum('quantity') : 0 }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="text-info">
                                            {{ number_format($detailsPurchases ? $detailsPurchases->sum(fn($d) => $d->quantity * $d->price) : 0, 0) }} Gs.
                                        </h5>
                                    </td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">        
                    <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('show-modal', msg => {
            $('#modal-details').modal('show');
        });
    });
</script>
