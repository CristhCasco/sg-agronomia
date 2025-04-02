<div class="row">
    <div class="col-sm-12">
        <div>
            <div class="connect-sorting">
                <!-- Ventas Recientes -->
                <div class="card mt-3">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-white text-center w-100  " >Ventas Recientes</h6>
                        <button class="btn btn-light btn-sm" onclick="toggleSales()">Mostrar/Ocultar</button>
                    </div>

                    <!-- Lista de Ventas -->
                    <div class="card-body" id="sales-list" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>ID</th>
                                        <th>Total</th>
                                        <th>Cliente</th>
                                        <th>Tipo Pago</th>
                                        <th>Método Pago</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ number_format($sale->total, 0) }} Gs.</td>
                                        <td>{{ $sale->customer->name ?? 'Cliente Ocasional' }}</td>
                                        <td >{{ $sale->payment_type }}</td>
                                        <td >{{ $sale->payment_method }}</td>
                                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button wire:click="printSale({{ $sale->id }})" class="btn btn-sm btn-dark">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Fin de Lista de Ventas -->
                </div>
                <!-- Fin de Ventas Recientes -->
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSales() {
        var salesList = document.getElementById('sales-list');
        salesList.style.display = salesList.style.display === 'none' ? 'block' : 'none';
    }
</script>