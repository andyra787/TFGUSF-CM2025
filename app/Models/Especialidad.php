<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Especialidad extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $primaryKey = 'id_especialidad';
    public $table = 'especialidades';
    public $fillable = [
        'nombre'
    ];

    public function medicos (){
        return $this->hasMany('App\Models\Medico');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nombre'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
