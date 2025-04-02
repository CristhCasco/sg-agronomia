<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
//use App\Traits\CartTrait;
use Livewire\Component;

class ModalSearchSuppliers extends Component
{
    //use CartTrait;

    public $search, $suppliers = [];

    protected $paginationTheme = 'bootstrap';


    public $supplierName, $supplierId;

    //Para que se ejecute en tiempo real el metodo buscar
    public function liveSearch()
    {

        if (strlen($this->search) > 0) {
            $this->suppliers = Supplier::where('id', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('ci', 'like', "%{$this->search}%")
                ->orWhere('company', 'like', "%{$this->search}%")
                ->orWhere('ruc', 'like', "%{$this->search}%")
                ->orderBy('name', 'asc')
                ->take(10)
                ->get();


            //METODO COPIADO DE MODALSEARCHCUSTOMERS
            /*$this->suppliers = Supplier::Join('purchases as p', 'p.id', 'suppliers.id')
                ->select('suppliers.*')
                ->where('suppliers.id', 'like', "%{$this->search}%")
                ->orwhere('suppliers.name', 'like', "%{$this->search}%")
                ->orWhere('suppliers.last_name', 'like', "%{$this->search}%")
                ->orWhere('suppliers.ci', 'like', "%{$this->search}%")
                ->orWhere('suppliers.company', 'like', "%{$this->search}%")
                ->orWhere('suppliers.ruc', 'like', "%{$this->search}%")
                ->orderBy('suppliers.name', 'asc')
                ->get()->take(10);*/
        } else {
            return $this->suppliers = [];
        }
    }

    //Para reenderizar la vista
    public function render()
    {
        $this->liveSearch();

        return view('livewire.modalsearch.suppliers.component');
    }

    public function setSupplier($supplierId, $supplierName)
    {
        $this->supplierName = $supplierName;
        $this->supplierId = $supplierId;
        $this->emit('setSupplier', $supplierId, $supplierName);
        $this->dispatchBrowserEvent('closeModal');
    }
}
