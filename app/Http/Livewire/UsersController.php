<?php

namespace App\Http\Livewire;

use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;


class UsersController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $name, $last_name, $ci, $user, $password, $email, $phone, $address, $search, $selected_id, $pageTitle, $componentName, $image, $fileLoaded, $profile;
    private $pagination = 3;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Usuarios';
        $this->status = 'Elegir';
    }

    public function render()
    {
        if (strlen($this->search) > 0)
            $data = User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('ci', 'like', '%' . $this->search . '%')
                ->orWhere('user', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->select('*')->orderBy('name', 'asc')
                ->paginate($this->pagination);
        else
            $data = User::orderBy('name', 'asc')->paginate($this->pagination);

        return view('livewire.users.component', [
            'data' => $data,
            'roles' => Role::orderBy('name', 'asc')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function resetUI()
    {
        #agrega todos los campos a resetear
        $this->name = '';
        $this->last_name = '';
        $this->ci = '';
        $this->user = '';
        $this->password = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->image = '';
        $this->resetValidation();
        $this->resetPage();

        
    }

    public function Edit(User $user)
    {
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->ci = $user->ci;
        $this->user = $user->user;
        $this->password = '';
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        //TENER EN CUENTA EL PERFIL
        $this->profile = $this->profile;
        $this->status = $user->status;
        $this->emit('show-modal', 'show modal!');

    }

    protected $Listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'
    ];

    public function Store()
    {
        $rules = [
            'name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'ci' => 'required|min:3|unique:users',
            'user' => 'required|min:3|unique:users',
            'password' => 'required|min:3',
            'email' => 'required',
            'profile' => 'required',
            'status' => 'required'
        ];

        $messages = [
        
        ];

        $this->validate($rules, $messages);

        $user = User::create([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'ci' => $this->ci,
            'user' => $this->user,
            'password' => bcrypt($this->password),
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'profile' => $this->profile,
            'status' => $this->status
        ]);

        $user->syncRoles($this->profile);

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/users', $customFileName);
            $user->image = $customFileName;
            $user->save();
        }

        $this->resetUI();
        $this->emit('user-added', 'Usuario Registrado');
    }

    public function Update()
    {
        $rules = [
            'email' => "required|email|unique:users,email,{$this->selected_id}",
            'name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'ci' => "required|min:3|unique:users,ci,{$this->selected_id}",
            'user' => "required|min:3|unique:users,user,{$this->selected_id}",
            'password' => 'min:3',
            'phone' => 'required|min:3',
            'address' => 'required|min:3',
            'profile' => 'required|not_in:Elegir',
            'status' => 'required|not_in:Elegir',
        ];

        $messages = [
            'name.required' => 'Nombre requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'last_name.required' => 'Apellido requerido',
            'last_name.min' => 'El apellido debe tener al menos 3 caracteres',
            'ci.required' => 'Cedula requerida',
            'ci.min' => 'La cedula debe tener al menos 3 caracteres',
            'ci.unique' => 'La cedula ya existe',
            'user.required' => 'Usuario requerido',
            'user.min' => 'El usuario debe tener al menos 3 caracteres',
            'user.unique' => 'El usuario ya existe',
            'password.min' => 'La contraseña debe tener al menos 3 caracteres',
            'email.required' => 'Correo requerido',
            'email.unique' => 'El correo ya existe',
            'phone.required' => 'Celular requerido',
            'phone.min' => 'El celular debe tener al menos 3 caracteres',
            'address.required' => 'Direccion requerida',
            'address.min' => 'La direccion debe tener al menos 3 caracteres',
            'profile.not_in' => 'Seleccione el Perfil',
            'profile.min' => 'El perfil debe tener al menos 3 caracteres',
            'status.not_in' => 'Selecciones el estado',
            'status.min' => 'El estado debe tener al menos 3 caracteres',
        ];

        $this->validate($rules, $messages);

        $user = User::find($this->selected_id);
        $user->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'ci' => $this->ci,
            'user' => $this->user,
            'password' => bcrypt($this->password),
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'profile' => $this->profile,
            'status' => $this->status
        ]);

        $user->syncRoles($this->profile);

        if ($this->image) {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/users', $customFileName);
            $imageTemp = $user->image;

            $user->image = $customFileName;
            $user->save();

            if ($imageTemp != null) {
                if (file_exists('storage/users/' . $imageTemp)) {
                    unlink('storage/users/' . $imageTemp);
                }
            }
        }

        $this->resetUI();
        $this->emit('user-updated', 'Usuario Actualizado');
    }


    public function destroy(User $user)
    {
        
        if($user) {
            $sales = Sale::where('user_id', $user->id)->count();
            if($sales > 0) {
                $this->emit('user-withsales', 'No se puede eliminar el usuario porque tiene ventas registradas');
            }else {
                $user->delete();
                $this->resetUI();
                $this->emit('user-deleted', 'Usuario Eliminado');
            }
        }


        $imageTemp = $user->image;
        $user->delete();

        if ($imageTemp != null) {
            if (file_exists('storage/users/' . $imageTemp)) {
                unlink('storage/users/' . $imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('user-deleted', 'Usuario Eliminado');
    }
}
