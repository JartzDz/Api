<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';

    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'id_negocio',
        'nombre_categoria',
        'descripcion',
        'habilitado',
        'imagen_categoria',
        'fecha_creacion',
    ];

    public $timestamps = false;
}

