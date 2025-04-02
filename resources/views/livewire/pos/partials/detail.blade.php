<div>
    <div class="connect-sorting mb-2">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="card simple-title-task ui-sorteable-handle">
                        @if($customerName == null)
                            <p class="h3 flex-grow-1 text-center">Seleccione el Cliente</p>
                        @else
                            <p class="h3 flex-grow-1 text-center">Cliente: {{$customerId}} | {{ $customerName }}</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <button class="btn btn-dark btn-block mb-3" data-toggle="modal" data-target="#modalSearchCustomer">
                        <i class="fas fa-search"></i>
                        Buscar Clientes
                    </button>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <button class="btn btn-dark btn-block" data-toggle="modal" data-target="#modalSearchProduct">
                        <i class="fas fa-search"></i>
                        Buscar Productos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="connect-sorting">
        <div class="connect-sorting-content">
            <div class="card simple-title-task ui-sortable-handle">
                <div class="card-body">
                    @if($total > 0)
                        <div class="table-responsive tblscroll">
                            <table class="table bordered table-striped mt-1">
                                <thead class="text-white" style="background: #620408">
                                    <tr>
                                        <!-- <th class="table-th text-left text-white">CÓDIGO</th> -->
                                        <th width="13%" class="table-th text-center text-white">CANTIDAD</th>
                                        <th class="table-th text-left text-white">DESCRIPCIÓN</th>
                                        <th class="table-th text-center text-white">PRECIO</th>
                                        <th class="table-th text-center text-white">SUB TOTAL</th>
                                        <th class="table-th text-center text-white">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posCart as $item)
                                        <tr>
                                            <!-- <td class="text-left">
                                                <a>{{$item['barcode']}}</a>
                                            </td> -->

                                            <td>
                                                <input type="number" id="r{{$item['id']}}"
                                                    wire:change="updateQuantity({{$item['id']}}, $('#r' + {{$item['id']}}).val() )"
                                                    style="font-size: 1rem!important"
                                                    class="form-control text-center"
                                                    @if(isset($item['w']) && intval($item['w']) ==1)
                                                    value="{{ number_format($item['quantity'], 3) }}"
                                                    @else
                                                    value="{{ number_format($item['quantity'], 0) }}"
                                                    @endif
                                                >
                                            </td>

                                            <td>
                                               <a>
                                                   {{$item['name']}}
                                               </a>
                                           </td>
                                           
                                            <td class="text-center">
                                                {{number_format($item['price'], 0)}} Gs.
                                            </td>
                                            <td class="text-center">
                                                {{number_format($item['price'] * $item['quantity'], 0)}} Gs.
                                            </td>
                                            <td class="text-center">
                                            <button wire:click="removeItem({{ $item['id'] }})" class="btn btn-dark btn-sm mbmobile">
                                                    <i class="fas fa-trash-alt"></i>
                                            </button>
                                                <!-- <button onclick="Confirm('{{$item['id']}}', 'removeItem', 'CONFIRMAS ELIMINAR EL REGISTRO?' )"
                                                    class="btn btn-dark btn-sm mbmobile">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button> -->
                                                <button wire:click.prevent="decreaseQty({{$item['id']}})" class="btn btn-dark btn-sm mbmobile">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button wire:click.prevent="increaseQty({{$item['id']}})" class="btn btn-dark btn-sm mbmobile">
                                                    <i class="fas fa-plus"></i>
                                                </button>

                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h5 class="text-center text-muted">Agrega Productos a la venta</h5>
                    @endif
                </div>
                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">Guardando Venta...</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function Confirm(id, action, message) {
        if (confirm(message)) {
            window.livewire.emit(action, id);
        }
    }

    function showImageModal(imageUrl) {
        document.getElementById('productImage').src = imageUrl;
        $('#imageModal').modal('show');
    }
</script>