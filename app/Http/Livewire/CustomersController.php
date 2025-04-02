<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;



class CustomersController extends Component
{
    use WithPagination;
    use WithFileUploads;


    public $search, $pageTitle, $componentName, $selected_id, $image, $person, $name, $email, $phone, $last_name, $ci, $company, $ruc, $address, $birthday, $description;
    private $pagination = 5;

    public function mount ()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Clientes';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        if(strlen($this->search) > 0 )
        {
            $customers = Customer::where('name','like','%'.$this->search.'%')
            ->orWhere('last_name','like','%'.$this->search.'%')
            ->orWhere('ci','like','%'.$this->search.'%')
            ->orWhere('address','like','%'.$this->search.'%')
            ->orWhere('email','like','%'.$this->search.'%')
            ->orWhere('company','like','%'.$this->search.'%')
            ->orWhere('ruc','like','%'.$this->search.'%')
            ->orderBy('id', 'desc')->paginate($this->pagination);
        }
        else

        $customers = Customer::orderBy('id', 'desc')->paginate($this->pagination);
        return view('livewire.customers.component', [
            'data' => $customers,
            //'customer' => Customer::orderBy('name','ASC')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store()
    {
        $rules = [
            'person' => 'required|not_in:Elegir',
            'name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'ci' => 'required|numeric',
            'phone' => 'required'
        
            
        ];

        $messages = [
            'person.required' => 'La persona es requerida',
            'person.not_in' => 'Elegir una opcion valida',
            'name.required' => 'Nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'phone.required' => 'El telefono es requerido',
            'person.required' => 'La persona es requerida',
            'last_name.required' => 'El apellido es requerido',
            'last_name.min' => 'El apellido debe tener al menos 3 caracteres',
            'ci.required' => 'El CI es requerido',
            'ci.numeric' => 'El CI debe ser solo numeros'
        ];

        $this->validate($rules, $messages);

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'person' => $this->person,
            'last_name' => $this->last_name,
            'ci' => $this->ci,
            'company' => $this->company,
            'ruc' => $this->ruc,
            'address' => $this->address,
            'birthday' => $this->birthday,
            'description' => $this->description
        ]);

        if ($this->image) {
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/customers', $customFileName);
			$customer->image = $customFileName;
			$customer->save();
		}

        $this->resetUI();
        $this->emit('customer-added','Cliente Registrado');
    }

   

    public function Edit(Customer $customer)
    {
        
        $this->selected_id = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->person = $customer->person;
        $this->last_name = $customer->last_name;
        $this->ci = $customer->ci;
        $this->company = $customer->company;
        $this->ruc = $customer->ruc;
        $this->address = $customer->address;
        $this->birthday = $customer->birthday;
        $this->image = null;

        $this->emit('modal-show','show modal!');
    }


    public function Update()
    {
        
        $rules = [
            'person' => 'required|not_in:Elegir',
            'name' => 'required|min:3',
            'phone' => 'required',
            'last_name' => 'required|min:3',
            'ci' => 'required'
        ];

        $messages = [
            'person.required' => 'La persona es requerida',
            'person.not_in' => 'Elegir una opcion valida',
            'name.required' => 'Nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'phone.required' => 'El telefono es requerido',
            'last_name.required' => 'El apellido es requerido',
            'last_name.min' => 'El apellido debe tener al menos 3 caracteres',
            'ci.required' => 'El CI es requerido'
        ];

        $this->validate($rules, $messages);

        $customer = Customer::find($this->selected_id);

        $customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'person' => $this->person,
            'last_name' => $this->last_name,
            'ci' => $this->ci,
            'company' => $this->company,
            'ruc' => $this->ruc,
            'address' => $this->address,
            'birthday' => $this->birthday
        ]);

        if ($this->image) 
        {
            $customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/customers', $customFileName);
            $imageTemp = $customer->image; //imagen temporal
			$customer->image = $customFileName;
			$customer->save();

            if($imageTemp != null)
            {
                if(file_exists('storage/customers/'.$imageTemp))
                {
                    unlink('storage/customers/'.$imageTemp);
                }
            }

        }

        $this->resetUI();
        $this->emit('customer-updated','Cliente Actualizado');
    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy($id)
    {
        $customer = Customer::find($id);
        $imageTemp = $customer->image; //imagen temporal
        $customer->delete();

        if($imageTemp != null)
        {
            if(file_exists('storage/customers/'.$imageTemp))
            {
                unlink('storage/customers/'.$imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('customer-deleted','Cliente Eliminado');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->person = '';
        $this->last_name = '';
        $this->ci = '';
        $this->company = '';
        $this->ruc = '';
        $this->address = '';
        $this->birthday = '';
        $this->description = '';
        $this->image = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();
    }
}
