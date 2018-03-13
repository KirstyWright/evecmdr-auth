<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function discord()
    {
        return $this->hasOne('\App\DiscordUser');
    }

    public function roles()
    {
        return $this->belongsToMany('Role');
    }

    public function corporation()
    {
        return $this->belongsTo('\App\Corporation');
    }

    public function groups()
    {
        return $this->belongsToMany('\App\Group');
    }

}
