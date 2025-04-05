<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCredit extends Model
{
    protected $fillable = [
        'sale_id', 'customer_id', 'total_credit', 'amount_paid', 'remaining_balance', 'status', 'due_date'
    ];  
    
    protected $casts = [
        'due_date' => 'date',
    ];
    

    public function payments()
    {
        return $this->hasMany(SaleCreditPayment::class, 'credit_id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }


}