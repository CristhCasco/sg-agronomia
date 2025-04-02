<div class="row">

    <div class="col-sm-12">
        <div>
            <div class="connect-sorting">

                <h5 class="text-center mb-3">RESUMEN DE COMPRA</h5>
                <div class="cennect-sorting-content">
                    <div class="card simple-title-task ui-sorteable-handle">
                        <div class="card-body">

                            <div class="task-header">
                                <div>
                                    <h2>TOTAL: {{number_format($totalCarrito,0)}} Gs.</h2>
                                    <input type="hidden" id="hiddenTotal" value="{{$total}}">
                                </div>
                                <div>
                                    <h4 class="mt-3">Articulos: {{$totalItems}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>