<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

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
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Considera el tipo de dato correcto para la imagen
            'precio' => 'required|numeric',
            'fecha_creacion' => 'required|date',
        ]);

        $producto = Producto::create([
            'id_categoria' => $request->id_categoria,
            'id_negocio' => $request->id_negocio,
            'nombre_producto' => $request->nombre_producto,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'precio' => $request->precio,
            'fecha_creacion' => $request->fecha_creacion,
        ]);

        return response()->json(['message' => 'Producto creado correctamente', 'producto' => $producto], 201);
    }

    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json(['producto' => $producto], 200);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $request->validate([
            'id_categoria' => 'exists:categoria,id_categoria',
            'id_negocio' => 'exists:negocio,id_negocio',
            'nombre_producto' => 'string',
            'descripcion' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Considera el tipo de dato correcto para la imagen
            'precio' => 'numeric',
            'fecha_creacion' => 'date',
        ]);

        $producto->id_categoria = $request->input('id_categoria', $producto->id_categoria);
        $producto->id_negocio = $request->input('id_negocio', $producto->id_negocio);
        $producto->nombre_producto = $request->input('nombre_producto', $producto->nombre_producto);
        $producto->descripcion = $request->input('descripcion', $producto->descripcion);
        $producto->imagen = $request->input('imagen', $producto->imagen);
        $producto->precio = $request->input('precio', $producto->precio);
        $producto->fecha_creacion = $request->input('fecha_creacion', $producto->fecha_creacion);
        $producto->save();

        return response()->json(['message' => 'Producto actualizado correctamente', 'producto' => $producto], 200);
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}

