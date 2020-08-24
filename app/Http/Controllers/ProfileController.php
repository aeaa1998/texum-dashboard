<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::with('worker')->find(Auth::user()->id);
        return view('la vista del profile')->json(['profile' => $user]);
    }

    // Obtener un perfil
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    // Actualizar un perfil
    public function update(Request $request, $id)
    {
    }
}
