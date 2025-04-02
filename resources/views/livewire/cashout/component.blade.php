<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="cart-title text-center">
                    <b>Corte de Caja</b>
                </h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Usuario</label>
                            <select wire:model="userId" class="form-control">
                                <option value="0">Elegir</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('userId') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" wire:model.lazy="fromDate" class="form-control">
                            @error('fromDate') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha Final</label>
                            <input type="date" wire:model.lazy="toDate" class="form-control">
                            @error('toDate') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-3 align-self-center d-flex justify-content-around">
                        @if($userId > 0 && $fromDate !=null && $toDate != null)
                            <button wire:click.prevent="Consult" type="button" class="btn btn-dark">
                                Consultar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <!-- Resumen de Ventas -->
                <div class="col-sm-12 col-md-4">
                    <div class="connect-sorting bg-dark p-3">
                        <h5 class="text-white text-center">Ventas Totales</h5>
                        <h6 class="text-white">Total: {{ number_format($totalSales, 0) }} Gs.</h6>
                        <h6 class="text-white">Artículos Vendidos: {{ $itemsSales }}</h6>
                    </div>
                </div>

                <!-- Resumen de Compras -->
                <div class="col-sm-12 col-md-4">
                    <div class="connect-sorting " style="background: #023b58" >
                        <h5 class="text-white text-center">Compras Totales</h5>
                        <h6 class="text-white">Total: {{ number_format($totalPurchases, 0) }} Gs.</h6>
                        <h6 class="text-white">Artículos Comprados: {{ $itemsPurchases }}</h6>
                    </div>
                </div>

                <!-- Resumen de Gastos Extras -->
                <div class="col-sm-12 col-md-4">
                    <div class="connect-sorting bg-warning p-3">
                        <h5 class="text-white text-center">Gastos Extras</h5>
                        <h6 class="text-white">Total: {{ number_format($totalExtraExpenses, 0) }} Gs.</h6>
                    </div>
                </div>

                <!-- Diferencia Ajustada -->
                <div class="col-sm-12 col-md-12 mt-3">
                    <div class="connect-sorting p-3 text-center"
                        style="background: 
                            @if($totalSales - $totalPurchases - $totalExtraExpenses > 0) #28a745; /* Verde */
                            @elseif($totalSales - $totalPurchases - $totalExtraExpenses < 0) #dc3545; /* Rojo */
                            @else #6c757d; /* Gris */
                            @endif">
                        <h5 class="text-white">Diferencia Ajustada</h5>
                        <h6 class="text-white">
                            @if($totalSales - $totalPurchases - $totalExtraExpenses > 0)
                                🟢 Ganancia: {{ number_format($totalSales - $totalPurchases - $totalExtraExpenses, 0) }} Gs.
                            @elseif($totalSales - $totalPurchases - $totalExtraExpenses < 0)
                                🔴 Pérdida: {{ number_format($totalSales - $totalPurchases - $totalExtraExpenses, 0) }} Gs.
                            @else
                                ⚖️ Equilibrio: No hay diferencia.
                            @endif
                        </h6>
                    </div>
                </div>
            </div>

            <!-- Botón para agregar gastos -->
            <button wire:click="$emit('show-expense-modal')" class="btn btn-warning mt-3">
                Agregar Gasto Extra
            </button> 

            <div class="row mt-4">
                <!-- Tabla de Ventas -->
                <div class="col-sm-12 col-md-4">
                    <h5 class="text-center text-dark">Historial de Ventas</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #620408; color: white;">
                                <tr>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">ITEMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td class="text-left">{{ $sale->customer->name }}</td>
                                        <td class="text-center">{{ number_format($sale->total, 0) }} Gs.</td>
                                        <td class="text-center">{{ $sale->items }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabla de Compras -->
                <div class="col-sm-12 col-md-4">
                    <h5 class="text-center text-dark">Historial de Compras</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #023b58">
                                <tr>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">ITEMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td class="text-left">{{ $purchase->supplier->name }}</td>
                                        <td class="text-center">{{ number_format($purchase->total, 0) }} Gs.</td>
                                        <td class="text-center">{{ $purchase->items }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabla de Gastos Extras -->
                <div class="col-sm-12 col-md-4">
                    <h5 class="text-center text-dark">Historial de Gastos Extras</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #856404">
                                <tr>
                                    <th class="text-center">FECHA</th>
                                    <th class="text-center">DESCRIPCIÓN</th>
                                    <th class="text-center">MONTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($extraExpenses as $expense)
                                    <tr>
                                        <td class="text-center">{{ $expense->date }}</td>
                                        <td class="text-center">{{ $expense->description }}</td>
                                        <td class="text-center">{{ number_format($expense->amount, 0) }} Gs.</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('livewire.cashout.modalDetails')
    @include('livewire.cashout.expenseModal')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('show-modal', msg => {
            $('#modal-details').modal('show');
        });
    });

    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('show-expense-modal', () => {
            $('#expense-modal').modal('show');
        });

        window.livewire.on('close-modal', () => {
            $('#expense-modal').modal('hide');
        });
    });
</script>

