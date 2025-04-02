<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['name','last_name','ci','user','password','email','phone','address','profile','status','image'];
    protected $user = 'user';

    //ACCESORES

    public function getImagenAttribute()
    {	
        if($this->image != null)
            return (file_exists('storage/users/' . $this->image) ? $this->image : 'noimg.jpg');
        else
            return 'noimg.jpg';		
        
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
