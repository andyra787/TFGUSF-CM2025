<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* *** CREACION DE ROLES *** */
        $rolAdmin = Role::create(['name' => 'admin']);
        $rolMedico = Role::create(['name' => 'medico']);
        $rolRecepcion = Role::create(['name' => 'recepcionista']);
        $rolAdministrativo = Role::create(['name' => 'administrativo']);

        /* *** CREACION DE PERMISOS *** */
        // Permisos para vista de citas
        $permCita1 = Permission::create(['name' => 'ver citas']);
        $permCita2 = Permission::create(['name' => 'crear citas']);
        $permCita3 = Permission::create(['name' => 'modificar citas']);
        $permCita4 = Permission::create(['name' => 'borrar citas']);
        $permCita5 = Permission::create(['name' => 'crear solo estado de cita']);
        $permCita6 = Permission::create(['name' => 'ver solo cita de medico']);
        $permCitaReporte = Permission::create(['name' => 'reportes citas']);

        // Permisos para vista de pacientes
        $permPaciente1 = Permission::create(['name' => 'ver pacientes']);
        $permPaciente2 = Permission::create(['name' => 'crear pacientes']);
        $permPaciente3 = Permission::create(['name' => 'modificar pacientes']);
        $permPaciente4 = Permission::create(['name' => 'borrar pacientes']);
        $permPacienteReporte = Permission::create(['name' => 'reportes pacientes']);

        // Permisos para vista de especialidades
        $permEspecialidad1 = Permission::create(['name' => 'ver especialidades']);
        $permEspecialidad2 = Permission::create(['name' => 'crear especialidades']);
        $permEspecialidad3 = Permission::create(['name' => 'modificar especialidades']);
        $permEspecialidad4 = Permission::create(['name' => 'borrar especialidades']);

        // Permisos para vista de medicos
        $permMedico1 = Permission::create(['name' => 'ver medicos']);
        $permMedico2 = Permission::create(['name' => 'crear medicos']);
        $permMedico3 = Permission::create(['name' => 'modificar medicos']);
        $permMedico4 = Permission::create(['name' => 'borrar medicos']);
        $permMedicoReporte = Permission::create(['name' => 'reportes medicos']);

        // Permisos para vista de salas
        $permSala1 = Permission::create(['name' => 'ver salas']);
        $permSala2 = Permission::create(['name' => 'crear salas']);
        $permSala3 = Permission::create(['name' => 'modificar salas']);
        $permSala4 = Permission::create(['name' => 'borrar salas']);

        // permisos para vista de departamentos
        $permDepartamento1 = Permission::create(['name' => 'ver departamentos']);
        $permDepartamento2 = Permission::create(['name' => 'crear departamentos']);
        $permDepartamento3 = Permission::create(['name' => 'modificar departamentos']);
        $permDepartamento4 = Permission::create(['name' => 'borrar departamentos']);

        // permisos para vista de ciudades
        $permCiudad1 = Permission::create(['name' => 'ver ciudades']);
        $permCiudad2 = Permission::create(['name' => 'crear ciudades']);
        $permCiudad3 = Permission::create(['name' => 'modificar ciudades']);
        $permCiudad4 = Permission::create(['name' => 'borrar ciudades']);

        // Permisos para vista de usuarios
        $permUsuario1 = Permission::create(['name' => 'ver usuarios']);
        $permUsuario2 = Permission::create(['name' => 'crear usuarios']);
        $permUsuario3 = Permission::create(['name' => 'modificar usuarios']);
        $permUsuario4 = Permission::create(['name' => 'borrar usuarios']);

        // Permisos para vista de tipos de consultas
        $permTipoConsulta1 = Permission::create(['name' => 'ver tipo_consulta']);
        $permTipoConsulta2 = Permission::create(['name' => 'crear tipo_consulta']);
        $permTipoConsulta3 = Permission::create(['name' => 'modificar tipo_consulta']);
        $permTipoConsulta4 = Permission::create(['name' => 'borrar tipo_consulta']);

        /* *** ASIGNACION DE PERMISOS A ROLES *** */
        // Permisos para rol admin
        $rolAdmin->syncPermissions([
            $permCita1,
            $permCita2,
            $permCita3,
            $permCita4,
            $permCitaReporte,
            $permPaciente1,
            $permPaciente2,
            $permPaciente3,
            $permPaciente4,
            $permPacienteReporte,
            $permEspecialidad1,
            $permEspecialidad2,
            $permEspecialidad3,
            $permEspecialidad4,
            $permMedico1,
            $permMedico2,
            $permMedico3,
            $permMedico4,
            $permMedicoReporte,
            $permSala1,
            $permSala2,
            $permSala3,
            $permSala4,
            $permDepartamento1,
            $permDepartamento2,
            $permDepartamento3,
            $permDepartamento4,
            $permCiudad1,
            $permCiudad2,
            $permCiudad3,
            $permCiudad4,
            $permUsuario1,
            $permUsuario2,
            $permUsuario3,
            $permUsuario4,
            $permTipoConsulta1,
            $permTipoConsulta2,
            $permTipoConsulta3,
            $permTipoConsulta4,
        ]);

        // Permisos para rol medico
        $rolMedico->syncPermissions([
            $permCita1,
            $permCita5,
            $permCita6,
            $permCitaReporte,
            $permPaciente1,
            $permPaciente2,
            $permPaciente3,
            $permPacienteReporte,
        ]);

        // Permisos para rol recepcionista
        $rolRecepcion->syncPermissions([
            $permCita1,
            $permCita2,
            $permCita3,
            $permCita4,
            $permCitaReporte,
            $permPaciente1,
            $permPaciente2,
            $permPaciente3,
            $permPacienteReporte,
            
        ]);

        // Permisos para rol administrativo
        $rolAdministrativo->syncPermissions([
            $permCita1,
            $permCitaReporte,
            $permPaciente1,
            $permPacienteReporte,
            $permEspecialidad1,
            $permMedico1,
            $permMedicoReporte,
            $permSala1,
            $permDepartamento1,
            $permCiudad1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
