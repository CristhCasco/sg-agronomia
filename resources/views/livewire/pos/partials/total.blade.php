<div class="row">

    <div class="col-sm-12">
        <div>
            <div class="connect-sorting">

                <h5 class="text-center mb-3">RESUMEN DE VENTA</h5>
                <div class="cennect-sorting-content">
                    <div class="card simple-title-task ui-sorteable-handle">
                        <div class="card-body">

                            <div class="task-header">
                                <div>
                                    <h2>TOTAL: {{number_format($total,0)}} Gs.</h2>
                                    <input type="hidden" id="hiddenTotal" value="{{$total}}">
                                </div>
                                <div>
                                    <h4 class="mt-3">Articulos: {{$itemsQuantity}}</h4>
                                </div>
                               
                            </div>
                            
                            <!-- <select wire:model="customer_selected_id" class="form-control">
                                <option value="0">Cliente</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select> 

                            @if($customerName == null)
                            <h6>Selecciona cliente:</h6>
                            @else
                            <h6>Cliente: {{$customerId}} | <b> {{ $customerName}}</b></h6>
                            @endif
                            -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>