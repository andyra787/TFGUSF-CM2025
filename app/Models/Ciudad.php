<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_ciudad';
    public $table = 'ciudades';
    public $fillable = [
        'nombre',
        'departamento_id'
    ];

    public function departamento()
    {
        return $this->belongsTo('App\Models\Departamento', 'departamento_id');
    }
}
