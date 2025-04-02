<div>
    <!--@json($carrito)-->
    <div class="card">
        <div class="card header">
            titulo
        </div>
        <div class="card-body">
            <div class="row">
<div class="col-sm-12">

<button wire:click.prevent="savePurchase" class="btn btn-secondary" {{$totalCarrito ==0 ? 'disabled' : '' }}>Guardar</button>

<table class="table">
    <tr>
        <td>ID</td>
        <td>DESCRIPCION</td>
        <td>CANT</td>
        <td>COSTO</td>
        <td>TOTAL</td>
        <td>Acciones</td>
</tr>
    <tbody>
        @forelse ($carrito as $producto)  
        <tr>
            <td>{{ $producto['id'] }}</td>
            <td>{{ $producto['name'] }}</td>
            <td>
                <input wire:keydown.enter.prevent="Increment({{ $producto['id']}}, $event.target.value, true)" type="text" class="form-control" value=" {{ $producto['qty']}}">
               
            </td>
            <td>{{ $producto['cost']}}</td>
            <td>{{ $producto['total']}}</td>
            <td>
                <buton wire:click.prevent="removeItem({{ $producto['id']}})" class="btn btn-primary">Remove</buton>
                <buton wire:click.prevent="Increment({{ $producto['id']}}, 1, false)" class="btn btn-primary">+</buton>
                <buton class="btn btn-primary">-</buton>
            </td>
        </tr>
        @empty
        <tr><td>Agrega productos al carrito de compras</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"></td>
            <td>${{$totalCarrito}}</td>
           
        </tr>       
    </tfoot>
</table>
</div>
            </div>
        </div>
    </div>
</div>
