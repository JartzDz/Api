<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all()->map(function ($categoria) {
            // Construir la URL pública completa de la imagen
            if ($categoria->imagen_categoria) {
                $categoria->imagen_url = Storage::disk('public')->url($categoria->imagen_categoria);
            } else {
                $categoria->imagen_url = null;
            }
            return $categoria;
        });

        return response()->json(['categorias' => $categorias], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_negocio' => 'required|exists:negocio,id_negocio',
            'nombre_categoria' => 'required|string',
            'descripcion' => 'nullable|string',
            'habilitado' => 'required',
            'imagen_categoria' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagen_categoria = null;
        if ($request->hasFile('imagen_categoria')) {
            // Guardar la imagen en la carpeta publica
            $imagen_categoria = $request->file('imagen_categoria')->store('imagenes_categorias', 'public');
        }

        $categoria = Categoria::create([
            'id_negocio' => $request->id_negocio,
            'nombre_categoria' => $request->nombre_categoria,
            'descripcion' => $request->descripcion,
            'habilitado' => $request->habilitado,
            'imagen_categoria' => $imagen_categoria,
        ]);

        return response()->json(['message' => 'Categoría creada correctamente', 'categoria' => $categoria], 201);
    }

    public function show($id_negocio)
    {
        $categorias = Categoria::where('id_negocio', $id_negocio)->get();

        if ($categorias->isEmpty()) {
            return response()->json(['message' => 'No se encontraron categorías para este negocio'], 404);
        }

        $data = $categorias->map(function ($categoria) {
            return [
                'id_categoria' => $categoria->id_categoria,
                'id_negocio' => $categoria->id_negocio,
                'nombre_categoria' => $categoria->nombre_categoria,
                'descripcion' => $categoria->descripcion,
                'habilitado' => $categoria->habilitado,
                'imagen_categoria' => $categoria->imagen_categoria ? url('storage/' . $categoria->imagen_categoria) : null,
                'fecha_creacion' => $categoria->fecha_creacion,
            ];
        });

        return response()->json(['message' => 'Categorías obtenidas correctamente', 'data' => $data], 200);
    }

    public function showCategoria($id_categoria)
    {
        $categoria = Categoria::find($id_categoria);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $data = [
            'id_categoria' => $categoria->id_categoria,
            'id_negocio' => $categoria->id_negocio,
            'nombre_categoria' => $categoria->nombre_categoria,
            'descripcion' => $categoria->descripcion,
            'habilitado' => $categoria->habilitado,
            'imagen_categoria' => $categoria->imagen_categoria ? url('storage/' . $categoria->imagen_categoria) : null,
            'fecha_creacion' => $categoria->fecha_creacion,
        ];

        return response()->json(['message' => 'Categoría obtenida correctamente', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $request->validate([
            'id_negocio' => 'exists:negocio,id_negocio',
            'nombre_categoria' => 'string',
            'descripcion' => 'nullable|string',
            'habilitado' => 'nullable',
            'imagen_categoria' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen_categoria')) {
            // Eliminar la imagen anterior si existe
            if ($categoria->imagen_categoria) {
                Storage::disk('public')->delete($categoria->imagen_categoria);
            }
            // Guardar la nueva imagen en la carpeta pública
            $categoria->imagen_categoria = $request->file('imagen_categoria')->store('imagenes_categorias', 'public');
        }

        $categoria->id_negocio = $request->input('id_negocio', $categoria->id_negocio);
        $categoria->nombre_categoria = $request->input('nombre_categoria', $categoria->nombre_categoria);
        $categoria->descripcion = $request->input('descripcion', $categoria->descripcion);
        $categoria->habilitado = $request->input('habilitado', $categoria->habilitado);
        $categoria->save();

        return response()->json(['message' => 'Categoría actualizada correctamente', 'categoria' => $categoria], 200);
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        // Eliminar la imagen asociada si existe
        if ($categoria->imagen_categoria) {
            Storage::disk('public')->delete($categoria->imagen_categoria);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
    }
}
