<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Consulta extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'id_consulta'; // Especificamos que la clave primaria es 'id_consulta'
    protected $table = 'consultas'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'sintomas',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'paciente_id',
        'medico_id',
        'sala_id',
        'tipo_consulta_id',
        'cita_id',
    ];

    // Relación con Paciente
    public function paciente(){
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    // Relación con Médico
    public function medico(){
        return $this->belongsTo(Medico::class, 'medico_id', 'id_medico');
    }

    // Relación con Sala
    public function sala(){
        return $this->belongsTo(Sala::class, 'sala_id', 'id_sala');
    }

    // Relación con TipoConsulta
    public function tipoConsulta(){
        return $this->belongsTo(TipoConsulta::class, 'tipo_consulta_id', 'id_tipo_consulta');
    }

    // Relación con Cita
    public function cita(){
        return $this->belongsTo(Cita::class, 'cita_id', 'id_cita');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['sintomas',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'paciente_id',
        'medico_id',
        'sala_id',
        'tipo_consulta_id',
        'cita_id',])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
