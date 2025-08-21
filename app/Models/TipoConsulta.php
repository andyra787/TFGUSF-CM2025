<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipoConsulta extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'id_tipo_consulta';
    public $table='tipo_consultas';
    protected $fillable = [
        'descripcion',
        'duracion'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['descripcion',
        'duracion'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
