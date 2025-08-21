<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Cita extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'id_cita';
    protected $table = 'citas';

    protected $fillable = [
        'fec_inicio',
        'fec_fin',
        'estado',
        'observaciones',
        'tipo_consulta_id',
        'paciente_id',
        'medico_id',
        'sala_id',
    ];

    public function paciente(){
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id_paciente');
    }

    public function medico(){
        return $this->belongsTo(Medico::class, 'medico_id', 'id_medico');
    }

    public function sala(){
        return $this->belongsTo(Sala::class, 'sala_id', 'id_sala');
    }

    public function tipoConsulta(){
        return $this->belongsTo(TipoConsulta::class, 'tipo_consulta_id', 'id_tipo_consulta');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['fec_inicio',
        'fec_fin',
        'estado',
        'observaciones',
        'tipo_consulta_id',
        'paciente_id',
        'medico_id',
        'sala_id',])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
