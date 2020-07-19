<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    function packages()
    {
        return $this->hasMany('App\Models\Package');
    }
    function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
}
