<div>
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget">
                <div class="widget-heading">
                    <div>
                        <h4 class="card-title"><b>{{$componentName}}</b></h4>
                    </div>
                    <div><a href="{{ route('reports')}}"><i class="fas fa-hand-point-left"></i> Regresar</a></div>
                </div>
                <div class="widget-content">
                    <div class="row mb-3 align-items-end">
                        
                    <div class="col-md-2">
                            <label class="text-muted">Cliente</label>
                            <select wire:model="customerId" class="form-control">
                                <option value="0">Todos</option>
                                @foreach(App\Models\Customer::orderBy('name')->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="col-md-2">
                            <label class="text-muted">Usuario</label>
                            <select wire:model="userId" class="form-control">
                                <option value="0">Todos</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="col-md-2">
                            <label class="text-muted">Estado</label>
                            <select wire:model="statusFilter" class="form-control">
                                <option value="ALL">Todos</option>
                                <option value="PAGADO">Pagado</option>
                                <option value="PENDIENTE">Pendiente</option>
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label class="text-muted">Tipo de Reporte</label>
                            <select wire:model="reportType" class="form-control">
                                <option value="0">Ventas del día</option>
                                <option value="1">Ventas por fecha</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="text-muted">Desde</label>
                            <input type="text" wire:model="dateFrom" class="form-control flatpickr"
                                placeholder="Desde" {{ $reportType==0 ? 'disabled' : '' }}>
                        </div>

                        <div class="col-md-2">
                            <label class="text-muted">Hasta</label>
                            <input type="text" wire:model="dateTo" class="form-control flatpickr"
                                placeholder="Hasta" {{ $reportType==0 ? 'disabled' : '' }}>
                        </div>


                        <div class="col-md-2 d-flex gap-1">
                            <button wire:click="$refresh" class="btn btn-dark w-100">Consultar</button>
                            <a class="btn btn-secondary w-100 {{ count($data) < 1 ? 'disabled' : '' }}"
                            href="{{ url('report/pdf/' . $userId . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}"
                            target="_blank">PDF</a>
                        </div>
                    </div>

                        <div class="col-12">
                            <!--TABLA-->
                            <div class="table-responsive">
                                <table class="table table-bordered table striped mt-1">
                                    <thead class="text-white" style="background: #620408">
                                        <tr>
                                            <th class="table-th text-white text-center">CIENTE</th>
                                            <th class="table-th text-white text-center">TOTAL</th>
                                            <th class="table-th text-white text-center">ITEMS</th>
                                            <th class="table-th text-white text-center">ESTADO</th>
                                            <th class="table-th text-white text-center">USUARIO</th>
                                            <th class="table-th text-white text-center">FECHA</th>
                                            <th class="table-th text-white text-center">ACCION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- @if(count($data) <1)
                                        <tr>
                                            <td colspan="7"><h5>Sin Resultados</h5></td>
                                        </tr>
                                        @endif -->
                                        @forelse($data as $d)
                                        <tr>
                                            <td class="text-center">
                                                <h6>{{$d->customer}}</h6>
                                            </td>

                                            <td class="text-center">
                                                <h6>{{number_format($d->total,0)}} Gs.</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{$d->items}}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{$d->status}}</h6>
                                            </td>

                                            <td class="text-center">
                                                <h6>{{$d->usuario}}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>
                                                    {{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}
                                                </h6>
                                            </td>

                                            <td class="text-center" width="50px">
                                                <button wire:click.prevent="getDetails({{$d->id}})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>

                                                <button wire:click="confirmDelete({{ $d->id }})" class="btn btn-danger btn-sm" title="Eliminar venta">
                                                    <i class="fas fa-trash"></i>
                                                </button>


                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td>Sin resultados</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if(isset($data) && count($data) > 0)
                                    <tfoot>
                                        <tr class="text-center">
                                            <td>
                                                <h4><strong>{{ number_format ($data->sum('total', 0))}} Gs.</strong>
                                                </h4>
                                            </td>
                                            <td>
                                                <h4>{{ $data->sum('items')}}</h4>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('livewire.reports.sales.sales-detail')
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function(){
            flatpickr(document.getElementsByClassName('flatpickr'),{
                enableTime: false,
                dateFormat: 'Y-m-d',
                locale: {
                    firstDayofWeek: 1,
                    weekdays: {
                        shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                        longhand: [
                        "Domingo",
                        "Lunes",
                        "Martes",
                        "Miércoles",
                        "Jueves",
                        "Viernes",
                        "Sábado",
                        ],
                    },
                    months: {
                        shorthand: [
                        "Ene",
                        "Feb",
                        "Mar",
                        "Abr",
                        "May",
                        "Jun",
                        "Jul",
                        "Ago",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dic",
                        ],
                        longhand: [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Noviembre",
                        "Diciembre",
                        ],
                    },
                }
            })

            //eventos
            window.livewire.on('show-modal', Msg =>{
                $('#modalDetails').modal('show')
            })
        })

        function rePrint(saleId)
        {
            window.open("print://" + saleId,  '_self').close()
        }
    </script>

    <script>
        window.addEventListener('confirm-delete-sale', () => {
            if (confirm('¿Estás seguro que deseas eliminar esta venta?')) {
                @this.call('deleteSale');
            }
        });

        // window.addEventListener('sale-deleted', () => {
        //     alert('✅ Venta eliminada correctamente.');
        // });

        window.addEventListener('sale-cannot-delete', () => {
            alert('⚠️ No se puede eliminar una venta con estado PENDIENTE. Marcar como pagado primero.');
        });
        
    </script>


</div>