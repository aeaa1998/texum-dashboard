<?php

namespace App\Http\Controllers;

use App\Models\AppMenu;
use App\Models\Role;
use App\Models\RoleAppMenu;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserRole;
use Carbon\Carbon;

class UserRoleController extends Controller
{
    //INDEX, SHOW, CREATE, UPDATE, DELETE
    public function index()
    {
        $roles= Role::with("menus")->withCount("users")->get(); 
        $allMenus = AppMenu::all();
         
        $roles->transform(function($item) use($allMenus){
            $item->create_time = $item->created_at->format('Y-m-d');
            $menusIds = $item->menus->pluck('id');
            $allMenus->map(function($menu) use(&$item, $menusIds) {
                $item->{'menu-'.$menu->slug} = $menusIds->contains($menu->id);
            });
            $item->menu_open = false;
            return $item;
        });
        return view('dashboard.roles')->with(['roles' => $roles, 'menus' => $allMenus]);
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
        $user = User::with('roles')->where('id', $userId)->first();

        $user->roles()->detach();
        collect($request->roles)->map(function ($roleId) use ($user) {
            $user->roles()->attach($roleId);
        });
        $user->refresh();
        return response()->json($user);
    }


    public function store(Request $request){
        $role = new Role();
        $role->name = $request->name;
        
        $role->save();
        $role->create_time = $role->created_at->format('Y-m-d');
        $allMenus = AppMenu::all();
        $allMenus->map(function($menu) use(&$role) {
            $role->{'menu-'.$menu->slug} = false;
        });
        return response()->json($role);
    }

    public function updatePermissions(Request $request, $id){
        $role = Role::find($id);
        RoleAppMenu::where('role_id', $id)->delete();
        $inserts = $request->menus(function($menu) use($id){
            return [
				'role_id' => $id,
			'app_menu_id' => $menu->id,
				'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			];
        })->toArray();
        RoleAppMenu::insert($inserts);
        $role = Role::with("menus")->withCount("users")->find($id); 
        $allMenus = AppMenu::all();
        $menusIds = $role->menus->pluck('id');
        $allMenus->map(function($menu) use(&$role, $menusIds) {
            $role->{'menu-'.$menu->slug} = $menusIds->contains($menu->id);
        });
        return response()->json($role);
    }
    public function updatePermission(Request $request, $roleId, $menuId){
        if($request->on == 0){
            RoleAppMenu::where('role_id', $roleId)->where('app_menu_id', $menuId)->delete();
        }else{
            $roleM = new RoleAppMenu();
            $roleM->role_id = $roleId;
            $roleM->app_menu_id = $menuId;
            $roleM->save();
        }
        
        $role = Role::with("menus")->withCount("users")->find($roleId); 
        $role->create_time = $role->created_at->format('Y-m-d');
        $allMenus = AppMenu::all();
        $menusIds = $role->menus->pluck('id');
        $allMenus->map(function($menu) use(&$role, $menusIds) {
            $role->{'menu-'.$menu->slug} = $menusIds->contains($menu->id);
        });
        return response()->json($role);
    }

    public function delete(Request $request, $id)
    {
        RoleAppMenu::where('role_id', $id)->delete();
        Role::where('id', $id)->delete();
        
        return response()->json(["message" => "deleted_successfully"]);
    }
}  
