<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    function users()
    {
        return $this->hasMany('App\Models\User');
    }

    function menus(){
        return $this->belongsToMany(AppMenu::class, 'role_app_menus');
    }
}
