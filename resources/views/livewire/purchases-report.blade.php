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
                    <div class="row">

                        <div class="col-12 col-md-9">
                            <!--TABLAE-->
                            <div class="table-responsive">
                                <table class="table table-bordered table striped mt-1">
                                    <thead class="text-white" style="background: #620408">
                                        <tr>
                                            <th class="table-th text-white text-center">TOTAL</th>
                                            <th class="table-th text-white text-center">ITEMS</th>
                                            <th class="table-th text-white text-center">ESTADO</th>
                                            <th class="table-th text-white text-center">USUARIO</th>
                                            <th class="table-th text-white text-center">FECHA</th>
                                            <th class="table-th text-white text-center">ACTIONS</th>
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
                                                <button wire:click.prevent="getDetails({{$d->id}})"
                                                    class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>


                                                <!-- RE PRINT
                                                <button type="button" onclick="rePrint({{$d->id}})" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                -->
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

                        <div class="col-sm-12 col-md-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6>Elige el usuario</h6>
                                    <div class="form-group">
                                        <select wire:model="userId" class="form-control">
                                            <option value="0">Todos</option>
                                            @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <h6>Elige el tipo de reporte</h6>
                                    <div class="form-group">
                                        <select wire:model="reportType" class="form-control">
                                            <option value="0">Ventas del día</option>
                                            <option value="1">Ventas por fecha</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <h6>Fecha desde {{$dateFrom}}</h6>
                                    <div class="form-group">
                                        <input type="text" wire:model="dateFrom" class="form-control flatpickr"
                                            placeholder="Click para elegir" {{ $reportType==0 ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <h6>Fecha hasta {{ $dateTo}}</h6>
                                    <div class="form-group">
                                        <input type="text" wire:model="dateTo" class="form-control flatpickr"
                                            placeholder="Click para elegir" {{ $reportType==0 ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button wire:click="$refresh" class="btn btn-dark btn-block">
                                        Consultar
                                    </button>
                                    @if($reportType == 0)
                                    <a class="btn btn-dark btn-block {{count($data) <1 ? 'disabled' : '' }}"
                                        href="{{ url('report/purchases/pdf' . '/' . $userId . '/' . $reportType ) }}"
                                        target="_blank">Generar PDF</a>
                                    @else
                                    <a class="btn btn-dark btn-block {{count($data) <1 ? 'disabled' : '' }}"
                                        href="{{ url('report/purchases/pdf' . '/' . $userId . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}"
                                        target="_blank">Generar PDF.</a>
                                    @endif


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('livewire.reports.compras.purchases-detail')
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

</div>