<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseCreditPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_id',
        'amount_paid',
        'payment_date',
        'user_id'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
    ];
    

    public function credit()
    {
        return $this->belongsTo(PurchaseCredit::class, 'credit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
