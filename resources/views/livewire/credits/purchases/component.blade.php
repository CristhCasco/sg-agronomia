
<div class="container-fluid px-4">
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="cart-title text-center">
                        <h3>Gestión de Créditos de Compras</h3>
                    </h4>
                </div>

                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label>Buscar proveedor (nombre o RUC):</label>
                        <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Buscar...">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Filtrar por Estado:</label>
                        <select wire:model="filterStatus" class="form-control">
                            <option value="">Todos</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="PAGADO">Pagado</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Desde:</label>
                        <input type="date" wire:model="startDate" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Hasta:</label>
                        <input type="date" wire:model="endDate" class="form-control">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-secondary w-100">
                            Limpiar Filtros
                        </button>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button wire:click="$toggle('showOnlyExpiring')" class="btn btn-warning">
                            {{ $showOnlyExpiring ? 'Ver Todos' : 'Ver Créditos por Vencer' }}
                        </button>
                    </div>
                </div>

                @if ($aboutToExpireCount > 0)
                    <div class="alert alert-warning d-flex justify-content-between align-items-center">
                        <strong>💣 Atención:</strong> {{ $aboutToExpireCount }} crédito{{ $aboutToExpireCount > 1 ? 's' : '' }} vence{{ $aboutToExpireCount > 1 ? 'n' : '' }} en los próximos 3 días o ya están vencidos.
                    </div>
                @endif

                <!-- Tabla de Créditos -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-white text-white" style="color: white; background-color: #620408">
                            <tr>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Total Crédito</th>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                <th>Estado</th>
                                <th>Vencimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($credits as $credit)
                                <tr>
                                    <td>{{ $credit->created_at->format('d/m/Y') }}</td>
                                    <td>{{ optional($credit->supplier)->name ?? 'Sin proveedor' }}</td>
                                    <td>{{ number_format($credit->total_credit, 2) }} Gs.</td>
                                    <td>{{ number_format(min($credit->amount_paid, $credit->total_credit), 2) }} Gs.</td>
                                    <td>{{ number_format(max(0, $credit->remaining_balance), 2) }} Gs.</td>
                                    <td>
                                        <span class="badge {{ $credit->status == 'PAGADO' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $credit->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($credit->purchase && $credit->purchase->due_date)
                                            @php $days = now()->diffInDays($credit->purchase->due_date, false); @endphp
                                            @if ($days > 3)
                                                <span class="badge badge-success">{{ $days }} días</span>
                                            @elseif ($days > 0)
                                                <span class="badge badge-warning">⚠️ {{ $days }} días</span>
                                            @elseif ($days === 0)
                                                <span class="badge badge-danger">🔴 Vence hoy</span>
                                            @else
                                                <span class="badge badge-danger">❌ Vencido {{ abs($days) }} días</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Sin fecha</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if ($credit->status == 'PENDIENTE')
                                            <button wire:click="seleccionar({{ $credit->purchase_id }})" class="btn btn-primary btn-sm">Abonar</button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Liquidado</button>
                                        @endif
                                        <button wire:click="verDetalleCredito({{ $credit->purchase_id }})" class="btn btn-info btn-sm">Ver Detalle</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No hay créditos registrados</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-2">
                    {{ $credits->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('livewire.credits.purchases.creditDetailsModal')
    @include('livewire.credits.purchases.purchasesPaymentModal')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('show-payment-modal', () => {
            $('#paymentModal').modal('show');
        });

        window.addEventListener('show-credit-details', () => {
            $('#creditDetailsModal').modal('show');
        });

        window.addEventListener('purchase-ok', e => {
            alert(e.detail.msg); // o usar Toastr
        });

        window.addEventListener('purchase-error', e => {
            alert(e.detail.msg); // o usar Toastr
        });
    });
</script>

