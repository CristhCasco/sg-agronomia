<!-- MODAL DETALLE DE CRÉDITO -->
<div wire:ignore.self class="modal fade" id="creditDetailsModal" tabindex="-1" aria-labelledby="creditDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="creditDetailsModalLabel">Detalle del Crédito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        @if ($selectedCredit)
          <h6><strong>Proveedor:</strong> {{ optional($selectedCredit->purchase->supplier)->name ?? 'Sin proveedor' }}</h6>
          <h6><strong>Total Crédito:</strong> {{ number_format($selectedCredit->total_credit, 0) }} Gs</h6>
          <h6><strong>Pagado:</strong> {{ number_format($selectedCredit->amount_paid, 0) }} Gs</h6>
          <h6><strong>Pendiente:</strong> {{ number_format($selectedCredit->remaining_balance, 0) }} Gs</h6>

          <hr>
          <h5>📜 Detalle de productos</h5>
          @php $totalProductos = 0; @endphp
          <ul>
            @foreach ($creditDetails as $item)
              @php
                $subtotal = $item->quantity * $item->price;
                $totalProductos += $subtotal;
              @endphp
              <li>
                {{ $item->product->name ?? 'Producto eliminado' }} - {{ $item->quantity }} x {{ number_format($item->price, 0) }} Gs =
                {{ number_format($subtotal, 0) }} Gs
              </li>

            @endforeach
          </ul>
          <p class="mt-2"><strong>Total productos:</strong> {{ number_format($totalProductos, 0) }} Gs</p>

          <hr>
          <h5>💵 Pagos realizados</h5>
          @php $totalPagos = 0; @endphp
          <ul>
            @forelse ($paymentHistory as $payment)
              @php $totalPagos += $payment->amount_paid; @endphp
              <li>
                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }} - {{ number_format($payment->amount_paid, 0) }} Gs
              </li>
            @empty
              <li>No hay pagos registrados</li>
            @endforelse
          </ul>

          <p class="mt-2"><strong>Total pagado:</strong> {{ number_format($totalPagos, 0) }} Gs</p>
        @else
          <p class="text-muted">Selecciona un crédito para ver el detalle.</p>
        @endif
      </div>
    </div>
  </div>
</div>