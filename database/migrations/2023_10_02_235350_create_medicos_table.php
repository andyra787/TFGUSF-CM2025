19802167<?php

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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id('id_medico');
            $table->string('nombre');
            $table->string('ci');
            $table->string('email');
            $table->string('telefono');
            $table->string('registro');
            $table->unsignedBigInteger('especialidad_id');
            $table->foreign('especialidad_id')->references('id_especialidad')->on('especialidades');
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};
