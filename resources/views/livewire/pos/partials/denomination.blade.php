<div class="row mt-3">
    <div class="col-sm-12">
        <div class="connect-sorting">
            <!--<h5 class="text-center mb-2">DENOMINACIONES</h5>-->
            <div class="container">
                <div class="row">
                    @foreach($denominations as $d)
                        <div class="col-md-12 mt-2">
                            <button wire:click.prevent="ACash({{$d->value}})" class="btn btn-dark btn-block den">
                                {{$d->value > 0 ? number_format($d->value,0).' Gs.' : 'Exacto' }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="connect-sorting-content mt-4">
                <div class="card simple-title task ui-sorteable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm" style="background: #620408; color:white">
                                EFECTIVO F2
                                </span>
                            </div>
                            <input type="number" id="cash"
                            wire:model="cash"
                            wire:keydown.enter="saveSale"
                            class="form-control text-center" value="{{$cash}}"

                            >
                            <div class="input-group-append">
                                <span wire:click="$set('cash', 0)" class="input-group-text" style="
                                    background: #620408; color:white">
                                    <i class="fas fa-backspace fa-2x"></i>

                                </span>
                            </div>
                        </div>
                        <h4 class="text-muted">
                            @if($cash <= 0)
                                Cambio :    0 Gs.
                            @else
                                Cambio : {{number_format($this->cash - $this->total,0)}} Gs.
                            @endif
                        </h4>
                        <div class="row justify-content-between mt-5 ">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if($total > 0)
                                    <button onclick="Confirm('','clearCart', 'SEGURO DESEA ELIMINAR TODOS LOS REGISTROS?')"
                                    class="btn btn-dar mtmobile">
                                    CANCELAR F4
                                    </button>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if(($cash >= $total && $total > 0) || $payment_type == 'CREDITO')
                                    <button wire:click.prevent="saveSale" class="btn btn-dark btn-md btn-block">
                                        <i class="fas fa-save"></i>
                                        GUARDAR F3
                                    </button>
                                @endif
                            </div>


                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>