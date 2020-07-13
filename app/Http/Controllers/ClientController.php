<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;

class ClientController extends Controller
{
    // Obtener todos los Clientes
    public function getAllClients()
    {
    }

    // Actualizar Cliente
    public function updateClient($oldClientName,$newClientName)
    {
    }

    // Crear Cliente
    public function insertClient(Request $request)
    {
        $client = new Client();
    
        $client->name = $request->input('name');

        $client->save();
        return response()->json($client);
    }

    // Borrar Cliente
    public function deleteClient($clientName)
    {
    }
}
