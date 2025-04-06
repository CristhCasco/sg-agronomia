<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleDetail;


class ReportsController extends Component
{

    public $componentName, $data, $details, $sumDetails, $countDetails, 
    $reportType, $userId, $dateFrom, $dateTo, $saleId;
    public $ahorroTotal;
    public $customerId = 0;



    public function mount()
    {
        $this->componentName ='Reportes de Ventas';
        $this->data =[];
        $this->details =[];
        $this->sumDetails =0;
        $this->countDetails =0;
        $this->reportType =0;
        $this->userId =0;
        $this->saleId =0;
        $this->customerId = 0;


    }

    public function render()
    {

        $this->SalesByDate();

        return view('livewire.reports.component', [
            'users' => User::orderBy('name','asc')->get()
        ])->extends('layouts.theme.app')
        ->section('content');
    }

    public function SalesByDate()
    {
        if ($this->reportType == 0) {
            $from = Carbon::now()->format('Y-m-d') . ' 00:00:00';
            $to   = Carbon::now()->format('Y-m-d') . ' 23:59:59';
        } else {
            if ($this->dateFrom == '' || $this->dateTo == '') {
                $this->data = [];
                return;
            }
    
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to   = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }
    
        // Comienza la query base
        $query = Sale::join('users as u', 'u.id', 'sales.user_id')
            ->join('customers as c', 'c.id', 'sales.customer_id')
            ->select('sales.*', 'u.name as usuario', 'c.name as customer')
            ->whereBetween('sales.created_at', [$from, $to]);
    
        // Filtrar por usuario
        if ($this->userId > 0) {
            $query->where('sales.user_id', $this->userId);
        }
    
        // Filtrar por cliente
        if ($this->customerId > 0) {
            $query->where('sales.customer_id', $this->customerId);
        }
    
        // Ejecutar consulta
        $this->data = $query->get();
    }
    


    public function getDetails($saleId)
    {
        $this->details = SaleDetail::join('products as p','p.id','sale_details.product_id')
        ->select(
            'sale_details.id',
            'sale_details.price',
            'sale_details.quantity',
            'sale_details.manual_discount',
            'p.name as product'
        )
        ->where('sale_details.sale_id', $saleId)
        ->get();



        //
        $suma = $this->details->sum(function($item) {
            $descuento = floatval($item->manual_discount ?? 0);
            $precioFinal = max(floatval($item->price) - $descuento, 0);
            return $precioFinal * $item->quantity;
        });

        $ahorro = $this->details->sum(function($item) {
            return floatval($item->manual_discount ?? 0) * $item->quantity;
        });
        

        $this->sumDetails = $suma;
        $this->countDetails = $this->details->sum('quantity');
        $this->saleId = $saleId;
        $this->ahorroTotal = $ahorro; 

        $this->emit('show-modal','details loaded');

    }

        public function inventory()
    {
        $products = Product::all();
        return view('livewire.reports.inventory.inventory', compact('products'));
    }
 

}
