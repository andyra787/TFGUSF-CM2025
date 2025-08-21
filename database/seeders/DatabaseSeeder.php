<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartamentosSeeder::class,
            CiudadesSeeder::class,
        ]);

        // Crear el rol de admin si aún no existe
        $adminRole = Role::where(['name' => 'admin'])->first();

        // Crear el usuario Admin
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'rol' => 'admin',
            'doc_id' => null,
            'estado' => 'activo',
            'password' => bcrypt('admin123')
        ]);

        // Asignar el rol de admin al usuario
        $adminUser->assignRole($adminRole);
    }
}
