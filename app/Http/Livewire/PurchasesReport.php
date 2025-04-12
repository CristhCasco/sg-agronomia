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
    public $statusFilter = 'ALL';


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

        return view('livewire.reports.purchases.purchases-report', [
            'users' => User::orderBy('name', 'asc')->get()
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function PurchasesByDate()
    {
        if ($this->reportType == 0) {
            $from = Carbon::now()->startOfDay();
            $to = Carbon::now()->endOfDay();
        } else {
            if ($this->dateFrom == '' || $this->dateTo == '') {
                $this->data = [];
                return;
            }
            $from = Carbon::parse($this->dateFrom)->startOfDay();
            $to = Carbon::parse($this->dateTo)->endOfDay();
        }

        $query = Purchase::join('users as u', 'u.id', 'purchases.user_id')
            ->select('purchases.*', 'u.name as user')
            ->whereBetween('purchases.created_at', [$from, $to]);

        if ($this->userId != 0) {
            $query->where('purchases.user_id', $this->userId);
        }

        if ($this->statusFilter !== 'ALL') {
            $query->where('purchases.status', $this->statusFilter);
        }

        $this->data = $query->get();
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
