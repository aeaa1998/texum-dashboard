<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;

class ClientController extends Controller
{
    // Obtener todos los Clientes
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }
    // Obtener cliente por id
    public function show($id)
    {
        $client = Client::find($id);
        return response()->json($client);
    }

    // Actualizar Cliente
    public function update(Request $request,$id)
    {
    }

    // Crear Cliente
    public function create(Request $request)
    {
        $client = new Client();
    
        $client->name = $request->input('name');

        $client->save();
        return response()->json($client);
    }

    // Borrar Cliente
    public function delete($id)
    {
    }
}
