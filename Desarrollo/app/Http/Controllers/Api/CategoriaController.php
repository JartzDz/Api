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
        $categorias = Categoria::all();

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

        // Guardar la imagen en la carpeta pública
        $imagenUrl = null;
        if ($request->hasFile('imagen_categoria')) {
            $imagenNombre = $request->file('imagen_categoria')->store('imagenes_categorias', 'public');
            $imagenUrl = Storage::disk('public')->url($imagenNombre); // Obtener la URL completa de la imagen
        }

        $categoria = Categoria::create([
            'id_negocio' => $request->id_negocio,
            'nombre_categoria' => $request->nombre_categoria,
            'descripcion' => $request->descripcion,
            'habilitado' => $request->habilitado,
            'imagen_categoria' => $imagenUrl, // Guardar la URL de la imagen en el campo `imagen_categoria`
        ]);

        return response()->json(['message' => 'Categoría creada correctamente', 'categoria' => $categoria], 201);
    }

    public function show($id_negocio)
    {
        $categorias = Categoria::where('id_negocio', $id_negocio)->get();

        if ($categorias->isEmpty()) {
            return response()->json(['message' => 'No se encontraron categorías para este negocio'], 404);
        }

        return response()->json(['message' => 'Categorías obtenidas correctamente', 'categorias' => $categorias], 200);
    }

    public function showCategoria($id_categoria)
    {
        $categoria = Categoria::find($id_categoria);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json(['message' => 'Categoría obtenida correctamente', 'categoria' => $categoria], 200);
    }

    public function update(Request $request, $id)
{
    $categoria = Categoria::find($id);

    if (!$categoria) {
        return response()->json(['message' => 'Categoría no encontrada'], 404);
    }

    $request->validate([
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
        $imagenNombre = $request->file('imagen_categoria')->store('imagenes_categorias', 'public');
        $imagenUrl = Storage::disk('public')->url($imagenNombre); // Obtener la URL completa de la imagen
        $categoria->imagen_categoria = $imagenUrl; // Guardar la URL completa de la imagen
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
