<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear el permiso si no existe
        $permiso = Permission::firstOrCreate(['name' => 'leer activity log']);

        // Asignar el permiso al rol admin si existe
        $rolAdmin = Role::where('name', 'admin')->first();
        if ($rolAdmin) {
            $rolAdmin->givePermissionTo($permiso);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quitar el permiso del rol admin
        $rolAdmin = Role::where('name', 'admin')->first();
        $permiso = Permission::where('name', 'leer activity log')->first();
        if ($rolAdmin && $permiso) {
            $rolAdmin->revokePermissionTo($permiso);
        }
        // Eliminar el permiso
        if ($permiso) {
            $permiso->delete();
        }
    }
};
