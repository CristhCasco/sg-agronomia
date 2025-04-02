<?php

namespace App\Http\Livewire;

use App\Models\Product;
//use App\Traits\CartTrait;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ModalSearch extends Component
{
    //use CartTrait;

    public $search, $products = [];

    protected $paginationTheme = 'bootstrap';

    public $componentToEmit = 'sales';


    function mount($componentToEmit = 1)
    {
        $this->componentToEmit = $componentToEmit;
    }




    public function liveSearch()
    {

        //TENER EN CUENTA QUE ESTE METODO SE EJECUTA CADA VEZ QUE SE ESCRIBE EN EL INPUT DE BUSQUEDA
        if (strlen($this->search) > 1) {
            $this->products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->select('products.*', 'c.name as category')
                ->where(function($query) {
                    $query->where('products.name', 'like', "%{$this->search}%")
                        ->orWhere('products.barcode', 'like', "%{$this->search}%")
                        ->orWhere('products.model', 'like', "%{$this->search}%")
                        ->orWhere('products.description', 'like', "%{$this->search}%")
                        ->orWhere('c.name', 'like', "%{$this->search}%")
                        ->orWhere(DB::raw("CONCAT(products.name, ' ', products.brand, ' ', products.model, ' ', products.description)"), 'like', "%{$this->search}%");
                })
                ->orderBy('products.name', 'asc')
                ->take(10)
                ->get();
        } else {
            $this->products = [];
        }
    }

    public function addProduct($productId)
    {
        // Emitir el evento
        if ($this->componentToEmit == 1)
            $this->emit('scan-code-byid', $productId);
        else
            $this->emit('addProduct', $productId);
    }


    public function render()
    {
        $this->liveSearch();

        return view('livewire.modalsearch.products.component');
    }


    public function addAll()
    {
        if (count($this->products) > 0) {
            foreach ($this->products  as $product) {
                $this->emit('scan-code-byid', $product->id);
            }
        }
    }


    function setCustomer($customerId, $customerName)
    {
        //$this->customer_name = $customerName;
        // $this->customer_selected_id = $customerId;
        $this->emit('setCustomer', $customerId, $customerName);
    }
}
