<div class="container">
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="cart-title text-center">
                        <h3>Gestión de Créditos y Pagos</h3>
                    </h4>
                </div>

                <!-- Tabla de Créditos -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                    <thead class="bg-white text-white" style="color: white;">
                            <tr>
                                <th>Cliente</th>
                                <th>Monto Total</th>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($credits as $credit)
                                <tr>
                                    <td>{{ optional($credit->customer)->name ?? 'Sin cliente' }}</td>
                                    <td>{{ number_format($credit->total_credit, 2) }} Gs.</td>
                                    <td>{{ number_format(min($credit->amount_paid, $credit->total_credit), 2) }} Gs.</td>
                                    <td>{{ number_format(max(0, $credit->total_credit - $credit->amount_paid), 2) }} Gs.</td>
                                    <td>
                                        <!-- <span class="badge {{ $credit->remaining_balance <= 0 ? 'badge-success' : 'badge-warning' }}">
                                            {{ $credit->remaining_balance <= 0 ? 'PAGADO' : 'PENDIENTE' }}
                                        </span> -->
                                        <span class="badge {{ $credit->status == 'PAGADO' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $credit->status }}
                                        </span>


                                    </td>
                                    <td>
                                        @if ($credit->status == 'PENDIENTE' && $credit->remaining_balance > 0)
                                            <button wire:click="openPaymentModal({{ $credit->id }})"
                                                class="btn btn-primary btn-sm">
                                                Pagar
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Liquidado</button>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @include('livewire.credits.sales.salePaymentModal') <!-- INCLUIR EL MODAL -->


</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-payment-modal', () => {
            $('#paymentModal').modal('show');
        });

        window.livewire.on('hide-payment-modal', () => {
            $('#paymentModal').modal('hide');
        });

        window.livewire.on('credit-updated', () => {
            window.livewire.emit('refreshComponent'); // 🔄 Refresca Livewire
        });
    });
</script>



