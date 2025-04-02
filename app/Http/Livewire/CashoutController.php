<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Models\ExtraExpense;

class CashoutController extends Component
{
    public $fromDate, $toDate, $userId, $totalSales, $itemsSales, $sales, $detailsSales;
    public $totalPurchases, $itemsPurchases, $purchases, $detailsPurchases;
    public $totalExtraExpenses = 0, $extraExpenses = [];
    public $expenseAmount, $expenseDescription;

    public function mount()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->userId = 0;
        
        // Ventas
        $this->totalSales = 0;
        $this->itemsSales = 0;
        $this->sales = [];
        $this->detailsSales = [];

        // Compras
        $this->totalPurchases = 0;
        $this->itemsPurchases = 0;
        $this->purchases = [];
        $this->detailsPurchases = [];

        //Gastos Extras
        $this->totalExtraExpenses = 0;
        $this->extraExpenses = [];
        $this->expenseAmount = 0;
        $this->expenseDescription = '';
    }

    public function render()
    {
        return view('livewire.cashout.component', [
            'users' => User::orderBy('name', 'asc')->get(),
        ])->extends('layouts.theme.app')
          ->section('content');
    }

    public function Consult()
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        // 🔹 Obtener Ventas
        $this->sales = Sale::whereBetween('created_at', [$fi, $ff])
            ->where('user_id', $this->userId)
            ->get();
        $this->totalSales = $this->sales ? $this->sales->sum('total') : 0;
        $this->itemsSales = $this->sales ? $this->sales->sum('items') : 0;

        // 🔹 Obtener Compras
        $this->purchases = Purchase::whereBetween('created_at', [$fi, $ff])
            ->where('user_id', $this->userId)
            ->get();
        $this->totalPurchases = $this->purchases ? $this->purchases->sum('total') : 0;
        $this->itemsPurchases = $this->purchases ? $this->purchases->sum('items') : 0;

        // 🔹 Obtener Gastos Extras
        $this->extraExpenses = ExtraExpense::whereBetween('date', [$fi, $ff])->where('user_id', $this->userId)->get();
        $this->totalExtraExpenses = $this->extraExpenses ? $this->extraExpenses->sum('amount') : 0;
    }


    public function AddExpense()
    {
        $this->validate([
            'expenseAmount' => 'required|numeric|min:0.01',
            'expenseDescription' => 'required|string|max:255',
        ]);

        ExtraExpense::create([
            'user_id' => Auth::id(),
            'amount' => $this->expenseAmount,
            'description' => $this->expenseDescription,
            'date' => now()->format('Y-m-d'),
        ]);

        $this->expenseAmount = 0;
        $this->expenseDescription = '';

        $this->Consult(); // Actualizar datos después de agregar un gasto

        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewDetails(Sale $sale)
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->detailsSales = Sale::join('sale_details as d', 'd.sale_id', 'sales.id')
            ->join('products as p', 'p.id', 'd.product_id')
            ->join('customers as c', 'c.id', 'sales.customer_id') // Unir con la tabla customers
            ->select('d.sale_id', 'p.name as product', 'd.quantity', 'd.price', 'c.name as customer') // Obtener el nombre del cliente
            ->whereBetween('sales.created_at', [$fi, $ff])
            ->where('sales.status', 'PAGADO')
            ->where('sales.user_id', $this->userId)
            ->where('sales.id', $sale->id)
            ->get();

        $this->emit('show-modal', 'modal-details-sales');
    }

    public function viewPurchaseDetails(Purchase $purchase)
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->detailsPurchases = Purchase::join('purchase_details as d', 'd.purchase_id', 'purchases.id')
            ->join('products as p', 'p.id', 'd.product_id')
            ->join('suppliers as s', 's.id', 'purchases.supplier_id') // Unir con la tabla suppliers
            ->select('d.purchase_id', 'p.name as product', 'd.quantity', 'd.price', 's.name as supplier') // Obtener el nombre del proveedor
            ->whereBetween('purchases.created_at', [$fi, $ff])
            ->where('purchases.status', 'PAGADO')
            ->where('purchases.user_id', $this->userId)
            ->where('purchases.id', $purchase->id)
            ->get();

        $this->emit('show-modal', 'modal-details-purchases');
    }
}
