<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    //INDEX, SHOW, CREATE, UPDATE, DELETE
    public function index()
    {
        $userroles = UserRole::all();
        return response()->json($userroles);
    }


    public function show($id)
    {
        $userrole = UserRole::find($id);
        return response()->json($userrole);
    }


    public function update(Request $request,$id)
    {
        $userrole = UserRole::find($id);
        $userrole->name = $request->name;
        $userrole->save();
        return response()->json($userrole);
    }


    public function create(Request $request)
    {
        $userrole = new UserRole();
        $userrole->user_id = $request->user_id;
        $userrole->role_id = $request->role_id;
        $userrole->save();
        return response()->json($userrole);
    }


    public function delete(Request $request, $id)
    {
        $userrole = UserRole::find($id);
        $userrole->delete();
        return response()->json($userrole);
    }
}
