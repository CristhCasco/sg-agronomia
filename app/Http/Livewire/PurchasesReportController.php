<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\Log;


class PurchasesReportController extends Component
{

    public $componentName, $data, $details, $sumDetails, $countDetails,
        $reportType, $userId, $dateFrom, $dateTo, $purchaseId;
    public $statusFilter = 'ALL';
    public $supplierId = 0;
    public $deletePurchaseId = null;

    protected $listeners = ['deletePurchaseConfirmed' => 'deletePurchase'];



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
        $this->supplierId = 0;

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
            $to   = Carbon::now()->endOfDay();
        } else {
            if (!$this->dateFrom || !$this->dateTo) {
                $this->data = [];
                return;
            }
        
            $from = Carbon::parse($this->dateFrom)->startOfDay();
            $to   = Carbon::parse($this->dateTo)->endOfDay();
        }

        $query = Purchase::join('users as u', 'u.id', 'purchases.user_id')
            ->join('suppliers as s', 's.id', 'purchases.supplier_id')
            ->select('purchases.*', 'u.name as user', 's.name as supplier')
            ->whereBetween('purchases.created_at', [$from, $to]);

        // if ($this->userId != 0) {
        //     $query->where('purchases.user_id', $this->userId);
        // }
        if ($this->supplierId != 0) {
            $query->where('purchases.supplier_id', $this->supplierId);
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

    public function confirmDelete($id)
    {
        $this->deletePurchaseId = $id;
        $this->dispatchBrowserEvent('confirm-delete-purchase');
    }

    public function deletePurchase()
    {
        if ($this->deletePurchaseId) {
            $purchase = Purchase::find($this->deletePurchaseId);
    
            if (!$purchase) return;
    
            // ⚠️ Si la compra es a crédito y está pendiente, no permitir eliminar
            if ($purchase->status === 'PENDIENTE' && $purchase->payment_type === 'CREDITO') {
                $this->dispatchBrowserEvent('purchase-cannot-delete');
                return;
            }
    
            // Eliminar detalles primero si no hay ON DELETE CASCADE
            PurchaseDetail::where('purchase_id', $this->deletePurchaseId)->delete();
            $purchase->delete();
    
            $this->deletePurchaseId = null;
            $this->PurchasesByDate();
    
            $this->dispatchBrowserEvent('purchase-deleted');
        }
    }
    

}
