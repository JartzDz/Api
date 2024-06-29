<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria_Negocio extends Model
{
    use HasFactory;

    protected $table = 'categoria_negocio'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_categoria'; // Clave primaria de la tabla

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];
}
