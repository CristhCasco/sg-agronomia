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
                            <option value="TDD" selected>TDD</option>
                            <option value="TDC" selected>TDC</option>
                            <option value="TRANS" selected>TRANS</option>
                            <option value="TIGO" selected>TIGO</option>
                            <option value="CHEQUE" selected>CHEQUE</option>
                            <option value="OTROS" selected>OTROS</option> 
                        </select>
                        @error('payment_method') <span class="text-danger er">{{ $message }}</span> @enderror
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
