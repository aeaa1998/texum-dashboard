<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CreateUsersTable;
use App\CreateWorkersTable;
use App\Models\Worker;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $timestamp = now();

        User::insert(
            ['id' => null, 'email' => $email, 'password' => $password, 'created_at' => $timestamp]
        );

        $userID = DB::table('users')->select('id')->where('email',$email)->get();

        Worker::insert(
            ['id' => null, 'name' => $first_name, 'last_name' => $last_name, 'user_id' => $userID]
        );

        return response()->json(["User Registered!", 200]);
    }

    public function login(Request $request)
    {
        $userCredentials = $request->only('email','password');

        if (Auth::attempt($userCredentials))
        {
            return response()->json(["Authenticated", 200]);
        }
        else {
            return response()->json(["Invalid Credentials", 411]);
        }
    }
}
