<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [ 'person', 'name', 'last_name', 'ci', 'company', 'ruc', 'address', 'phone', 'birthday', 'email', 'image'];


    //accessor
    public function getImagenAttribute()
    {	
        if($this->image != null)
            return (file_exists('storage/customers/' . $this->image) ? $this->image : 'noimg.jpg');
        else
            return 'noimg.jpg';		
        
    }
}
