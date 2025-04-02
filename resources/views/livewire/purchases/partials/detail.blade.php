<div>

    <div class="connect-sorting mb-2">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="card simple-title-task ui-sorteable-handle">
                        @if($supplierName == null)
                        <p class="h3 flex-grow-1 text-center"> Seleccione el Proveedor</p>
                        @else
                        <p class="h3 flex-grow-1 text-center">Proveedor : {{$supplierId}} | {{ $supplierName}}</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <button class="btn btn-dark btn-block mb-3" data-toggle="modal" data-target="#modalSearchSupplier">
                        <i class="fas fa-search"></i>
                        Buscar Proveedor
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

                    @if($totalCarrito > 0)
                    <div class="table-responsive tblscroll">

                        <table class="table bordered table-strped mt-1">
                            <thead class="text-white" style="background: #620408">
                                <tr>
                                    <th width="10%"></th>
                                    <th width="13%" class="table-th text-center text-white">CANTIDAD</th>
                                    <th class="table-th text-left text-white">DESCRIPCION</th>
                                    <th width="20%" class="table-th text-center text-white">COSTO</th>
                                    <th class="table-th text-center text-white">SUB TOTAL</th>
                                    <th class="table-th text-center text-white">ACCIONES</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($carrito as $item)
                                <tr>

                                    <td class="text-center table-th">

                                        <span>
                                            <img src="{{ asset('storage/products/' . $item['image']) }}"
                                                alt="imagen de producto" width="50" class="rounded">
                                        </span>

                                    </td>

                                    <td>
                                        <input type="number" id="r{{$item['id']}}"
                                            wire:keydown.enter.prevent="Increment({{$item['id']}}, $event.target.value, true )"
                                            wire:change="UpdateQty({{$item['id']}}, $event.target.value )"
                                            style="font-size: 1rem!important" class="form-control text-center"
                                            value="{{$item['qty']}}">
                                    </td>


                                    <!--PARA QUE NO SE ACTUALICE EL VALOR DEL INPUT CUANDO SE AGREGA UN PRODUCTO

                                        @if(is_object($item) && property_exists($item, 'quantity'))
                                            <input type="number" id="r{{$item->id}}"
                                                wire:change="updateQty({{$item->id}}, $('#r' + {{$item->id}}). val() )"
                                                style="font-size: 1rem!important"
                                                class="form-control text-center" 
                                                value="{{$item->quantity}}"
                                            >
                                        @endif

                                        -->

                                    <td>
                                        <h6>{{$item['name']}}</h6>
                                    </td>



                                    <td class="text-center">
                                        <input
                                            wire:keydown.enter.prevent="setCost({{$item['id']}}, $event.target.value, true )"
                                            type="number" class="form-control text-center"
                                            value="{{ intval($item['cost'] )}}">


                                    </td>

                                    <!--
                                    <td class="text-center">
                                        <input type="number" value="{{ $item['cost'] }}" wire:model="items.{{ $item['id'] }}.cost" class="form-control" />
                                    </td>
                                    -->

                                    <td class="text-center">
                                        <h6>
                                            {{number_format($item['cost'] * $item['qty'])}} Gs.
                                        </h6>
                                    </td>
                                    <td class="text-center">

                                        <button
                                            onclick="Confirm('{{$item['id']}}', 'removeItem', 'CONFIRMAS ELIMINAR EL REGISTRO?' )"
                                            class="btn btn-dark mbmobile">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>



                                        <button wire:click.prevent="Increment({{$item['id']}})"
                                            class="btn btn-dark mbmobile">
                                            <i class="fas fa-plus"></i>
                                        </button>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                    @else
                    <h5 class="text-center text-muted">Agrega Productos a la compra</h5>
                    @endif
                </div>

                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">Guardando compra...</h4>
                </div>



            </div>
        </div>



    </div>

</div>