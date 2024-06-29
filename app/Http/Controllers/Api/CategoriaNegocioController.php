<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria_Negocio;
use Illuminate\Http\Request;

class CategoriaNegocioController extends Controller
{
    public function index()
    {
        $categorias = Categoria_Negocio::all();

        return response()->json(['categorias' => $categorias], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:categoria_negocio,nombre',
        ]);

        $categoria = Categoria_Negocio::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json(['message' => 'Categoría de negocio creada correctamente', 'categoria' => $categoria], 201);
    }

    public function show($id)
    {
        $categoria = Categoria_Negocio::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría de negocio no encontrada'], 404);
        }

        return response()->json(['categoria' => $categoria], 200);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria_Negocio::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría de negocio no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|unique:categoria_negocio,nombre,' . $categoria->id_categoria . ',id_categoria',
        ]);

        $categoria->nombre = $request->nombre;
        $categoria->save();

        return response()->json(['message' => 'Categoría de negocio actualizada correctamente', 'categoria' => $categoria], 200);
    }

    public function destroy($id)
    {
        $categoria = Categoria_Negocio::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría de negocio no encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoría de negocio eliminada correctamente'], 200);
    }
}
