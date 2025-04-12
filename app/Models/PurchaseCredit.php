<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'total_credit',
        'amount_paid',
        'remaining_balance',
        'status'
    ];

    protected $casts = [
        'total_credit' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];
    

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchaseCreditPayment::class, 'credit_id');
    }

    // Accesorios opcionales si querés facilitar uso

    public function getEstadoAttribute()
    {
        return $this->status === 'PAGADO' ? '✔ PAGADO' : '⏳ PENDIENTE';
    }
}
