<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->dateTime('fec_inicio');
            $table->dateTime('fec_fin');
            $table->string('estado');
            $table->string('observaciones');
            $table->unsignedBigInteger('tipo_consulta_id');
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('medico_id');
            $table->unsignedBigInteger('sala_id');

            $table->timestamps();

            $table->foreign('tipo_consulta_id')->references('id_tipo_consulta')->on('tipo_consultas');
            $table->foreign('paciente_id')->references('id_paciente')->on('pacientes');
            $table->foreign('medico_id')->references('id_medico')->on('medicos');
            $table->foreign('sala_id')->references('id_sala')->on('salas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
