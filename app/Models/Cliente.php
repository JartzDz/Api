<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'cliente'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id_cliente'; // Clave primaria de la tabla

    protected $fillable = [
        'id_cliente',
        'nombre',
        'foto_perfil',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_cliente', 'id_usuario');
    }
}
