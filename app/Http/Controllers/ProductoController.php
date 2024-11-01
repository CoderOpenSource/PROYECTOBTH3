<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductoController extends Controller
{
    public function index()
    {
        // Verificar si el usuario no tiene el rol de administrador ni de empleado
        if (session('rol') !== 'administrador' && session('rol') !== 'empleado') {
            return redirect('/login')->withErrors(['No tienes acceso a esta área.']);
        }

        // Obtener todos los productos con sus categorías
        $productos = Producto::with('categoria')->get();
        $categorias = CategoriaProducto::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        // Verificar si el usuario tiene el rol de administrador
        if (session('rol') !== 'administrador') {
            return redirect('/login')->withErrors(['No tienes acceso a esta área.']);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|integer',
            'foto_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validación de imagen
            'categoria_id' => 'required|exists:categoria_productos,id',
        ]);

        // Subir la imagen a Cloudinary si se ha proporcionado
        $fotoUrl = null;
        if ($request->hasFile('foto_url')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('foto_url')->getRealPath())->getSecurePath();
            $fotoUrl = $uploadedFileUrl;
        }

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'foto_url' => $fotoUrl, // Guardar la URL de Cloudinary
            'categoria_id' => $request->categoria_id,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, Producto $producto)
    {
        // Verificar si el usuario tiene el rol de administrador
        if (session('rol') !== 'administrador') {
            return redirect('/login')->withErrors(['No tienes acceso a esta área.']);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|integer',
            'foto_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validación de imagen
            'categoria_id' => 'required|exists:categoria_productos,id',
        ]);

        // Subir la imagen a Cloudinary si se ha proporcionado una nueva
        if ($request->hasFile('foto_url')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('foto_url')->getRealPath())->getSecurePath();
            $producto->foto_url = $uploadedFileUrl;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        // Verificar si el usuario tiene el rol de administrador
        if (session('rol') !== 'administrador') {
            return redirect('/login')->withErrors(['No tienes acceso a esta área.']);
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
