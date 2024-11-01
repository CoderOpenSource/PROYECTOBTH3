<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las ventas con sus detalles y pagos
        $ventas = Venta::with(['detalles.producto', 'pago'])->get();
        $productos = Producto::all(); // Obtener productos disponibles para el modal

        // Retornar la vista y pasarle las ventas y productos
        return view('ventas.index', compact('ventas', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener los productos para crear los detalles de venta
        $productos = Producto::all();

        // Retornar la vista de creación de ventas
        return view('ventas.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si el usuario tiene el rol de administrador o empleado
        if (session('rol') !== 'administrador' && session('rol') !== 'empleado') {
            return redirect('/login')->withErrors(['No tienes acceso a esta área.']);
        }

        // Validar los datos del formulario
        $request->validate([
            'total' => 'required|numeric',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|string',
            'imagen_pago' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validar la imagen si es QR
        ]);

        DB::transaction(function () use ($request) {
            // Crear la venta
            $venta = Venta::create([
                'total' => $request->total,
                'usuario_id' => session('usuario_id'),
                'fecha' => now(),
            ]);

            foreach ($request->productos as $producto) {
                $productoInfo = Producto::find($producto['producto_id']);

                // Verificar si hay suficiente stock
                if ($productoInfo->stock < $producto['cantidad']) {
                    throw new \Exception("Stock mínimo insuficiente para el producto {$productoInfo->nombre}");
                }

                // Reducir el stock del producto
                $productoInfo->stock -= $producto['cantidad'];
                $productoInfo->save();

                // Crear el detalle de venta
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_venta' => $productoInfo->precio_venta,
                    'ganancia' => $productoInfo->precio_venta - $productoInfo->precio_compra,
                ]);
            }

            // Subir la imagen a Cloudinary si el método de pago es QR y se ha proporcionado una imagen
            $imagenPagoUrl = null;
            if ($request->metodo_pago === 'QR' && $request->hasFile('imagen_pago')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('imagen_pago')->getRealPath())->getSecurePath();
                $imagenPagoUrl = $uploadedFileUrl;
            }

            // Registrar el pago
            Pago::create([
                'venta_id' => $venta->id,
                'metodo_pago' => $request->metodo_pago,
                'imagen_pago' => $imagenPagoUrl, // Guardar la URL de Cloudinary si es QR
                'monto' => $request->total,
            ]);
        });

        return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        // Cargar los detalles y pagos junto con la venta
        $venta->load(['detalles', 'pago']);
        $productos = Producto::all(); // Obtener productos disponibles

        return view('ventas.edit', compact('venta', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'total' => 'required|numeric',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|string',
            'imagen_pago' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $venta) {
            $venta->update(['total' => $request->total]);

            $nuevosProductosIds = collect($request->productos)->pluck('producto_id')->toArray();
            DetalleVenta::where('venta_id', $venta->id)
                ->whereNotIn('producto_id', $nuevosProductosIds)
                ->delete();

            foreach ($request->productos as $producto) {
                $productoInfo = Producto::find($producto['producto_id']);

                // Verificar el stock disponible
                if ($productoInfo->stock < $producto['cantidad']) {
                    throw new \Exception("Stock mínimo insuficiente para el producto {$productoInfo->nombre}");
                }

                $detalle = DetalleVenta::where('venta_id', $venta->id)
                    ->where('producto_id', $producto['producto_id'])
                    ->first();

                if ($detalle) {
                    // Restaurar stock original antes de la actualización
                    $productoInfo->stock += $detalle->cantidad;
                    $productoInfo->save();
                    $detalle->update([
                        'cantidad' => $producto['cantidad'],
                        'precio_venta' => $productoInfo->precio_venta,
                    ]);
                } else {
                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto['producto_id'],
                        'cantidad' => $producto['cantidad'],
                        'precio_venta' => $productoInfo->precio_venta,
                        'ganancia' => $productoInfo->precio_venta - $productoInfo->precio_compra,
                    ]);
                }

                // Reducir el stock del producto
                $productoInfo->stock -= $producto['cantidad'];
                $productoInfo->save();
            }

            $imagenPagoUrl = $venta->pago->imagen_pago;
            if ($request->metodo_pago === 'QR' && $request->hasFile('imagen_pago')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('imagen_pago')->getRealPath())->getSecurePath();
                $imagenPagoUrl = $uploadedFileUrl;
            }

            $venta->pago->update([
                'metodo_pago' => $request->metodo_pago,
                'monto' => $request->total,
                'imagen_pago' => $imagenPagoUrl,
            ]);
        });

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        // Eliminar los detalles relacionados
        $venta->detalles()->delete();

        // Eliminar el pago relacionado, si lo deseas
        $venta->pago()->delete();

        // Ahora eliminar la venta
        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente.');
    }

}
