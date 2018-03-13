<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordUser extends Model
{
    public $incrementing = false;
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
