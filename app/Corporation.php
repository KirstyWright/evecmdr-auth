<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id'
    ];

    public function alliance()
    {
        return $this->belongsTo('\App\Alliance');
    }
}
