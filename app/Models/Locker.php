<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    function oldPackageRecords()
    {
        return $this->hasMany('App\Models\Record', 'old_locker');
    }
    function newPackageRecords()
    {
        return $this->hasMany('App\Models\Record', 'new_locker');
    }
}
