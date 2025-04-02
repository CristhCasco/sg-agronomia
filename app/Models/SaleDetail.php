<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    //protected $fillable = [ 'price', 'quantity','sub_total', 'product_id', 'sale_id' ];
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'weight', 'price', 'sub_total'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


    //1 DETALLE DE LA VENTA PERTENECE A 1 PRODUCTO PARA PODER TRAER EL NOMBRE DEL PRODUCTO YA QUE EN EL DETALLE SOLO TENEMOS EL ID
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
