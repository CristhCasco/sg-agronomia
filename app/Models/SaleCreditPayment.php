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
}
