<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{
    public function registrarCliente(Request $request)
    {
        // Validación de datos para el registro de cliente
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required',
            'contrasenia' => 'required|min:6',
            // otros campos necesarios para el cliente
        ]);

        // Crear registro en la tabla de usuarios asociando el cliente
        $usuario = User::create([
            'email' => $request->email,
            'contrasenia' => Hash::make($request->contrasenia),
            'telefono' => $request->telefono,
            'tipo_usuario' => 'Cliente', // opcional: define el tipo de usuario
        ]);

        // Crear registro en la tabla de clientes
        $cliente = Cliente::create([
            'id_cliente' => $usuario->id_usuario, // asocia el id_cliente
            'nombre' => $request->nombre,
            // otros campos para el cliente
        ]);

        return response()->json(['message' => 'Registro de cliente exitoso'], 201);
    }

    public function registrarNegocio(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required',
            'contrasenia' => 'required|min:6',
            'posicion_x' => 'required|numeric',
            'posicion_y' => 'required|numeric',
        ]);

        // Crear registro en la tabla de usuarios asociando el cliente
        $usuario = User::create([
            'email' => $request->email,
            'contrasenia' => Hash::make($request->contrasenia),
            'telefono' => $request->telefono,
            'tipo_usuario' => 'Negocio', // opcional: define el tipo de usuario
        ]);


        // Crear el negocio asociado al usuario
        $negocio = Negocio::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre_negocio' => $request->nombre_negocio,
            'posicion_x' => $request->posicion_x,
            'posicion_y' => $request->posicion_y,
        ]);

        return response()->json(['message' => 'Negocio registrado correctamente', 'data' => $negocio], 201);
    }
}
