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

                    <div class="col-sm-12 mt-2">
                        <button wire:click.prevent="savePurchase" class="btn btn-primary btn-block" {{ $totalCarrito==0
                            ? 'disabled' : '' }}>
                            Save Purchase F3
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>