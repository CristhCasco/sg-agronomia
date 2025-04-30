<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCreditPayment extends Model
{
    protected $fillable = [
        'credit_id',
        'amount_paid',  // DEBE estar aquí
        'payment_date',
        'user_id'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];
    

    public function saleCredit()
    {
        return $this->belongsTo(SaleCredit::class, 'credit_id'); // 👈 esta es la columna real
    }

    public function credit()
    {
        return $this->belongsTo(\App\Models\SaleCredit::class, 'credit_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }



}
