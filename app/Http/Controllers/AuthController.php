<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreateUsersTable;
use App\CreateWorkersTable;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function userRegister(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $timestamp = now();

        DB::table('users')->insert(
            ['id' => null, 'email' => $email, 'password' => $password, 'created_at' => $timestamp]
        );

        $userID = DB::table('users')->select('id')->where('email',$email)->get();

        DB::table('workers')->insert(
            ['id' => null, 'name' => $first_name, 'last_name' => $last_name, 'user_id' => $userID]
        );

        return response()->json(["User Registered!", 200]);
    }
}
