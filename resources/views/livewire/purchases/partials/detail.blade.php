<div>
    <style>
        thead tr {
            background-color: #620408 !important;
            color: #fff !important;
        }

        th {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .tblscroll {
            max-height: 400px;
            overflow-y: auto;
        }

        .tblscroll thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>


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
                        <table class="table bordered table-striped mt-1">
                        <thead style="background-color: #620408;">
                            <tr>
                                <th style="color: white; font-weight: bold; text-align: center;">CANTIDAD</th>
                                <th style="color: white; font-weight: bold; text-align: left;">DESCRIPCIÓN</th>
                                <th style="color: white; font-weight: bold; text-align: center;">COSTO</th>
                                <th style="color: white; font-weight: bold; text-align: center;">SUBTOTAL</th>
                                <th style="color: white; font-weight: bold; text-align: center;">MARGEN %</th>
                                <th style="color: white; font-weight: bold; text-align: center;">NUEVO PRECIO</th>
                                <th style="color: white; font-weight: bold; text-align: center;">ACCIONES</th>
                            </tr>
                        </thead>



                            <tbody>
                                @foreach($carrito as $index => $item)
                                    @php
                                        $nuevoPrecio = $item['cost'] * (1 + ($item['margen'] ?? 0) / 100);
                                    @endphp
                                    <tr>
                                        {{-- Cantidad --}}
                                        <td>
                                            <input type="number"
                                                class="form-control text-center"
                                                wire:model.lazy="carrito.{{ $index }}.qty"
                                                wire:change="UpdateQty({{ $item['id'] }}, carrito.{{ $index }}.qty)">
                                        </td>

                                        {{-- Descripción --}}
                                        <td>
                                            <h6>{{ $item['name'] }}</h6>
                                        </td>

                                        {{-- Costo --}}
                                        <td class="text-center">
                                            <input type="number"
                                                step="0.01"
                                                class="form-control text-center"
                                                wire:model.lazy="carrito.{{ $index }}.cost"
                                                wire:change="updateCost({{ $item['id'] }}, carrito.{{ $index }}.cost)">
                                        </td>

                                        {{-- Subtotal --}}
                                        <td class="text-center">
                                            <h6>{{ number_format($item['cost'] * $item['qty'], 0, ',', '.') }} Gs.</h6>
                                        </td>

                                        {{-- Margen --}}
                                        <td class="text-center">
                                            <input type="number"
                                                step="0.01"
                                                class="form-control text-center"
                                                wire:model.lazy="carrito.{{ $index }}.margen">
                                        </td>

                                        {{-- Nuevo Precio Venta --}}
                                        <td class="text-center">
                                            <h6 class="{{ $nuevoPrecio < (\App\Models\Product::find($item['id'])->price ?? 0) ? 'text-danger font-weight-bold' : '' }}">
                                                {{ number_format($nuevoPrecio, 0, ',', '.') }} Gs.
                                            </h6>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center">
                                            <button onclick="Confirm('{{ $item['id'] }}', 'removeItem', 'CONFIRMAS ELIMINAR EL REGISTRO?')" class="btn btn-dark mbmobile">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            <button wire:click.prevent="Increment({{ $item['id'] }})" class="btn btn-dark mbmobile">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h5 class="text-center text-muted">Agrega productos a la compra</h5>
                @endif

                </div>

                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">Guardando compra...</h4>
                </div>



            </div>
        </div>



    </div>

</div>


