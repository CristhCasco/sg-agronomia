<div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
        	<b>Detalle de Venta # {{$saleId}}</b>
        </h5>
        <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
      </div>
      <div class="modal-body">

        <div class="table-responsive">
          <table class="table table-bordered table striped mt-1">
            <thead class="text-white" style="background: #620408">
              <tr>
                <th class="table-th text-white text-center">FOLIO</th>
                <th class="table-th text-white text-center">PRODUCTO</th>
                <th class="table-th text-white text-center">PRECIO</th>
                <th class="table-th text-white text-center">CANT</th>
                <th class="table-th text-white text-center">IMPORTE</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $d)
                <tr>
                  <td class='text-center'><h6>{{ $d->id }}</h6></td>
                  <td class='text-center'><h6>{{ $d->product }}</h6></td>

                  @php
                    $precioBase = floatval($d->price);
                    $descuento = floatval($d->manual_discount ?? 0);
                    $precioFinal = max($precioBase - $descuento, 0);
                  @endphp

                  <!-- PRECIO -->
                  <td class='text-center'>
                    @if($descuento > 0)
                      <div>
                        <small class="text-muted" style="text-decoration: line-through;">
                          {{ number_format($precioBase, 0) }} Gs.
                        </small><br>
                        <strong class="text-success">
                          {{ number_format($precioFinal, 0) }} Gs.
                        </strong><br>
                        <small class="text-success">-{{ number_format($descuento, 0) }} Gs.</small>
                      </div>
                    @else
                      <h6>{{ number_format($precioBase, 0) }} Gs.</h6>
                    @endif
                  </td>

                  <!-- CANTIDAD -->
                  <td class='text-center'><h6>{{ number_format($d->quantity, 0) }}</h6></td>

                  <!-- IMPORTE -->
                  <td class='text-center'>
                    <h6>{{ number_format($precioFinal * $d->quantity, 0) }} Gs.</h6>
                  </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"><h5 class="text-center font-weight-bold">TOTALES</h5></td>
                <td><h5 class="text-center">{{$countDetails}}</h5></td>
                <td><h5 class="text-center">
                  {{number_format($sumDetails,0)}} Gs.
                </h5></td>
              </tr>
              <tr>
                <td colspan="4" class="text-right">
                  <h6 class="text-success font-weight-bold">Total Descuento</h6>
                </td>
                <td class="text-center">
                  <h6 class="text-success font-weight-bold">
                    {{ number_format($ahorroTotal, 0) }} Gs.
                  </h6>
                </td>
              </tr>

            </tfoot>
          </table>         
        </div>

        


      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
      </div>
    </div>
  </div>
</div>