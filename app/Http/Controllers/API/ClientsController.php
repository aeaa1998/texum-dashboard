<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lot;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::select('id', 'name')->get();
        return response()->json($clients);
    }

    public function lots($id)
    {
        return Lot::where('client_id', $id)->where('is_delivered', 0)->get()->map(fn ($i) => ["name" => $i->number, "id" => $i->id]);
    }
}
