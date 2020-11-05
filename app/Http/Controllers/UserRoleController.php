<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    //INDEX, SHOW, CREATE, UPDATE, DELETE
    public function index()
    {
        $users = User::with('userrole')->get()->map(function($i){
            $i->create_time = $i->created_at->format("Y-m-d h:i:s a");
            return $i;
        });
        return view('dashboard.roles')->with(['roles' => $users]);
    }


    public function show($id)
    {
        $userRole = UserRole::find($id);
        return response()->json($userRole);
    }

    /**
     * @param $request->user_id
     * @param $reques->roles Array<Int>
     */
    public function update(Request $request, $userId)
    {
        $user = UserRole::where('user_id', $userId)->first();
        $user->role_id = $request->role;
        $user->save();
        return response()->json($user);
    }


    /*public function create(Request $request, $userId)
    {
        $user = User::find($userId);

        collect($request->roles)->map(function ($roleId) use ($user) {
            $user->roles()->attach($roleId);
        })
        $user->refresh();
        return response()->json($user);
    }
    */

    public function delete(Request $request, $id)
    {
        $userRole = UserRole::find($id);
        $userRole->delete();
        return response()->json($userRole);
    }
}
