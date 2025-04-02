<div>
    <div class="row sales layout-top-spacing">
        
        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>{{$componentName}} | {{$pageTitle}}</b>
                    </h4>
                    <ul class="tabs tab-pills">
                        <li>
                            <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                        </li>
                    </ul>
                </div>
                
                @include('common.searchbox')

                <div class="widget-content">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table striped mt-1">
                            <thead class="text-white" style="background: #620408">
                                <tr>
                                    <th class="table-th text-white">NOMBRE</th>
                                    <th class="table-th text-white text-center">APELLIDO</th>
                                    <th class="table-th text-white text-center">C.I</th>
                                    <th class="table-th text-white text-center">USUARIO</th>
                                    <th class="table-th text-white text-center">CORREO</th>
                                    <th class="table-th text-white text-center">CELULAR</th>
                                    <th class="table-th text-white text-center">DIRECCION</th>
                                    <th class="table-th text-white text-center">PERFIL</th>
                                    <th class="table-th text-white text-center">ESTADO</th>
                                    <th class="table-th text-white text-center">IMAGEN</th>
                                    <th class="table-th text-white text-center">ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                    <tr>
                                        <td><h6>{{$r->name}}</h6></td>
                                        <td class="text-center"><h6>{{$r->last_name}}</h6></td>
                                        <td class="text-center"><h6>{{$r->ci}}</h6></td>
                                        <td class="text-center"><h6>{{$r->user}}</h6></td>
                                        <td class="text-center"><h6>{{$r->email}}</h6></td>
                                        <td class="text-center"><h6>{{$r->phone}}</h6></td>
                                        <td class="text-center"><h6>{{$r->address}}</h6></td>
                                        <td class="text-center">
                                            <span class="badge {{ $r->profile == 'Active' ? 'badge-success' : 'badge-danger'}}
                                            text-uppercase">{{$r->profile}}
                                            </span>
                                        </td>

                                        <!--
                                        <td class="text-center text-uppercase">
                                            <h6>{{$r->profile}}</h6>
                                            <small><b>Roles:</b>{{implode(',',$r->getRoleNames()->toArray())}}</small>
                                        </td>

                                        -->
                                        
                                        <td class="text-center">
                                            <span class="badge {{ $r->status == 'ACTIVO' ? 'badge-success' : 'badge-danger'}} 
                                            text-uppercase">{{ $r->status }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if($r->image !=null)
                                                <img src="{{ asset('storage/users/'. $r->image )}}" alt="imagen" class="card-img-top img-fluid">
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="javascript:void(0)"
                                            wire:click="Edit({{$r->id}})"
                                            class="btn btn-dark mtmobile" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                            onclick="Confirm('{{$r->id}}')"
                                            class="btn btn-dark" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$data->links()}}
                    </div>

                </div>


            </div>


        </div>
        
        @include('livewire.users.form')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function(){
            window.livewire.on('user-added', msg=>{
                $('#theModal').modal('hide')
                noty(msg)
            })
            window.livewire.on('user-updated', msg=>{
                $('#theModal').modal('hide')
                noty(msg)
            })
            window.livewire.on('user-deleted', msg=>{
                noty(msg)
            })
            window.livewire.on('hide-modal', msg=>{
                $('#theModal').modal('hide')
            })
            window.livewire.on('show-modal', msg=>{
                $('#theModal').modal('show')
            })
            window.livewire.on('user-withsales', msg=>{
                noty(msg)
            })

        });

        function Confirm(id)
            {
                swal({
                    title: 'CONFIRMAR',
                    text: '¿CONFIRMAS ELIMINAR EL USUARIO?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'CERRAR',
                    cancelButtonColor: '#fff',
                    confirmButtonText: 'ACEPTAR',
                    confirmButtonColor: '#620408'
                }).then(function(result){
                    if(result.value){
                        window.livewire.emit('deleteRow', id);
                        swal.close();
                    }
                });
            }    
    </script>
</div>
