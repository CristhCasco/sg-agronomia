<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;



class SuppliersController extends Component
{
    use WithPagination;
    use WithFileUploads;


    public $search, $pageTitle, $componentName, $selected_id, $image, $person, $name, $email, $phone, $last_name, $ci, $company, $ruc, $address, $birthday, $description;
    private $pagination = 5;

    public function mount ()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Proveedores';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        if(strlen($this->search) > 0 )
        {
            $suppliers = Supplier::where('name','like','%'.$this->search.'%')
            ->orWhere('last_name','like','%'.$this->search.'%')
            ->orWhere('ci','like','%'.$this->search.'%')
            ->orWhere('address','like','%'.$this->search.'%')
            ->orWhere('email','like','%'.$this->search.'%')
            ->orWhere('company','like','%'.$this->search.'%')
            ->orWhere('ruc','like','%'.$this->search.'%')
            ->orderBy('id', 'desc')->paginate($this->pagination);
        }
        else

        $suppliers = Supplier::orderBy('id', 'desc')->paginate($this->pagination);
        return view('livewire.suppliers.component', [
            'data' => $suppliers,
            //'supplier' => supplier::orderBy('name','ASC')->get()
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

        $supplier = Supplier::create([
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
			$this->image->storeAs('public/suppliers', $customFileName);
			$supplier->image = $customFileName;
			$supplier->save();
		}

        $this->resetUI();
        $this->emit('supplier-added','Cliente Registrado');
    }

   

    public function Edit(Supplier $supplier)
    {
        
        $this->selected_id = $supplier->id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->person = $supplier->person;
        $this->last_name = $supplier->last_name;
        $this->ci = $supplier->ci;
        $this->company = $supplier->company;
        $this->ruc = $supplier->ruc;
        $this->address = $supplier->address;
        $this->birthday = $supplier->birthday;
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

        $supplier = Supplier::find($this->selected_id);

        $supplier->update([
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
			$this->image->storeAs('public/suppliers', $customFileName);
            $imageTemp = $supplier->image; //imagen temporal
			$supplier->image = $customFileName;
			$supplier->save();

            if($imageTemp != null)
            {
                if(file_exists('storage/suppliers/'.$imageTemp))
                {
                    unlink('storage/suppliers/'.$imageTemp);
                }
            }

        }

        $this->resetUI();
        $this->emit('supplier-updated','Cliente Actualizado');
    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy($id)
    {
        $supplier = supplier::find($id);
        $imageTemp = $supplier->image; //imagen temporal
        $supplier->delete();

        if($imageTemp != null)
        {
            if(file_exists('storage/suppliers/'.$imageTemp))
            {
                unlink('storage/suppliers/'.$imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('supplier-deleted','Cliente Eliminado');
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
