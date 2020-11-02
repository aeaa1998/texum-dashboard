<?php

namespace App\Models;

use App\Models\Worker;
use App\Models\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

	use HasApiTokens, Notifiable;
	protected $hidden = ['password'];
	public function worker()
	{
		return $this->hasOne('App\Models\Worker');
	}

	public function role()
	{
		return $this->belongsTo('App\Models\Role');
	}

	public function userrole()
	{
		return $this->hasMany('App\Models\UserRole');
	}
}
