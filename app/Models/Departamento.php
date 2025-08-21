<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_departamento';
    public $table = 'departamentos';
    public $fillable = [
        'nombre',
    ];
    
    public function ciudades(){
        return $this->hasMany(Ciudad::class);
    }
}
