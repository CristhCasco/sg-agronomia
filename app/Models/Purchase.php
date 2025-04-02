<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [ 'items', 'sub_total', 'total', 'cash', 'change', 'status', 'payment_type', 'payment_method', 'discount', 'discount_total', 'supplier_id', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function supplier()  {
        return $this->belongsTo(Supplier::class);       
    }
    
}
