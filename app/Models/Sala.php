<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sala extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $primaryKey = 'id_sala';
    public $table='salas';
    protected $fillable = [
        'nombre',
        'tipo_sala',
        'num_sala'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nombre',
        'tipo_sala',
        'num_sala'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
