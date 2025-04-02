@include('common.modalHead')

<div class="row">

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>PERSONA *</label>
            <select wire:model.lazy="person" class="form-control">
                <option value="Elegir" selected>Elegir</option>
                <option value="FISICA" selected>FISICA</option>
                <option value="JURIDICA" selected>JURIDICA</option>
            </select>
            @error('person') <span class="text-danger er">{{ $message}}</span>@enderror
        </div>
    </div>

     <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>NOMBRE *</label>
            <input wire:model.lazy="name" type="text" class="form-control" placeholder="ej: Antonio">
            @error('name') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>APELLIDO *</label>
            <input wire:model.lazy="last_name" type="text" class="form-control" placeholder="ej: Galarza">
            @error('last_name') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>C.I *</label>
            <input wire:model.lazy="ci" type="text" class="form-control" placeholder="ej: 1258741">
            @error('ci') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>EMPRESA</label>
            <input wire:model.lazy="company" type="text" class="form-control" placeholder="ej: CRIS INFO">
            @error('company') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>RUC</label>
            <input wire:model.lazy="ruc" type="text" class="form-control" placeholder="ej: 1258741-9">
            @error('ruc') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>DIRECCION</label>
            <input wire:model.lazy="address" type="text" class="form-control" placeholder="ej: LIMOY SUB URBANO - RUTA 2 - CDE - PY">
            @error('address') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>CELULAR *</label>
            <input wire:model.lazy="phone" type="text" class="form-control" placeholder="ej: 0986963258">
            @error('phone') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>NACIMIENTO</label>
            <input wire:model.lazy="birthday" type="date" class="form-control">
            @error('birthday') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>CORREO</label>
            <input wire:model.lazy="email" type="email"  class="form-control" placeholder="ej: antonio@outlook.com">
            @error('email') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-12">
        <div class="form-group custom-file">
            <input type="file" class="custom-file-input" wire:model="image"
            accept="image/x-png, image/gif, image/jpeg"
            >
            <label class="custom-file-label"> Imagen {{$image}}</label>
            @error('image') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

</div>


@include('common.modalFooter')