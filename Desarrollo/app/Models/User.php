<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuario'; // o 'usuario' si esa es tu tabla

    protected $primaryKey = 'id_usuario'; // Especifica la clave primaria

    public $timestamps = false;

    protected $fillable = [
        'email',
        'contrasenia',
        'telefono',
        'posicion_x',
        'posicion_y',
        'fecha_registro',
        'tipo_usuario',
    ];

    protected $hidden = [
        'contrasenia',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }
}