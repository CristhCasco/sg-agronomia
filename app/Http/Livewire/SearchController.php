<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\CartTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SearchController extends Component
{
    use CartTrait;

    public $search;
    public $currentPath;

    public function mount()
    {
        $this->currentPath = url()->current();
    }

    protected $listeners = ['scan-code' => 'ScanCode'];


    public function ScanCode($barcode)
    {
        //Log::info('SearchController -> ScanCode');

        $routeName = Str::afterLast($this->currentPath, '/');

        if ($routeName = 'purchases') {
            $this->emit('addProductFromPurchase', $barcode);
        } else {


            if ($routeName != 'pos' && $routeName != 'purchases') {

                $this->ScanearCode($barcode);

                redirect()->to('/pos');
            }
        }
    }


    public function render()
    {
        return view('livewire.search');
    }
}
