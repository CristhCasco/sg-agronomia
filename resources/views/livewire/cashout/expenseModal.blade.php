<div wire:ignore.self id="expense-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Agregar Gasto Extra</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" wire:model="expenseDescription" class="form-control" placeholder="Descripción">
                <input type="number" wire:model="expenseAmount" class="form-control mt-2" placeholder="Monto">
            </div>
            <div class="modal-footer">
                <button wire:click="AddExpense" class="btn btn-dark">Guardar</button>
            </div>
        </div>
    </div>
</div>
