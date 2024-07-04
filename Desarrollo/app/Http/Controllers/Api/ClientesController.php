<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json(['message' => 'Lista de clientes obtenida correctamente', 'data' => $clientes], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo de validación para una imagen
            'id_cliente' => 'required|exists:usuarios,id_usuario', // Validar que el id_cliente exista en la tabla usuarios
        ]);

        // Guardar la foto_perfil en el servidor si es necesario
        $foto_perfil = $request->file('foto_perfil')->store('public/fotos_perfil');

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'foto_perfil' => $foto_perfil,
            'id_cliente' => $request->id_cliente,
        ]);

        return response()->json(['message' => 'Cliente creado correctamente', 'data' => $cliente], 201);
    }
    
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json(['message' => 'Cliente obtenido correctamente', 'data' => $cliente], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'foto_perfil' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Permitir actualización opcional de imagen
            'id_cliente' => 'required|exists:usuarios,id_usuario', // Validar que el id_cliente exista en la tabla usuarios
        ]);

        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        // Actualizar los campos del cliente
        $cliente->nombre = $request->nombre;

        if ($request->hasFile('foto_perfil')) {
            // Eliminar la imagen anterior si se actualiza
            Storage::delete($cliente->foto_perfil);

            // Guardar la nueva imagen
            $cliente->foto_perfil = $request->file('foto_perfil')->store('public/fotos_perfil');
        }

        $cliente->id_cliente = $request->id_cliente;
        $cliente->save();

        return response()->json(['message' => 'Cliente actualizado correctamente', 'data' => $cliente], 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        // Eliminar la imagen del perfil del cliente
        Storage::delete($cliente->foto_perfil);

        // Eliminar el cliente
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente'], 200);
    }

}
