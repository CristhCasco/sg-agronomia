<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCredit extends Model
{
    protected $fillable = [
        'sale_id', 'customer_id', 'total_credit', 'amount_paid', 'remaining_balance', 'status'
    ];    

    public function payments()
    {
        return $this->hasMany(SaleCreditPayment::class);
    }

    public function customer()
{
    return $this->belongsTo(Customer::class);
}

}