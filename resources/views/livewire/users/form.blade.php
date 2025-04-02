@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Nombre</label>
            <input wire:model.lazy="name" type="text" class="form-control" placeholder="ej: Jesus">
            @error('name') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Apellido</label>
            <input wire:model.lazy="last_name" type="text" class="form-control" placeholder="ej: Medina">
            @error('last_name') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>C.I</label>
            <input wire:model.lazy="ci" type="text" class="form-control" placeholder="ej: 2347568">
            @error('ci') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Usuario</label>
            <input wire:model.lazy="user" type="text" class="form-control" placeholder="ej: jmedina">
            @error('user') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <!--
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Contraseña</label>
            <input wire:model.lazy="password" type="password" class="form-control" placeholder="ej: Mj951/* ">
            @error('password') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    -->

    <form wire:submit.prevent="submit">
    <div class="form-group">
        <label>Contraseña</label>
        <input wire:model.lazy="password" type="password" class="form-control" placeholder="ej: Mj951/* " autocomplete="new-password">
        @error('password') <span class="text-danger er">{{ $message }}</span> @enderror
    </div>
    </form>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Correo</label>
            <input wire:model.lazy="email" type="text" class="form-control" placeholder="ej: jmedina@outlook.com">
            @error('email') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Celular</label>
            <input wire:model.lazy="phone" type="text" class="form-control" placeholder="ej: 0986357951">
            @error('phone') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Direccion</label>
            <input wire:model.lazy="address" type="text"  class="form-control" placeholder="ej: Barrio Carolina - San Alberto - CDE - PY">
            @error('address') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Perfil</label>
            <select wire:model.lazy='profile' class="form-control">
                <option value="Elegir" selected>Elegir</option>
                @foreach ($roles as $role)
                <option value="{{$role->name}}" selected > {{$role->name}}</option>
                @endforeach 
            </select>
            @error('profile') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Estado</label>
            <select wire:model='status' class="form-control">
                <option value="Elegir" disabled>Elegir</option>
                <option value="ACTIVO" selected>ACTIVO</option>
                <option value="BLOQUEADO" selected>BLOQUEADO</option>   
            </select>
            @error('status') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>
    

    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Imagen de Perfil</label>
            <input type="file" class="custom-file-input" wire:model="image"
            accept="image/x-png, image/gif, image/jpeg" class="form-control"
            >
            @error('image') <span class="text-danger er">{{ $message }}</span> @enderror
        </div>
    </div>

</div>


@include('common.modalFooter')