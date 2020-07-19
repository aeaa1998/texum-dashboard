<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Records extends Model
{
    function user()
    {
        $this->belongsTo('App\Models\User');
    }
}
