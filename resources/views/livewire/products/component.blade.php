<div class="row sales layout-top-spacing">
	
	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs tab-pills">
					@can('Product_Create')
					<li>
						<a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
					</li>
					@endcan
				</ul>
			</div>
			@can('Product_Search')
			@include('common.searchbox')
			@endcan
			<div class="widget-content">
				
				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #620408">
							<tr>
								<th class="table-th text-white">NOMBRE</th>
								<th class="table-th text-white text-center">CODIGO</th>
								<!-- <th class="table-th text-white text-center">DESCRIPCION</th> -->
								<!--<th class="table-th text-white text-center">CATEGORIA</th>-->
								<th class="table-th text-white text-center">MARCA</th>
								<!-- <th class="table-th text-white text-center">MODELO</th> -->
								<th class="table-th text-white text-center">TAMAÑO</th>
								<!-- <th class="table-th text-white text-center">COLOR</th> -->
								<th class="table-th text-white text-center">COSTO</th>
								<th class="table-th text-white text-center">PRECIO</th>
								<th class="table-th text-white text-center">STOCK</th>
								<!--
								<th class="table-th text-white text-center">STOCK MINIMO</th>
								<th class="table-th text-white text-center">IMAGEN</th>
								-->
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
						</thead>
						<tbody>

                            @foreach($data as $product)
							<tr>
								<td><h6 style="font-size: 14px;">{{$product->name}}</h6></td>
                                <td><h6 class="text-center" style="font-size: 14px;">{{$product->barcode}}</h6></td>
								<!-- <td><h6 class="text-center" style="font-size: 14px;">{{$product->description}}</h6></td> -->
								<!-- <td><h6 class="text-center" style="font-size: 14px;">{{$product->category}}</h6></td> -->
                                <td><h6 class="text-center" style="font-size: 14px;">{{$product->brand}}</h6></td>
                                <!-- <td><h6 class="text-center" style="font-size: 14px;">{{$product->model}}</h6></td> -->
                                <td><h6 class="text-center" style="font-size: 14px;">{{$product->size}}</h6></td>
                                <!-- <td><h6 class="text-center" style="font-size: 14px;">{{$product->color}}</h6></td> -->
                                <td><h6 class="text-center" style="font-size: 14px;">{{number_format($product->cost), 0}}</h6></td>
                                <td>
									<h6 class="text-center" style="font-size: 14px;">
										@if($product->is_weighted)
											{{ number_format($product->price_per_kg, 0) }} Gs.
										@else
											{{ number_format($product->price, 0) }} Gs.
										@endif
									</h6>
								</td>
                                <td>
									<h6 class="text-center" style="font-size: 14px;">
										@if($product->is_weighted)
											{{ number_format($product->stock, 3) }}
										@else
											{{ number_format($product->stock, 0) }}
										@endif
									</h6>
								</td>
								<!--
                                <td><h6 class="text-center">{{$product->min_stock}}</h6></td>
								

								<td class="text-center">
									<span>
										<img src="{{ asset('storage/products/' . $product->imagen ) }}"
											alt="imagen de ejemplo" height="70" width="80" class="rounded"
											onclick="showImageModal('{{ asset('storage/products/' . $product->imagen ) }}')">
									</span>
								</td>

								-->
								

								<td class="text-center">
									@can('Product_Update')
									<a href="javascript:void(0)"
                                    wire:click.prevent="Edit({{$product->id}})"                                        
                                    class="btn btn-dark mtmobile" title="Edit">
										<i class="fas fa-edit"></i>
									</a>
									@endcan

									@can('Product_Destroy')
									<a href="javascript:void(0)"
                                        onclick="Confirm('{{$product->id}}')"
                                        class="btn btn-dark" title="Delete">
										<i class="fas fa-trash"></i>
									</a>
									@endcan


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

	<!-- Incluir el modal Imagen-->
	@include('livewire.modalimage.modalimage')

	@include('livewire.products.form')

	<!-- INCLUIR MODALIMAGE -->

</div>


<script>
	document.addEventListener('DOMContentLoaded', function(){

        
        window.livewire.on('product-added', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-updated', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-deleted', msg =>{
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

	function Confirm(id, products)
    {
        if(products > 0)
        {
            swal('NO SE PUEDE ELIMINAR LA CATEGORIA PORQUE TIENE PRODUCTOS ASOCIADOS');
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

	function showImageModal(imageUrl) {
        document.getElementById('productImage').src = imageUrl;
        $('#imageModal').modal('show');
    }
	
</script>