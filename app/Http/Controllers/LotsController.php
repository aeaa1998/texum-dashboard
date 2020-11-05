<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\Package;

class LotsController extends Controller
{
    public function index() {
        $lots = Lot::all();
        //return response()->json($lots);
        $lots = $lots->map(function($lot){
            $createdAt = $lot->created_at;
            unset($lot->created_at);
            $lot->create_date = $createdAt->format('Y-m-d h:m:s a');
            return $lot;
        });
        return view('dashboard.lots')->with(['lots' => $lots]);
    }
    public function show($id) {
        $lot = Lot::find($id);
        return response()->json($lot);
    }
    public function update(Request $request, $id) {
        $lot = Lot::find($id);
        $lot->number = $request->number;
        $lot->client_id = $request->client_id;
        $lot->is_delivered = $request->is_delivered;
        $lot->cut_date = $request->cut_date;
        $lot->save();
        return response()->json($lot);
    }
    public function create(Request $request) {
        $lot = new Lot();
        $lot->number = $request->number;
        $lot->client_id = $request->client_id;
        $lot->is_delivered = $request->is_delivered;
        $lot->cut_date = $request->cut_date;
        $lot->save();
        return response()->json($lot);
    }
    public function delete(Request $request, $id) {
        $lot = Lot::find($id);
        $lot->delete();
        return response()->json($lot);
    }

  #  public function deliverLot(Request $request, $id)
   # {
    #    $lot=Lot::find($id);
     #   $packages = Package::with(['lot.package'])
      #      ->whereHas('lot.package', function ($query) use ($id) {
       #         $query->where('lot_id', $id);
        #    })
        #    ->get();
       # return response()->json(['packages' => $packages]);
        //return response()->json($lot);
    #}
    
}
