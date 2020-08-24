<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    function records()
    {
        return $this->hasMany('App\Models\Record');
    }

    function requests()
    {
        return $this->hasMany('App\Models\PackageRequest');
    }
    function lastRecord()
    {
        return $this->hasOne('App\Models\Record')->orderByDesc('id');
    }
    function lot()
    {
        return $this->belongsTo('App\Models\Lot');
    }
    function status()
    {
        return $this->belongsTo('App\Models\PackageStatus');
    }
}
