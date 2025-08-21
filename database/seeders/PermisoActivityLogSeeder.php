<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoActivityLogSeeder extends Seeder
{
    public function run()
    {
        // Crear permiso si no existe
        $permiso = Permission::firstOrCreate([
            'name' => 'leer activity log',
            'guard_name' => 'web',
        ]);

        // Asignar a rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permiso);
        }
    }
}
