<?php

namespace App\Http\Controllers;

use App\Models\SaleCreditPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfCreditController extends Controller
{
    public function imprimirComprobante($hash)
{
    $payment_id = base64_decode($hash);

    $pago = SaleCreditPayment::with(['credit.sale.customer', 'user'])->findOrFail($payment_id);
    $sale = $pago->credit->sale;

    // Traer todos los pagos sin excluir nada
    $pagos = SaleCreditPayment::where('credit_id', $pago->credit_id)
        ->orderBy('created_at')
        ->get()
        ->map(function ($p) {
            return [
                'fecha' => \Carbon\Carbon::parse($p->created_at)->format('d/m/Y'),
                'monto' => (int) round($p->amount_paid),
            ];
        });

    $totalPagado = $pagos->sum('monto');
    $saldo = $sale->total - $totalPagado;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.tickets.payment_credit', [
        'cliente' => $sale->customer->name,
        'fecha' => now()->format('d/m/Y H:i'),
        'venta_id' => $sale->id,
        'total_original' => $sale->total,
        'total_pagado' => $totalPagado,
        'saldo' => $saldo,
        'pagos' => $pagos,
        'usuario' => $pago->user->name,
        'estado_credito' => $pago->credit->status,
    ])->setPaper([0, 0, 226.77, 600], 'portrait');

    return $pdf->stream('comprobante_pago_credito.pdf');
}

}
