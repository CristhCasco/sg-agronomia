<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseCredit;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchasesCreditsController extends Component
{
    use WithPagination;

    public $selectedPurchaseId = null;
    public $selectedPurchase = null;
    public $monto = 0;
    public $metodo = 'EFECTIVO';
    public $selectedCredit = null;
    public $creditDetails = [];
    public $paymentHistory = [];

    public $search = '';
    public $filterStatus = '';
    public $startDate;
    public $endDate;
    public $showOnlyExpiring = false;
    public $aboutToExpireCount = 0;

    public function mount()
    {
        // $this->startDate = now()->startOfMonth()->format('Y-m-d');
        // $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->startDate = null;
        $this->endDate = null;
    }

    public function render()
    {
        $query = PurchaseCredit::with(['supplier', 'purchase'])
            ->when($this->search, fn($q) => $q->whereHas('supplier', fn($s) =>
                $s->where('name', 'like', "%{$this->search}%")
            ))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->startDate && $this->endDate, fn($q) =>
                $q->whereBetween('created_at', ["{$this->startDate} 00:00:00", "{$this->endDate} 23:59:59"])
            )
            ->when($this->showOnlyExpiring, function ($q) {
                $today = Carbon::today();
                $nextDays = $today->copy()->addDays(3);
                $q->where('status', 'PENDIENTE')
                ->whereHas('purchase', fn($q2) =>
                    $q2->whereBetween('due_date', [$today, $nextDays])
                );
            });

            $this->aboutToExpireCount = PurchaseCredit::where('status', 'PENDIENTE')
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->count();
        

        $credits = $query->latest()->paginate(10);

        return view('livewire.credits.purchases.component', [
            'credits' => $credits
        ])->extends('layouts.theme.app')->section('content');
    }


    public function seleccionar($id)
    {
        $this->selectedPurchaseId = $id;
        $this->selectedPurchase = Purchase::with('payments')->find($id);
        $this->monto = $this->selectedPurchase->saldo_pendiente;
        $this->metodo = 'EFECTIVO';
        $this->dispatchBrowserEvent('show-payment-modal'); // ← ESTE EVENTO
    }
    
    public function verDetalleCredito($purchaseId)
    {
        $this->selectedCredit = \App\Models\PurchaseCredit::with('purchase.details')->where('purchase_id', $purchaseId)->first();
    
        if ($this->selectedCredit) {
            $this->creditDetails = $this->selectedCredit->purchase->details()->with('product')->get() ?? [];
            $this->paymentHistory = $this->selectedCredit->payments()->latest()->get();
            $this->dispatchBrowserEvent('show-credit-details'); // ← ESTE EVENTO
        } else {
            $this->dispatchBrowserEvent('credit-error', 'No se encontró el crédito para esta compra');
        }
    }
    

    public function guardarPago()
    {
        if (!$this->selectedPurchase || !$this->selectedPurchase->credit) {
            $this->dispatchBrowserEvent('purchase-error', ['msg' => 'Crédito no encontrado.']);
            return;
        }

        $credito = $this->selectedPurchase->credit;
        $pendiente = $credito->remaining_balance;

        if (!is_numeric($this->monto) || $this->monto <= 0 || $this->monto > $pendiente) {
            $this->dispatchBrowserEvent('purchase-error', ['msg' => 'Monto inválido']);
            return;
        }

        $credito->payments()->create([
            'amount_paid' => $this->monto,
            'payment_date' => now(),
            'user_id' => auth()->id(),
        ]);

        $credito->amount_paid += $this->monto;
        $credito->remaining_balance = $credito->total_credit - $credito->amount_paid;

        if ($credito->remaining_balance <= 0.01) {
            $credito->status = 'PAGADO';
            $credito->purchase->status = 'PAGADO';
            $credito->purchase->save();
        }

        $credito->save();

        $this->reset([
            'selectedPurchaseId',
            'selectedPurchase',
            'monto',
            'metodo',
            'selectedCredit',
            'creditDetails',
            'paymentHistory'
        ]);

        $this->dispatchBrowserEvent('purchase-ok', ['msg' => 'Pago registrado correctamente']);
        $this->emit('$refresh'); // 🔥 Forzar recarga de la vista
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->showOnlyExpiring = false;

        // Reiniciar a las fechas por defecto
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

}