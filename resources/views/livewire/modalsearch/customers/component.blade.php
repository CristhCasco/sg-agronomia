<div>
    <div wire:ignore.self class="modal fade modal-fullscreen" id="modalSearchCustomer" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <div class="input-group">
                        <input type="text" wire:model="search" id="modal-search-input"
                            placeholder="Escribe el nombre, apellido, ci, empresa o ruc..." class="form-control">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-gp">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="row p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #620408">
                                    <tr>
                                        <!--<th width="4%"></th>-->
                                        <th width="5%" class="table-th text-left text-white">CODIGO</th>
                                        <th class="table-th text-center text-white">NOMBRE</th>
                                        <th width="13%" class="table-th text-center text-white">APELLIDO</th>
                                        <th class="table-th text-center text-white">CEDULA</th>
                                        <th class="table-th text-center text-white">EMPRESA</th>
                                        <th class="table-th text-center text-white">RUC</th>
                                        <th class="table-th text-center text-white">DIRECCION</th>
                                        <th class="table-th text-center text-white">CELULAR</th>
                                        <th class="table-th text-center text-white">
                                            <button wire:click.prevent="addAll" class="btn btn-info"
                                                {{count($customers)> 0 ? '' : 'disabled' }}>
                                                <i class="fas fa-check"></i>
                                                TODOS
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                    <tr>
                                        <!--
                                        <td>
                                            <span>
                                                <img src="{{ asset('storage/customers/' . $customer->imagen ) }}" alt="img" height="50" width="50" class="rounded">
                                            </span>
                                        </td>
                                        -->
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->id}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->name}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->last_name}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->ci}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->company}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->ruc}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->address}}</b></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$customer->phone}}</b></h6>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button
                                                wire:click.prevent="setCustomer({{$customer->id}}, '{{$customer->name}}')"
                                                class="btn btn-dark">
                                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                                AGREGAR CLIENTE
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">SIN RESULTADOS</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">CERRAR VENTANA</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
       
       window.addEventListener('focusInput', event => {
       
let input = document.getElementById('modal-search-input');
if(input) {
   input.focus();
} });


window.addEventListener('closeModal', event => {
   $('#modalSearchCustomer').modal('hide');
});

})


    </script>
</div>