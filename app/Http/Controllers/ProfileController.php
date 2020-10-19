<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {

        $users = User::with('worker')->find(Auth::user()->id);
        return view('dashboard.profile')->with(['profile' => $users]);
    }

    // Obtener un perfil
    public function show($id)
    {
        $user = User::with('worker')->find($id);
        return response()->json($user);
    }

    // Actualizar un perfil
    public function updateCredentials(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->email = $request->email;
        $user->save();
        $worker = Worker::where('user_id',63)->first();
        $worker->first_name = $request->first_name;
        $worker->last_name = $request->last_name;
        $worker->save();
        return response()->json($worker);
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user);
    }
}
