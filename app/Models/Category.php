<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected  $fillable = ['name', 'image'];

    // Relación uno a muchos, es decir una categoría puede tener muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImagenAttribute()
	{
	

		if($this->image != null)
			return (file_exists('storage/categories/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		

		//método 2
		/*
		if($this->image == null)
		{		
			if(file_exists('storage/categories/' . $this->image))
				return $this->image;
			else
				return 'noimg.jpg';
		} else {
			return 'noimg.jpg';
		}
		*/
		
	}

}
