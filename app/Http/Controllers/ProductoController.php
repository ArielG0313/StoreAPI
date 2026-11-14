<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    // CONSULTAR TODOS LOS PRODUCTOS
    public function index()
    {
        $productos = Producto::all();

        return response()->json([
            'success' => true,
            'mensaje' => 'Lista de productos obtenida correctamente.',
            'data' => $productos
        ], 200);
    }

    // REGISTRAR UN PRODUCTO
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación.',
                'errores' => $validator->errors()
            ], 422);
        }

        $producto = Producto::create($validator->validated());

        return response()->json([
            'success' => true,
            'mensaje' => 'Producto registrado correctamente.',
            'data' => $producto
        ], 201);
    }

    // CONSULTAR UN PRODUCTO
    public function show(string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Producto no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Producto encontrado.',
            'data' => $producto
        ], 200);
    }

    // ACTUALIZAR UN PRODUCTO
    public function update(Request $request, string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Producto no encontrado.'
            ], 404);
        }

        $regla = $request->isMethod('put')
            ? 'required'
            : 'sometimes|required';

        $validator = Validator::make($request->all(), [
            'nombre' => $regla . '|string|max:150',
            'descripcion' => 'sometimes|nullable|string',
            'precio' => $regla . '|numeric|min:0',
            'stock' => $regla . '|integer|min:0',
            'categoria' => $regla . '|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación.',
                'errores' => $validator->errors()
            ], 422);
        }

        $producto->update($validator->validated());

        return response()->json([
            'success' => true,
            'mensaje' => 'Producto actualizado correctamente.',
            'data' => $producto->fresh()
        ], 200);
    }

    // ELIMINAR UN PRODUCTO
    public function destroy(string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Producto no encontrado.'
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'Producto eliminado correctamente.'
        ], 200);
    }
}
