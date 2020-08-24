<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as Req;

class RequestsController extends Controller
{
    public function index() {
        $requests = Request::all();
        return response()->json($requests);
    }
    public function show($id) {
        $request = Request::find($id);
        return response()->json($request);
    }
    public function create(Request $request) {
        $req = new Req();
        $req->package_id = $request->package_id;
        $req->user_id = $request->user_id;
        $req->old_locker = $request->old_locker;
        $req->new_locker = $request->new_locker;
        $req->status_id = $request->status_id;
        $req->save();
        return response()->json($req);
    }
    public function update(Request $request,$id) {
        $req = Req::find($id);
        $req->package_id = $request->package_id;
        $req->user_id = $request->user_id;
        $req->old_locker = $request->old_locker;
        $req->new_locker = $request->new_locker;
        $req->status_id = $request->status_id;
        $req->save();
        return response()->json($req);
    }
    public function delete(Request $request,$id) {
        $req = Req::find($id);
        $req->delete();
        return response()->json($req);
    }
}
