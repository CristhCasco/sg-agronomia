
<div class="row sales layout-top-spacing">
    
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="
                        #theModal">Agregar</a>
                    </li>
                </ul>
            </div>
            @include('common.searchbox')



            <div class="widget-content">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #620408">
                            <tr>
                                <th class="table-th text-white">TIPO</th>
                                <th class="table-th text-white text-center">VALOR</th>
                                <th class="table-th text-white text-center">IMAGEN</th>
                                <th class="table-th text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $denomination)
                            <tr>
                                <td><h6>{{$denomination->type}}</h6></td>
                                <td><h6 class="text-center">{{number_format($denomination->value,0)}} Gs.</h6></td>
                                <td class="text-center">
                                    <span>
                                        <img src=" {{ asset('storage/' .$denomination->imagen)}}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                    wire:click="Edit({{$denomination->id}})"
                                    class="btn btn-dark mtmobile" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>


                                    <a href="javascript:void(0)"
                                    onclick="Confirm('{{$denomination->id}}')"
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

    @include('livewire.denominations.form')
    

</div>




<script>
	document.addEventListener('DOMContentLoaded', function(){

        
        window.livewire.on('item-added', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('item-updated', msg =>{
            $('#theModal').modal('hide')
        });
        window.livewire.on('item-deleted', msg =>{
            //notificacion
        });

        window.livewire.on('show-modal', msg =>{
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg =>{
            $('#theModal').modal('hide')
        });

        $('#theModal').on('hidden.bs.modal', function (e) {
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
	
</script>