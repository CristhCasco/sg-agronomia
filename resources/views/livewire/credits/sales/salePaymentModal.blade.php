<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="paymentModalLabel">Registrar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Agregamos un id al input y lo enlazamos correctamente con el label -->
                <label for="amountInput">Monto a Pagar (Gs.)</label>
                <!-- <input type="number" wire:model="amount" id="amountInput" class="form-control" min="1"> -->
                <input type="number" wire:model.defer="amount" id="amountInput" class="form-control" min="1">
                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" wire:click="payCredit" class="btn btn-primary">Registrar Pago</button> -->
                <button type="button" wire:click.prevent="payCredit" class="btn btn-primary">
                    Registrar Pago
                </button>

            </div>
        </div>
    </div>
</div>




