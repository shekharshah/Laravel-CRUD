<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function right(){
        
        return $this->belongsTo('\App\Rights','right_id','id'); //defining a relationship  using belongs to and using foreign key
    }
    public function car(){
        
        return $this->hasMany('\App\Car','user_id','id'); //defining a relationship  using hasMany and using foreign key
    }
    public function hobby(){
        
        return $this->hasMany('\App\Hobby','user_id','id'); //defining a relationship  using hasMany and using foreign key
    }
    public function image(){
        
        return $this->hasMany('\App\Images','user_id','id'); //defining a relationship  using hasMany and using foreign key
    }
}
