<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode', 'description', 'quantity_excel', 'quantity_counted', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}