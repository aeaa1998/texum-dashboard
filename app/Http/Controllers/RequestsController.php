<?php

namespace App\Http\Controllers;

use App\Models\Request;

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
    }
    public function update() {
    }
    public function delete() {
    }
}
