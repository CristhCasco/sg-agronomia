<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\Log;


class PurchasesReport extends Component
{

    public $componentName, $data, $details, $sumDetails, $countDetails,
        $reportType, $userId, $dateFrom, $dateTo, $purchaseId;

    public function mount()
    {
        $this->componentName = 'Reportes de Compras';
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->purchaseId = 0;
    }

    public function render()
    {

        $this->PurchasesByDate();

        return view('livewire.purchases-report', [
            'users' => User::orderBy('name', 'asc')->get()
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function PurchasesByDate()
    {
        if ($this->reportType == 0) // compras del dia
        {
            $from = Carbon::parse(Carbon::now())->startOfDay(); // ->format('Y-m-d')->i . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->endOfDay();     //->format('Y-m-d')   . ' 23:59:59';
        } else {
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d')     . ' 23:59:59';
        }



        if ($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo == '')) {
            $this->data = [];
            return;
        }

        if ($this->userId == 0) {
            $this->data = Purchase::join('users as u', 'u.id', 'purchases.user_id')
                ->select('purchases.*', 'u.name as usuario')
                ->whereBetween('purchases.created_at', [$from, $to])
                ->get();
        } else {
            $this->data = Purchase::join('users as u', 'u.id', 'purchases.user_id')
                ->select('purchases.id', 'purchases.total', 'purchases.items', 'purchases.status', 'u.name as usuario', 'purchases.created_at')
                ->whereBetween('purchases.created_at', [$from, $to])
                ->where('user_id', $this->userId)
                ->get();
        }
    }


    public function getDetails($purchaseId)
    {
        $this->details = PurchaseDetail::join('products as p', 'p.id', 'purchase_details.product_id')
            ->select('purchase_details.id', 'purchase_details.price', 'purchase_details.quantity', 'p.name as product')
            ->where('purchase_details.purchase_id', $purchaseId)
            ->get();


        //
        $suma = $this->details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->sumDetails = $suma;
        $this->countDetails = $this->details->sum('quantity');
        $this->purchaseId = $purchaseId;

        $this->emit('show-modal', 'details loaded');
    }
}
