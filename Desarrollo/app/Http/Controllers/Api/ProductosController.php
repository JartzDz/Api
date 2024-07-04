<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    public function index()
    {
        $productos = Producto::all();

        return response()->json(['productos' => $productos], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'id_negocio' => 'required|exists:negocio,id_negocio',
            'nombre_producto' => 'required|string',
            'descripcion' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'required|numeric',
        ]);

        // Guardar la imagen en la carpeta pública y obtener la URL
        $imagenUrl = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('imagenes_productos', 'public');
            $imagenUrl = Storage::disk('public')->url($imagenPath);
        }

        $producto = Producto::create([
            'id_categoria' => $request->id_categoria,
            'id_negocio' => $request->id_negocio,
            'nombre_producto' => $request->nombre_producto,
            'descripcion' => $request->descripcion,
            'imagen' => $imagenUrl, // Guardar la URL de la imagen
            'precio' => $request->precio,
        ]);

        return response()->json(['message' => 'Producto creado correctamente', 'producto' => $producto], 201);
    }

    public function showProducto($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json(['producto' => $producto], 200);
    }


    public function show($id_negocio)
{
    // Obtener los productos junto con sus categorías
    $productos = Producto::where('id_negocio', $id_negocio)
        ->with('categoria') // Cargar la relación 'categoria'
        ->get();

    if ($productos->isEmpty()) {
        return response()->json(['message' => 'No se encontraron productos para este negocio'], 404);
    }

    // Formatear la respuesta para incluir 'nombre_categoria'
    $productos_formateados = $productos->map(function ($producto) {
        return [
            'id_producto' => $producto->id_producto,
            'id_negocio' => $producto->id_negocio,
            'nombre_producto' => $producto->nombre_producto,
            'descripcion' => $producto->descripcion,
            'imagen' => $producto->imagen,
            'precio' => $producto->precio,
            'fecha_creacion' => $producto->fecha_creacion,
            'id_categoria' => $producto->categoria->nombre_categoria, // Incluir 'nombre_categoria'
        ];
    });

    return response()->json(['message' => 'Productos obtenidos correctamente', 'productos' => $productos_formateados], 200);
}

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $request->validate([
            'id_categoria' => 'exists:categoria,id_categoria',
            'nombre_producto' => 'string',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'precio' => 'numeric',
        ]);

        // Actualizar la imagen si se proporciona una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            // Guardar la nueva imagen en la carpeta pública
            $imagenUrl = $request->file('imagen')->store('imagenes_productos', 'public');
            $producto->imagen = Storage::disk('public')->url($imagenUrl);
        }

        $producto->id_categoria = $request->input('id_categoria', $producto->id_categoria);
        $producto->nombre_producto = $request->input('nombre_producto', $producto->nombre_producto);
        $producto->descripcion = $request->input('descripcion', $producto->descripcion);
        $producto->precio = $request->input('precio', $producto->precio);
        $producto->save();

        return response()->json(['message' => 'Producto actualizado correctamente', 'producto' => $producto], 200);
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Eliminar la imagen asociada si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}
