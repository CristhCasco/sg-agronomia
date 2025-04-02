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
								<th class="table-th text-white">PERSONA</th>
								<th class="table-th text-white text-center">NOMBRE</th>
								<th class="table-th text-white text-center">APELLIDO</th>
								<th class="table-th text-white text-center">C.I</th>
								<th class="table-th text-white text-center">EMPRESA</th>
								<th class="table-th text-white text-center">RUC</th>
								<th class="table-th text-white text-center">DIRECCION</th>
								<th class="table-th text-white text-center">CELULAR</th>
								<th class="table-th text-white text-center">NACIMIENTO</th>
								<th class="table-th text-white text-center">CORREO</th>
								<th class="table-th text-white text-center">IMAGEN</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
						</thead>
						<tbody>

                            @foreach($data as $suppliers)
							<tr>
								<td><h6>{{$suppliers->person}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->name}}</h6></td>
								<td><h6 class="text-center">{{$suppliers->last_name}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->ci}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->company}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->ruc}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->address}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->phone}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->birthday}}</h6></td>
                                <td><h6 class="text-center">{{$suppliers->email}}</h6></td>
							
								<td class="text-center">
									<span>
										<img src="{{ asset('storage/suppliers/' . $suppliers->image ) }}"
										 alt="imagen de ejemplo" height="70" width="80" class="rounded">
									</span>
								</td>
								

								<td class="text-center">
									<a href="javascript:void(0)"
                                    wire:click.prevent="Edit({{$suppliers->id}})"                                        
                                    class="btn btn-dark mtmobile" title="Edit">
										<i class="fas fa-edit"></i>
									</a>

									<a href="javascript:void(0)"
                                        onclick="Confirm('{{$suppliers->id}}')"
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

	@include('livewire.suppliers.form')

</div>


<script>
	document.addEventListener('DOMContentLoaded', function(){

        
        window.livewire.on('supplier-added', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('supplier-updated', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('supplier-deleted', msg =>{
            //notificacion
        });

        window.livewire.on('modal-show', msg =>{
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg =>{
            $('#theModal').modal('hide')
        });

		$('#theModal').on('hidden.bs.modal', msg =>{
            $('.er').css('display', 'none')
        });

		
    });

	function Confirm(id, suppliers)
    {
        if(suppliers > 0)
        {
            swal('NO SE PUEDE ELIMINAR EL PROVEEDOR PORQUE TIENE REGISTROS ASOCIADAS');
            return;
        }
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
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