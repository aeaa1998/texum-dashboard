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
		$this->hasOne('App\Models\Worker');
	}
}
