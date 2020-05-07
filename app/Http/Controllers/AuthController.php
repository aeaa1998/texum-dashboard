<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	public function register(Request $request) {
		$request->validate([
			'email'      => 'required|email|unique:App\Models\User,email',
			'password'   => 'required',
			'first_name' => 'required',
			'last_name'  => 'required',
		]);
		$user           = new User();
		$user->email    = $request->email;
		$user->password = Hash::make($request->password);
		$user->save();
		$worker             = new Worker();
		$worker->first_name = $request->first_name;
		$worker->last_name  = $request->last_name;
		$worker->user_id    = $user->id;
		// $user->worker()->save($worker);
		$worker->save();

		return response()->json(["message", "successfully created"]);
	}

	public function login(Request $request) {
		$userCredentials = $request->only('email', 'password');

		if (Auth::attempt($userCredentials)) {
			return response()->json(["Authenticated"], 200);
		} else {
			return response()->json(["Invalid Credentials"], 411);
		}
	}
}
