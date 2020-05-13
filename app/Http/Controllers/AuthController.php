<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
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

    public function login(Request $request)
    {
        $userCredentials = $request->only('email', 'password');

        if (Auth::attempt($userCredentials)) {
            $request->session()->put('user_id', User::where('email', $request->email)->first()->id);
            return response()->json(["Authenticated"], 200);
        } else {
            return response()->json(["Invalid Credentials"], 411);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return view('auth.login');
    }
}
