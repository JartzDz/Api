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
        $negocios = Negocio::all()->map(function ($negocio) {
            // Construir las URLs públicas completas de las imágenes
            if ($negocio->logotipo) {
                $negocio->logotipo_url = Storage::disk('public')->url($negocio->logotipo);
            } else {
                $negocio->logotipo_url = null;
            }

            if ($negocio->imagen_referencial) {
                $negocio->imagen_referencial_url = Storage::disk('public')->url($negocio->imagen_referencial);
            } else {
                $negocio->imagen_referencial_url = null;
            }

            return $negocio;
        });

        return response()->json(['message' => 'Lista de negocios obtenida correctamente', 'data' => $negocios], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categoria_negocio,id_categoria',
            'nombre_negocio' => 'required|string',
            'descripcion' => 'required|string',
            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i',
            'horario_oferta' => 'nullable|date_format:H:i',
            'logotipo' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_referencial' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
            'posicion_x' => 'required|numeric',
            'posicion_y' => 'required|numeric',
        ]);

        $logotipo = null;
        if ($request->hasFile('logotipo')) {
            $logotipo = $request->file('logotipo')->store('logotipos', 'public');
        }

        $imagen_referencial = null;
        if ($request->hasFile('imagen_referencial')) {
            $imagen_referencial = $request->file('imagen_referencial')->store('imagenes_referenciales', 'public');
        }

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

        $data = [
            'id_categoria' => $negocio->id_categoria,
            'nombre_negocio' => $negocio->nombre_negocio,
            'descripcion' => $negocio->descripcion,
            'horario_apertura' => $negocio->horario_apertura,
            'horario_cierre' => $negocio->horario_cierre,
            'horario_oferta' => $negocio->horario_oferta,
            'logotipo' => $negocio->logotipo ? url('storage/' . $negocio->logotipo) : null,
            'imagen_referencial' => $negocio->imagen_referencial ? url('storage/' . $negocio->imagen_referencial) : null,
            'posicion_x' => $negocio->posicion_x,
            'posicion_y' => $negocio->posicion_y,
        ];

        return response()->json(['message' => 'Negocio obtenido correctamente', 'data' => $data], 200);
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'id_categoria' => 'sometimes|exists:categoria_negocio,id_categoria',
            'nombre_negocio' => 'sometimes|string',
            'descripcion' => 'sometimes|string',
            'horario_apertura' => 'nullable',
            'horario_cierre' => 'nullable',
            'horario_oferta' => 'nullable',
            'logotipo' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
            'imagen_referencial' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
            'posicion_x' => 'sometimes|numeric',
            'posicion_y' => 'sometimes|numeric',
        ]);

        $negocio = Negocio::find($id);

        if (!$negocio) {
            return response()->json(['message' => 'Negocio no encontrado'], 404);
        }

        if ($request->hasFile('logotipo')) {
            // Eliminar el logotipo anterior
            if ($negocio->logotipo) {
                Storage::disk('public')->delete($negocio->logotipo);
            }
            $negocio->logotipo = $request->file('logotipo')->store('logotipos', 'public');
        }

        if ($request->hasFile('imagen_referencial')) {
            // Eliminar la imagen referencial anterior
            if ($negocio->imagen_referencial) {
                Storage::disk('public')->delete($negocio->imagen_referencial);
            }
            $negocio->imagen_referencial = $request->file('imagen_referencial')->store('imagenes_referenciales', 'public');
        }

        $negocio->update($request->except(['logotipo', 'imagen_referencial']));

        return response()->json(['message' => 'Negocio actualizado correctamente'], 200);
    }


    public function destroy($id)
    {
        $negocio = Negocio::find($id);

        if (!$negocio) {
            return response()->json(['message' => 'Negocio no encontrado'], 404);
        }

        // Eliminar archivos de imágenes si existen
        if ($negocio->logotipo) {
            Storage::disk('public')->delete($negocio->logotipo);
        }
        if ($negocio->imagen_referencial) {
            Storage::disk('public')->delete($negocio->imagen_referencial);
        }

        // Eliminar el negocio
        $negocio->delete();

        return response()->json(['message' => 'Negocio eliminado correctamente'], 200);
    }

}
