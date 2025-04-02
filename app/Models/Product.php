<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // protected $fillable = ['name', 'barcode', 'brand', 'model', 'size', 'color',
    //  'description', 'cost', 'price', 'stock', 'min_stock', 'image', 'category_id'];
    
     protected $fillable = [
        'name', 'barcode', 'brand', 'model', 'size', 'color',
        'description', 'cost', 'price', 'stock', 'min_stock',
        'is_weighted', 'price_per_kg', 'image', 'category_id',
    ];

    protected $casts = [
        'is_weighted' => 'boolean',
        'price_per_kg' => 'float', // Esto garantiza que el precio sea tratado como número decimal
    ];
    
    // Relación uno a muchos inversa, es decir un producto pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //accessor
    public function getImagenAttribute()
	{	
		if($this->image != null)
			return (file_exists('storage/products/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}
