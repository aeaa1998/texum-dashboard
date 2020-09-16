<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        Role::truncate();
        //Permisos para Trabajadores y Cuadradores de Empaques: Ingresar empaques, modificar ubicacion de empaques, despachar empaques y ver historial
        Role::insert(['name' => 'Trabajador', 'created_at' => $now, 'updated_at' => $now]);
        //Permisos para Admnistrador: Agregar usuarios, quitar usuarios, ver historial y realizar busqueda detallada, todos
        Role::insert(['name' => 'Administrador de bodega', 'created_at' => $now, 'updated_at' => $now]);
        //Permisos para Jefes: Convertir queries a formatos pdf o xls, ver historial y realizar busqueda detallada
        Role::insert(['name' => 'Jefe', 'created_at' => $now, 'updated_at' => $now]);
        //
        Role::insert(['name' => 'Administrador', 'created_at' => $now, 'updated_at' => $now]);
    }
}
