<?php

namespace App\Http\Livewire;

use App\Models\Customer;
//use App\Traits\CartTrait;
use Livewire\Component;

class ModalSearchCustomers extends Component
{
    //use CartTrait;

    public $search, $customers = [];

    protected $paginationTheme = 'bootstrap';
    public  $customerName, $customerId;



    //Para que se ejecute en tiempo real el metodo buscar
    public function liveSearch()
    {

        if (strlen($this->search) > 0) {
            $this->customers = Customer::select('customers.*')
                ->where('customers.id', 'like', "%{$this->search}%")
                ->orwhere('customers.name', 'like', "%{$this->search}%")
                ->orWhere('customers.last_name', 'like', "%{$this->search}%")
                ->orWhere('customers.ci', 'like', "%{$this->search}%")
                ->orWhere('customers.company', 'like', "%{$this->search}%")
                ->orWhere('customers.ruc', 'like', "%{$this->search}%")
                ->orderBy('customers.name', 'asc')
                ->get()->take(10);
        } else {
            return $this->customers = [];
        }
    }

    //Para reenderizar la vista
    public function render()
    {
        $this->liveSearch();

        return view('livewire.modalsearch.customers.component');
    }

    public function setCustomer($customerId, $customerName)
    {
        $this->customerName = $customerName;
        $this->customerId = $customerId;
        $this->emit('setCustomer', $customerId, $customerName);
        $this->dispatchBrowserEvent('closeModal');
    }
}
