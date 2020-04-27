<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Worker;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $worker = new Worker();
        $worker->first_name = $request->first_name;
        $worker->last_name = $request->last_name;
        $user->worker()->save($worker);
        return response()->json(["User Registered!", 200]);
    }

    public function login(Request $request)
    {
        $userCredentials = $request->only('email', 'password');

        if (Auth::attempt($userCredentials)) {
            return response()->json(["Authenticated", 200]);
        } else {
            return response()->json(["Invalid Credentials", 411]);
        }
    }
}
