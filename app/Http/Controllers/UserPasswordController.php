<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserPasswordController extends Controller
{
    public function update(Request $request, $userId)
    {
        $user = User::with('roles')->where('id', $userId)->first();
        $user->password = $request->password;
        $user->save();
        return response()->json($user);
    }

    public function index(Request $request, $userId)
    {
        $user = User::with('roles')->where('id', $userId)->first();
        return response()->json($user);
    }
    
}
