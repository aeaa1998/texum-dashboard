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
        // Rol para Bodegueros
        // PERMISOS: Hacer requests de cambio de paquete, ver historial, ver busqueda avanzada
        Role::insert(['name' => 'Trabajador', 'created_at' => $now, 'updated_at' => $now]);
        // Rol para Cuadradores de paquetes
        // PERMISOS: Aceptar/Rechazar usuarios, Aceptar/Rechazr requests, ver historial, ver busqueda avanzada
        Role::insert(['name' => 'Administrador', 'created_at' => $now, 'updated_at' => $now]);
        // Rol para jefes
        // PERMISOS: Convertir queries a formatos pdf o xls, Crear/Borrar/Modificar Roles, Asignar roles a empleados, ver historial y realizar busqueda detallada
        Role::insert(['name' => 'Jefe', 'created_at' => $now, 'updated_at' => $now]);
    }
}
