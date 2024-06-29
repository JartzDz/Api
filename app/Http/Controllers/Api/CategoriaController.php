<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        return response()->json(['categorias' => $categorias], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_negocio' => 'required|exists:negocio,id_negocio',
            'nombre_categoria' => 'required|string',
            'descripcion' => 'nullable|string',
            'habilitado' => 'required|boolean',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Considera el tipo de dato correcto para la imagen
            'fecha_creacion' => 'required|date',
        ]);

        $categoria = Categoria::create([
            'id_negocio' => $request->id_negocio,
            'nombre_categoria' => $request->nombre_categoria,
            'descripcion' => $request->descripcion,
            'habilitado' => $request->habilitado,
            'imagen' => $request->imagen,
            'fecha_creacion' => $request->fecha_creacion,
        ]);

        return response()->json(['message' => 'Categoría creada correctamente', 'categoria' => $categoria], 201);
    }

    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json(['categoria' => $categoria], 200);
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
            'habilitado' => 'boolean',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Considera el tipo de dato correcto para la imagen
            'fecha_creacion' => 'date',
        ]);

        $categoria->id_negocio = $request->input('id_negocio', $categoria->id_negocio);
        $categoria->nombre_categoria = $request->input('nombre_categoria', $categoria->nombre_categoria);
        $categoria->descripcion = $request->input('descripcion', $categoria->descripcion);
        $categoria->habilitado = $request->input('habilitado', $categoria->habilitado);
        $categoria->imagen = $request->input('imagen', $categoria->imagen);
        $categoria->fecha_creacion = $request->input('fecha_creacion', $categoria->fecha_creacion);
        $categoria->save();

        return response()->json(['message' => 'Categoría actualizada correctamente', 'categoria' => $categoria], 200);
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
    }
}
