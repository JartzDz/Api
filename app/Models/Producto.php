<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_categoria',
        'id_negocio',
        'nombre_producto',
        'descripcion',
        'imagen',
        'precio',
        'fecha_creacion',
    ];

    public $timestamps = false;

    // Relación con la categoría de negocio
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Relación con el negocio
    public function negocio()
    {
        return $this->belongsTo(Negocio::class, 'id_negocio', 'id_negocio');
    }
}
