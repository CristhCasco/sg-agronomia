<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDifference extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'description',
        'quantity_excel',
        'quantity_counted',
        'difference',
        'user_id',
        'created_at',
        'updated_at',
    ];
}