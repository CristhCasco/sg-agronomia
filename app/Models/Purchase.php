<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'items',
        'sub_total',
        'total',
        'cash',
        'change',
        'status',
        'payment_type',
        'payment_method',
        'discount',
        'discount_total',
        'supplier_id',
        'user_id',
        'due_date', // <- Asegúrate de incluirlo si usás vencimiento
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function credit()
    {
        return $this->hasOne(PurchaseCredit::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(
            PurchaseCreditPayment::class,
            PurchaseCredit::class,
            'purchase_id', // FK en PurchaseCredit
            'credit_id',   // FK en PurchaseCreditPayment
            'id',          // Local key en Purchase
            'id'           // Local key en PurchaseCredit
        );
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->credit ? $this->credit->remaining_balance : 0;
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }
}
