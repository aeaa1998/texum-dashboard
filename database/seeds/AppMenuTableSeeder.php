<?php

use App\Models\AppMenu;
use App\Models\RoleAppMenu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AppMenuTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Schema::disableForeignKeyConstraints();
		$now = Carbon::now();
		AppMenu::truncate();
		
		AppMenu::insert(['name' => 'Paqueteria', 'slug' => 'packages', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Historial', 'slug' => 'package-history', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Solicitudes', 'slug' => 'package-requests', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Trabajadores', 'slug' => 'users', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Roles', 'slug' => 'roles', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Clientes', 'slug' => 'clients', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Cortes', 'slug' => 'lots', 'created_at' => $now, 'updated_at' => $now]);
		self::insertRoles(3, AppMenu::all()->map(fn($i) => $i->slug)->toArray());
		self::insertRoles(2, AppMenu::all()->map(fn($i) => $i->slug)->toArray());
		self::insertRoles(1, AppMenu::all()->map(fn($i) => $i->slug)->toArray());
		Schema::enableForeignKeyConstraints();
	}

	private static function insertRoles($roleId, $slugs){
		$menus = AppMenu::whereIn('slug', $slugs)->get();
		$menus->map(function($item) use($roleId){
			RoleAppMenu::updateOrCreate([
				'role_id' => $roleId,
			'app_menu_id' => $item->id,
			], [
				'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			]);

		}, []);

	}
}
