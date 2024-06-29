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

    protected $casts = [
        'horario_apertura' => 'time',
        'horario_cierre' => 'time',
        'horario_oferta' => 'time',
    ];

    // Validación de existencia de id_categoria en la tabla categoria_negocio
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!Categoria_Negocio::where('id_categoria', $model->id_categoria)->exists()) {
                throw new \Exception('La categoría de negocio especificada no existe.');
            }
        });
    }

    // Relación con la tabla de usuarios (uno a uno)
    public function usuario()
    {
        return $this->hasOne(User::class, 'id_usuario', 'id_negocio');
    }

    // Relación con la tabla categoria_negocio
    public function categoria()
    {
        return $this->belongsTo(Categoria_Negocio::class, 'id_categoria', 'id_categoria');
    }
}
