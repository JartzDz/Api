<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;

    protected $table = 'negocio';

    protected $primaryKey = 'id_negocio';

    public $timestamps = false;

    protected $fillable = [
        'id_negocio',
        'id_categoria',
        'nombre_negocio',
        'descripcion',
        'horario_apertura',
        'horario_cierre',
        'horario_oferta',
        'logotipo',
        'imagen_referencial',
        'posicion_x',
        'posicion_y',
    ];



    // Relación con la tabla de usuarios (uno a uno)
    public function usuario()
    {
        return $this->hasOne(User::class, 'id_usuario', 'id_negocio');
    }

}
