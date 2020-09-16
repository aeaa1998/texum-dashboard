<?php

use App\Models\AppMenu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppMenuTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$now = Carbon::now();
		AppMenu::insert(['name' => 'Paqueteria', 'slug' => 'packages', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Historial', 'slug' => 'package-history', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Solicitudes', 'slug' => 'package-requests', 'created_at' => $now, 'updated_at' => $now]);
		AppMenu::insert(['name' => 'Trabajadores', 'slug' => 'users', 'created_at' => $now, 'updated_at' => $now]);
	}
}
