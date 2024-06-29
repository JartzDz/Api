<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NegociosController extends Controller
{
    public function index()
    {
        $negocios = Negocio::all();
        return response()->json(['message' => 'Lista de negocios obtenida correctamente', 'data' => $negocios], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'nombre_negocio' => 'required|string',
            'descripcion' => 'required|string',
            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i',
            'horario_oferta' => 'nullable|date_format:H:i',
            'logotipo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_referencial' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posicion_x' => 'required|numeric',
            'posicion_y' => 'required|numeric',
        ]);

        // Guardar logotipo si está presente
        $logotipo = $request->file('logotipo') ? $request->file('logotipo')->store('public/logotipos') : null;

        // Guardar imagen referencial si está presente
        $imagen_referencial = $request->file('imagen_referencial') ? $request->file('imagen_referencial')->store('public/imagenes_referenciales') : null;

        $negocio = Negocio::create([
            'id_categoria' => $request->id_categoria,
            'nombre_negocio' => $request->nombre_negocio,
            'descripcion' => $request->descripcion,
            'horario_apertura' => $request->horario_apertura,
            'horario_cierre' => $request->horario_cierre,
            'horario_oferta' => $request->horario_oferta,
            'logotipo' => $logotipo,
            'imagen_referencial' => $imagen_referencial,
            'posicion_x' => $request->posicion_x,
            'posicion_y' => $request->posicion_y,
        ]);

        return response()->json(['message' => 'Negocio creado correctamente', 'data' => $negocio], 201);
    }

    public function show($id)
    {
        $negocio = Negocio::find($id);

        if (!$negocio) {
            return response()->json(['message' => 'Negocio no encontrado'], 404);
        }

        return response()->json(['message' => 'Negocio obtenido correctamente', 'data' => $negocio], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_categoria' => 'sometimes|exists:categorias,id_categoria',
            'nombre_negocio' => 'sometimes|string',
            'descripcion' => 'sometimes|string',
            'horario_apertura' => 'sometimes|date_format:H:i',
            'horario_cierre' => 'sometimes|date_format:H:i',
            'horario_oferta' => 'nullable|date_format:H:i',
            'logotipo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_referencial' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posicion_x' => 'sometimes|numeric',
            'posicion_y' => 'sometimes|numeric',
        ]);

        $negocio = Negocio::find($id);

        if (!$negocio) {
            return response()->json(['message' => 'Negocio no encontrado'], 404);
        }

        // Actualizar logotipo si está presente
        if ($request->hasFile('logotipo')) {
            Storage::delete($negocio->logotipo);
            $logotipo = $request->file('logotipo')->store('public/logotipos');
        } else {
            $logotipo = $negocio->logotipo;
        }

        // Actualizar imagen referencial si está presente
        if ($request->hasFile('imagen_referencial')) {
            Storage::delete($negocio->imagen_referencial);
            $imagen_referencial = $request->file('imagen_referencial')->store('public/imagenes_referenciales');
        } else {
            $imagen_referencial = $negocio->imagen_referencial;
        }

        // Actualizar campos del negocio
        $negocio->update([
            'id_categoria' => $request->id_categoria ?? $negocio->id_categoria,
            'nombre_negocio' => $request->nombre_negocio ?? $negocio->nombre_negocio,
            'descripcion' => $request->descripcion ?? $negocio->descripcion,
            'horario_apertura' => $request->horario_apertura ?? $negocio->horario_apertura,
            'horario_cierre' => $request->horario_cierre ?? $negocio->horario_cierre,
            'horario_oferta' => $request->horario_oferta ?? $negocio->horario_oferta,
            'logotipo' => $logotipo,
            'imagen_referencial' => $imagen_referencial,
            'posicion_x' => $request->posicion_x ?? $negocio->posicion_x,
            'posicion_y' => $request->posicion_y ?? $negocio->posicion_y,
        ]);

        return response()->json(['message' => 'Negocio actualizado correctamente', 'data' => $negocio], 200);
    }

    public function destroy($id)
    {
        $negocio = Negocio::find($id);

        if (!$negocio) {
            return response()->json(['message' => 'Negocio no encontrado'], 404);
        }

        // Eliminar archivos de imágenes si existen
        Storage::delete($negocio->logotipo);
        Storage::delete($negocio->imagen_referencial);

        // Eliminar el negocio
        $negocio->delete();

        return response()->json(['message' => 'Negocio eliminado correctamente'], 200);
    }
}
