<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupRule extends Model
{
    public function entity()
    {
        if (strtolower($this->entity_type) == 'corporation') {
            return $this->belongsTo('App\Corporation','entity_id');
        } elseif (strtolower($this->entity_type) == 'alliance') {
            return $this->belongsTo('App\Alliance','entity_id');
        } elseif (strtolower($this->entity_type) == 'faction') {
            return $this->belongsTo('App\Faction','entity_id');
        }
    }
}
