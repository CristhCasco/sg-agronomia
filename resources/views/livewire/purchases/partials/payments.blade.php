<div class="row mt-3">
    <div class="col-sm-12">
        <div class="connect-sorting">
            <h5 class="text-center mb-2">METODOS DE PAGOS</h5>
            <div class="container">
                <div class="row">

                    <div class="col-sm-6 form-group text-center">
                        <label>TIPO</label>
                        <select wire:model='payment_type' class="form-control">
                            <option value="CONTADO" selected>CONTADO</option>
                            <option value="CREDITO" selected>CREDITO</option>
                        </select>
                        @error('payment_type') <span class="text-danger er">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-sm-6 form-group text-center">
                        <label>METODO</label>
                        <select wire:model='payment_method' class="form-control">
                            <option value="EFECTIVO" selected>EFECTIVO</option>
                            <option value="TARJETA_CREDITO" selected>TDD</option>
                            <option value="TARJETA_DEBITO" selected>TDC</option>
                            <option value="TRANSFERENCIA" selected>TRANS</option>
                            <option value="TIGO_MONEY" selected>TIGO</option>
                            <option value="CHEQUE" selected>CHEQUE</option>
                            <option value="OTRO" selected>OTROS</option>
                        </select>
                        @error('payment_method') <span class="text-danger er">{{ $message }}</span> @enderror
                    </div>

                    @if($payment_type == 'CREDITO')
                        <div class="col-sm-6 form-group text-center">
                            <label>Fecha de Vencimiento</label>
                            <input type="date" wire:model="due_date" class="form-control">
                            @error('due_date') <span class="text-danger er">{{ $message }}</span> @enderror
                        </div>
                    @endif


                    <div class="col-sm-12 mt-2">
                    <button wire:click.prevent="confirmarResumen" class="btn btn-secondary"
                     {{ $totalCarrito == 0 ? 'disabled' : '' }}>Guardar</button>

                    </div>
                </div>
                @if($mostrarResumen)
                    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5)">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title">Resumen de Compra</h5>
                                    <button type="button" class="close" wire:click="$set('mostrarResumen', false)">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="background-color: #620408;">
                                                <th style="color: white; font-weight: bold;">Producto</th>
                                                <th style="color: white; font-weight: bold;">Cantidad</th>
                                                <th style="color: white; font-weight: bold;">Costo</th>
                                                <th style="color: white; font-weight: bold;">Margen %</th>
                                                <th style="color: white; font-weight: bold;">Nuevo Precio Venta</th>
                                                <th style="color: white; font-weight: bold;">Precio actual</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($carrito as $item)
                                                <tr>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td>{{ $item['qty'] }}</td>
                                                    <td>{{ number_format($item['cost'], 0, ',', '.') }} Gs.</td>
                                                    <td>{{ $item['margen'] ?? 0 }}%</td>
                                                    <td>{{ number_format($item['cost'] * (1 + ($item['margen'] ?? 0) / 100), 0, ',', '.') }} Gs.</td>
                                                    <td>
                                                        {{ number_format(\App\Models\Product::find($item['id'])->price ?? 0, 0, ',', '.') }} Gs.
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button wire:click.prevent="savePurchase" class="btn btn-success">Confirmar Compra</button>
                                    <button wire:click="$set('mostrarResumen', false)" class="btn btn-secondary">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>