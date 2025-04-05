<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\SaleCredit;
use App\Models\SaleCreditPayment;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleCreditsController extends Component
{
    use WithPagination;

    public $creditId, $amount;
    public $filterCustomer = '';
    public $filterStatus = '';
    public $search = '';
    public $startDate;
    public $endDate;
    public $aboutToExpireCount = 0;
    public $showOnlyExpiring = false;
    public $selectedCredit, $creditDetails = [], $paymentHistory = [];



    protected $paginationTheme = 'bootstrap';

    public function updatingFilterStatus()     { $this->resetPage(); }
    public function updatingFilterCustomer()   { $this->resetPage(); }
    public function updatingStartDate()        { $this->resetPage(); }
    public function updatingEndDate()          { $this->resetPage(); }
    public function updatingSearch()           { $this->resetPage(); }

    public function openPaymentModal($creditId = null)
    {
        if (!$creditId) {
            $this->emit('credit-error', 'Error: ID de crédito no válido.');
            return;
        }

        $this->creditId = $creditId;
        $this->amount = null;
        $this->emit('show-payment-modal');
    }

    public function payCredit()
    {
        $this->validate([
            'creditId' => 'required|exists:sale_credits,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $credit = SaleCredit::find($this->creditId);

        if (!$credit || $credit->remaining_balance <= 0) {
            $this->emit('credit-error', 'El crédito ya ha sido pagado o no existe.');
            return;
        }

        if ($this->amount > $credit->remaining_balance) {
            $this->emit('credit-error', 'El monto es mayor al saldo pendiente.');
            return;
        }

        DB::beginTransaction();

        try {
            SaleCreditPayment::create([
                'credit_id' => $this->creditId,
                'amount_paid' => $this->amount,
                'payment_date' => now(),
                'user_id' => Auth::id(),
            ]);

            $credit->amount_paid += $this->amount;
            $credit->remaining_balance = max(0, $credit->total_credit - $credit->amount_paid);
            $credit->status = $credit->remaining_balance == 0 ? 'PAGADO' : 'PENDIENTE';
            $credit->save();

            DB::commit();

            $this->emit('credit-paid', 'Pago registrado con éxito.');
            $this->emit('hide-payment-modal');
            $this->reset('creditId', 'amount');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('credit-error', $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset('filterCustomer', 'filterStatus', 'startDate', 'endDate', 'search');
        $this->resetPage();
    }

    public function render()
    {
        $query = SaleCredit::with('customer')->orderBy('created_at', 'desc');

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        if ($this->search) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('ci', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        $this->aboutToExpireCount = (clone $query)
        ->where('status', 'PENDIENTE')
        ->whereDate('due_date', '<=', now()->addDays(3))
        ->count();

        if ($this->showOnlyExpiring) {
            $query->where('status', 'PENDIENTE')
                  ->whereDate('due_date', '<=', now()->addDays(3));
        }

        $credits = $query->paginate(10);

        return view('livewire.credits.sales.component', [
            'credits' => $credits
        ])->extends('layouts.theme.app')->section('content');
    }

    public function updatingShowOnlyExpiring() {
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->selectedCredit = SaleCredit::with(['sale.details.product', 'payments'])
                                    ->find($id);

        $this->creditDetails = $this->selectedCredit->sale->details ?? [];
        $this->paymentHistory = $this->selectedCredit->payments ?? [];

        $this->emit('show-credit-details');
    }

    public function getAvailableCredit($customerId)
    {
        $customer = \App\Models\Customer::find($customerId);
        if (!$customer) return 0;

        $used = \App\Models\SaleCredit::where('customer_id', $customerId)
            ->where('status', 'PENDIENTE')
            ->sum('remaining_balance');

        return max(0, $customer->credit_limit - $used); // Nunca negativo
    }

        
}
