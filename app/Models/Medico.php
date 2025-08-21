<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Medico extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $primaryKey = 'id_medico';
    public $table = 'medicos';
    public $fillable = [
        'nombre',
        'ci',
        'email',
        'telefono',
        'registro',
        'especialidad_id',
        'estado'
    ];

    public function especialidad()
    {
        return $this->belongsTo('App\Models\Especialidad', 'especialidad_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nombre',
        'ci',
        'email',
        'telefono',
        'registro',
        'especialidad_id',
        'estado'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
