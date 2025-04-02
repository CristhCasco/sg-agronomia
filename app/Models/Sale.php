<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'items',
        'total',
        'cash',
        'change',
        'status',
        'payment_type',
        'payment_method',
        'discount',
        'discount_total',
        'customer_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // public function toArray()
    // {
    //     $attributes = parent::toArray();
    //     $attributes['created_at'] = $this->created_at->timezone('America/Asuncion')->toDateTimeString();
    //     return $attributes;
    // }

    public function toArray()
{
    $attributes = parent::toArray();

    // Verifica si created_at no es null antes de aplicar timezone()
    if ($this->created_at) {
        $attributes['created_at'] = $this->created_at->timezone('America/Asuncion')->toDateTimeString();
    } else {
        $attributes['created_at'] = null; // O cualquier otro valor por defecto que desees
    }

    return $attributes;
}

    // relationship
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    function customer()  {
        //PARA OPTIMIZAR LA CONSULTA
        //SE DEBE ESPECIFICAR EN ESTA RELACION QUE SOLO QUIERO EL NOMBRE EL ID SI O SI DEBE IR
        return $this->belongsTo(Customer::class)->select('id','name');       
    }

    public function user()
    {
        //ESPECIFICAR EN ESTA RELACION QUE DE ESTA RELACION SOLO QUIERO EL NOMBRE  EL ID SI O SI DEBE IR
        return $this->belongsTo(User::class)->select('id','name');
    }

    public function credit()
    {
        return $this->hasOne(SaleCredit::class);
    }

    //MAS ADELANTE PARA METODOS DE PAGOS
    // public function payments()
    // {
    //     return $this->hasMany(Paymen::class);
    // }

   


}
