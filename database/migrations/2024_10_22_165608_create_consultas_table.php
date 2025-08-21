<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id('id_consulta'); // Identificador de la consulta
            $table->text('sintomas');
            $table->text('diagnostico');
            $table->text('tratamiento');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('paciente_id'); // Relación con Paciente
            $table->unsignedBigInteger('medico_id');   // Relación con Médico
            $table->unsignedBigInteger('sala_id');     // Relación con Sala
            $table->unsignedBigInteger('tipo_consulta_id'); // Relación con TipoConsulta
            $table->unsignedBigInteger('cita_id');     // Relación con Cita
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
