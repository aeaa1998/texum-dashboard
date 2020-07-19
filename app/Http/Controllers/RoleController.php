<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    //INDEX, SHOW, CREATE, UPDATE, DELETE
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }


    public function show($id)
    {
        $role = Role::find($id);
        return response()->json($role);
    }


    public function update(Request $request,$id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        return response()->json($role);
    }


    public function create(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        return response()->json($role);
    }


    public function delete(Request $request, $id)
    {
        $role = Role::find($id);
        $role->delete();
        return response()->json($role);
    }
}
