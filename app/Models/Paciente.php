<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Paciente extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $primaryKey = 'id_paciente';
    public $table='pacientes';
    protected $fillable = [
        'cod_paciente',
        'num_doc',
        'nombre',
        'apellido',
        'ciudad',
        'departamento',
        'direccion',
        'edad',
        'sexo',
        'url_maps',
        'tiene_ips',
        'fallecido',
        'estado',
        'diagnostico',
        'comentario'

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['num_doc',
        'nombre',
        'apellido',
        'ciudad',
        'departamento',
        'direccion',
        'edad',
        'sexo',
        'url_maps',
        'tiene_ips',
        'fallecido',
        'estado',
        'diagnostico',
        'comentario'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }

}
