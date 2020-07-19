<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Worker;

class User extends Authenticatable
{

	use HasApiTokens, Notifiable;

	public function worker()
	{
		return  $this->hasOne('App\Models\Worker');
	}

	public function roles()
	{
		return  $this->belongsToMany('App\Models\Role', 'user_roles');
	}
}
