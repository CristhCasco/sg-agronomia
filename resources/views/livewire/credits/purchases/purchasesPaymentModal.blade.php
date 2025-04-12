{{-- MODAL DE ABONO --}}
@if($selectedPurchaseId && $selectedPurchase)
<div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Abonar Compra #{{ $selectedPurchase->id }}</h5>
                <button type="button" class="close" wire:click="$set('selectedPurchaseId', null)">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Saldo Pendiente:
                    <b>{{ number_format($selectedPurchase->saldo_pendiente, 0, ',', '.') }} Gs.</b>
                </p>

                <div class="form-group">
                    <label>Monto a abonar</label>
                    <input type="number" class="form-control" wire:model="monto">
                </div>

                <div class="form-group">
                    <label>Método de pago</label>
                    <select wire:model="metodo" class="form-control">
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                        <option value="CHEQUE">Cheque</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" wire:click="$set('selectedPurchaseId', null)">Cancelar</button>
                <button class="btn btn-success" wire:click="guardarPago">Confirmar Pago</button>
            </div>
        </div>
    </div>
</div>
@endif