<div>
    <div wire:ignore.self class="modal fade modal-fullscreen" id="modalSearchProduct" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <div class="input-group">
                        <input type="text" wire:model="search" id="modal-search-input"
                            placeholder="Puedes buscar por nombre del producto, código ó categoría..."
                            class="form-control">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-gp">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-body">
                    {{-- <h1>{{ url()->current() }}</h1> --}}
                    <div class="row p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #620408">
                                    <tr>
                                        <th width="4%"></th>
                                        <th class="table-th text-left text-white">NOMBRE</th>
                                        <th class="table-th text-left text-white">MARCA</th>
                                        <th class="table-th text-left text-white">MODELO</th>
                                        <th class="table-th text-left text-white">DESCRIPCION</th>
                                        <th class="table-th text-left text-white">TAMAÑO</th>
                                        <th class="table-th text-left text-white">COLOR</th>
                                        <th width="13%" class="table-th text-center text-white">PRECIO</th>
                                        <th class="table-th text-center text-white">
                                            <button wire:click.prevent="addAll" class="btn btn-info" {{count($products)>
                                                0 ? '' : 'disabled' }}>
                                                <i class="fas fa-check"></i>
                                                TODOS
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <span>
                                                <img src="{{ asset('storage/products/' . $product->imagen ) }}"
                                                    alt="img" height="50" width="50" class="rounded">
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->name}}</h6>
                                                <small class="text-info">{{$product->barcode}}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->brand}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->model}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->description}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->size}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <h6>{{$product->color}}</h6>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ number_format($product->price,0)}} Gs.</h6>
                                        </td>
                                        <td class="text-center">
                                            <!--<button wire:click.prevent="$emit('scan-code-byid',{{$product->id}})" class="btn btn-dark">
                                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                                AGREGAR
                                            </button>
                                            -->

                                            <button wire:click.prevent="addProduct({{$product->id}})"
                                                class="btn btn-dark">
                                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                                AGREGAR
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


</div>