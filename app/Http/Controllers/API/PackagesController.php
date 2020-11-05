<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lot;
use App\Models\Package;

class PackagesController extends Controller
{
    public function store(Request $request)
    {
        $insert = collect($request->packages)->map(function ($package) use ($request) {
            return [
                "bar_code" => $package['bar_code'],
                "lot_id" => $request['lot_id'],
                "status_id" => 1,
            ];
        })->toArray();
        Package::insert($insert);
        return response()->json(["message" => "created_successfully"]);
    }

    public function lots($id)
    {
        return Lot::where('client_id', $id)->where('is_delivered', 0)->get()->map(fn ($i) => ["name" => $i->number, "id" => $i->id]);
    }
}
