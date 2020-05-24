<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;

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
        //Permisos para Trabajadores y Cuadradores de Empaques
        Role::insert(['name' => 'Ingresar empaques', 'created_at' => $now, 'updated_at' => $now]);
        Role::insert(['name' => 'Modificar ubicacion de empaques', 'created_at' => $now, 'updated_at' => $now]);
        Role::insert(['name' => 'Despachar empaques', 'created_at' => $now, 'updated_at' => $now]);
        //Permisos para Admnistrador
        Role::insert(['name' => 'Agregar Usuarios', 'created_at' => $now, 'updated_at' => $now]);
        Role::insert(['name' => 'Eliminar Usuarios', 'created_at' => $now, 'updated_at' => $now]);
        //Permisos para Jefes
        Role::insert(['name' => 'Convertir Data en archivo PDF', 'created_at' => $now, 'updated_at' => $now]);
        Role::insert(['name' => 'Convertir Data en archivo XLS', 'created_at' => $now, 'updated_at' => $now]);
        //Permisos para Todos los Usuarios
        Role::insert(['name' => 'Ver Historial', 'created_at' => $now, 'updated_at' => $now]);
        Role::insert(['name' => 'Realizar Busqueda Detallada', 'created_at' => $now, 'updated_at' => $now]);
    }
}
