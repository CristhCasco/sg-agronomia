@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre *</label>
            <input wire:model.lazy="name" type="text" class="form-control" placeholder="ej: Remera">
            @error('name') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Codigo *</label>
            <input wire:model.lazy="barcode" type="text" class="form-control" placeholder="ej: 0001">
            @error('barcode') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
            <div>
                <label for="is_weighted">¿Se vende por peso? *</label>
                <div class="form-group">
                    <input type="checkbox" id="is_weighted" wire:model="is_weighted">
                </div>
            </div>
    </div>
    

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="price">Precio por Unidad</label>
            <input type="number" id="price" wire:model="price" step="0.01" class="form-control" 
                {{ $is_weighted ? 'disabled' : '' }}>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="price_per_kg">Precio por Kilogramo</label>
            <input type="number" id="price_per_kg" wire:model="price_per_kg" step="0.01" class="form-control"
                {{ $is_weighted ? '' : 'disabled' }}>
        </div>
    </div>


    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Marca</label>
            <input wire:model.lazy="brand" type="text" class="form-control" placeholder="ej: Nike">
            @error('brand') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <!-- <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Modelo</label>
            <input wire:model.lazy="model" type="text" class="form-control" placeholder="ej: Deportivo">
            @error('model') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div> -->
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Tamaño</label>
            <input wire:model.lazy="size" type="text" class="form-control" placeholder="ej: 12/14/P/M/G/GG">
            @error('size') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Color</label>
            <input wire:model.lazy="color" type="text" class="form-control" placeholder="ej: AZUL">
            @error('color') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Descripcion</label>
            <input wire:model.lazy="description" type="text" class="form-control" placeholder="ej: Elegante Remera Deportiva">
            @error('description') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Costo</label>
            <input wire:model.lazy="cost" type="number"  class="form-control" placeholder="ej: 10000">
            @error('cost') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <!-- <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Precio *</label>
            @can('MODIFICPRICE')
            <input wire:model.lazy="price" type="number"  class="form-control" placeholder="ej: 15000">
            @error('price') <span class="text-danger er">{{ $message }}</span> @enderror
            @else
            {}
            @endcan
        </div>
    </div> -->

    <!-- <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Precio *</label>
            <input wire:model.lazy="price" type="number" class="form-control" placeholder="ej: 15000">
            @error('price') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div> -->

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock *</label>
            <input wire:model.lazy="stock" type="number" class="form-control" placeholder="ej: 50">
            @error('stock') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <!-- <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock Minimo *</label>
            <input wire:model.lazy="min_stock" type="number"  class="form-control" placeholder="ej: 10">
            @error('min_stock') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div> -->

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Categoria *</label>
            <select wire:model='categoryid' class="form-control">
                <option value="Elegir" disabled>Elegir</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('categoryid') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- <div class="col-sm-12 col-md-8">
        <div class="form-group custom-file">
            <input type="file" class="custom-file-input" wire:model="image"
            accept="image/x-png, image/gif, image/jpeg"
            >
            <label class="custom-file-label"> Imagen {{$image}}</label>
            @error('image') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div> -->

</div>


@include('common.modalFooter')