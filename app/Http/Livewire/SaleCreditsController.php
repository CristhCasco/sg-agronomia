<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\SaleCredit;
use App\Models\SaleCreditPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SaleCreditsController extends Component
{
    public $credits, $creditId, $amount;
    public $filterCustomer = '';
    public $filterStatus = '';


   // protected $listeners = ['show-payment-modal' => 'openPaymentModal'];
    


    public function mount()
    {
        $this->getCredits();
    }

    public function getCredits()
    {
        $query = SaleCredit::with('customer')->orderBy('created_at', 'desc');

        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $this->credits = $query->get();
    }


    public function openPaymentModal($creditId = null)
    {
        //Log::info('🧪 openPaymentModal llamado con ID: ' . json_encode($creditId));
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
            // **Registrar el pago en la tabla sale_credit_payments**
            SaleCreditPayment::create([
                'credit_id' => $this->creditId,
                'amount_paid' => $this->amount,
                'payment_date' => now(),
                'user_id' => Auth::user()->id
            ]);
    
            // **Actualizar `amount_paid` en `sale_credits`**
            $credit->amount_paid += $this->amount;
            $credit->remaining_balance = max(0, $credit->total_credit - $credit->amount_paid);
            $credit->status = ($credit->remaining_balance == 0) ? 'PAGADO' : 'PENDIENTE';
            $credit->save();
    
            DB::commit();
    
            // 🔄 **Forzar la actualización de datos en Livewire**
            $this->emit('credit-paid', 'Pago registrado con éxito.');
            $this->emit('hide-payment-modal');
            $this->getCredits(); // Recargar datos
            $this->reset('creditId', 'amount'); // Resetear los inputs
    
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('credit-error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.credits.sales.component', [
            'credits' => SaleCredit::with('customer')->get()
        ])->extends('layouts.theme.app')->section('content'); // ⬅️ Aquí se define el `@extends`
    }

    public function resetFilters()
    {
        $this->reset('filterCustomer', 'filterStatus');
        $this->getCredits();
    }

    
}
