<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        // Consulta todos los usuarios desde la tabla 'usuarios' en el esquema 'public'
        $usuarios = DB::table('public.usuario')->get();

        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        // Ejemplo de creación de usuario
        $usuario = DB::table('public.usuario')->insert([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            // Agregar otros campos según sea necesario
        ]);

        return response()->json(['message' => 'Usuario creado correctamente']);
    }

    public function show($id)
    {
        // Consulta un usuario específico por su ID
        $usuario = DB::table('public.usuario')->where('id_usuario', $id)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }

    public function show_email($email)
    {
        // Consulta un usuario específico por su ID
        $usuario = DB::table('public.usuario')->where('email', $email)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        // Ejemplo de actualización de usuario
        DB::table('public.usuario')->where('id_usuario', $id)->update([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            // Actualizar otros campos según sea necesario
        ]);

        return response()->json(['message' => 'Usuario actualizado correctamente']);
    }

    public function destroy($id)
    {
        // Ejemplo de eliminación de usuario
        DB::table('public.usuario')->where('id_usuario', $id)->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
