<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $msg = $request->validate([
            'email'      => 'required|email|unique:App\Models\User,email',
            'password'   => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);
        $user           = new User();
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = 1;
        $user->save();
        $worker             = new Worker();
        $worker->first_name = $request->first_name;
        $worker->last_name  = $request->last_name;
        $worker->user_id    = $user->id;
        $worker->save();

        // Mail::to($request->email)->queue(new WelcomeEmail($msg));
        return response()->json(["message" => "successfully created"]);
    }

    public function login(Request $request)
    {
        
        $userCredentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        $userVerification = $user->verified_at;
        if($userVerification == null) {
            return response()->json(["Not Verified"], 411);
        }
        if (Auth::attempt($userCredentials)) {
            $request->session()->put('user_id', $user->id);
            return response()->json(["Authenticated"], 200);

        } else {
            return response()->json(["Invalid Credentials"], 411);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}
