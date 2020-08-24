<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageRequest extends Model
{
    protected $table = 'requests';
    function package()
    {
        return $this->belongsTo('App\Models\Package');
    }

    function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    function oldLocker()
    {
        return $this->belongsTo('App\Models\Locker', 'old_locker');
    }

    function newLocker()
    {
        return $this->belongsTo('App\Models\Locker', 'new_locker');
    }

    function status()
    {
        return $this->belongsTo('App\Models\RequestStatus', 'status_id');
    }
}
