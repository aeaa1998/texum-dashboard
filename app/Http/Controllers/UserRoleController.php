<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    //INDEX, SHOW, CREATE, UPDATE, DELETE
    public function index()
    {
        $userRoles = UserRole::all();
        return response()->json($userRoles);
    }


    public function show($id)
    {
        $userRole = UserRole::find($id);
        return response()->json($userRole);
    }


    public function update(Request $request,$id)
    {
        $userRole = UserRole::find($id);
        $userRole->name = $request->name;
        $userRole->save();
        return response()->json($userRole);
    }


    public function create(Request $request)
    {
        $userRole = new UserRole();
        $userRole->user_id = $request->user_id;
        $userRole->role_id = $request->role_id;
        $userRole->save();
        return response()->json($userRole);
    }


    public function delete(Request $request, $id)
    {
        $userRole = UserRole::find($id);
        $userRole->delete();
        return response()->json($userRole);
    }
}
