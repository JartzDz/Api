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
            $uploadedFile = $request->file('logotipo');
            $logotipo = base64_encode(file_get_contents($uploadedFile->getRealPath())); // Convertir a base64
        }

        $imagen_referencial = null;
        if ($request->hasFile('imagen_referencial')) {
            $uploadedFile = $request->file('imagen_referencial');
            $imagen_referencial = base64_encode(file_get_contents($uploadedFile->getRealPath())); // Convertir a base64
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

    // Codificar el logotipo y la imagen referencial a base64 si están presentes
    $logotipo = $negocio->logotipo ? utf8_encode(stream_get_contents($negocio->logotipo)) : null;
    $imagen_referencial = $negocio->imagen_referencial ? utf8_encode(stream_get_contents($negocio->imagen_referencial)) : null;

    // Preparar los datos del negocio para la respuesta
    $data = [
        'id_categoria' => $negocio->id_categoria,
        'nombre_negocio' => $negocio->nombre_negocio,
        'descripcion' => $negocio->descripcion,
        'horario_apertura' => $negocio->horario_apertura,
        'horario_cierre' => $negocio->horario_cierre,
        'horario_oferta' => $negocio->horario_oferta,
        'logotipo' => $logotipo,
        'imagen_referencial' => $imagen_referencial,
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

         // Guardar logotipo como BLOB si está presente
        if ($request->hasFile('logotipo')) {
            $logotipoFile = $request->file('logotipo');
            $logotipoBlob = base64_encode(file_get_contents($logotipoFile->getRealPath())); // Codificar en base64
            $negocio->logotipo = $logotipoBlob;
        }

        // Guardar imagen referencial como BLOB si está presente
        if ($request->hasFile('imagen_referencial')) {
            $imagenReferencialFile = $request->file('imagen_referencial');
            $imagenReferencialBlob = base64_encode(file_get_contents($imagenReferencialFile->getRealPath())); // Codificar en base64
            $negocio->imagen_referencial = $imagenReferencialBlob;
        }

        // Actualizar campos del negocio
        $negocio->id_categoria = $request->input('id_categoria', $negocio->id_categoria);
        $negocio->nombre_negocio = $request->input('nombre_negocio', $negocio->nombre_negocio);
        $negocio->descripcion = $request->input('descripcion', $negocio->descripcion);
        $negocio->horario_apertura = $request->input('horario_apertura', $negocio->horario_apertura);
        $negocio->horario_cierre = $request->input('horario_cierre', $negocio->horario_cierre);
        $negocio->horario_oferta = $request->input('horario_oferta', $negocio->horario_oferta);
        $negocio->posicion_x = $request->input('posicion_x', $negocio->posicion_x);
        $negocio->posicion_y = $request->input('posicion_y', $negocio->posicion_y);

        $negocio->save();

        return response()->json(['message' => 'Negocio actualizado correctamente'], 200);
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
