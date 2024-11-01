<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
Use App\Http\Controllers\CategoriaProductoController;
Use App\Http\Controllers\ProductoController;
Use App\Http\Controllers\VentaController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/tables', function () {
    return view('pages.table');
})->name('tables');
Route::get('/typography', function () {
    return view('pages.typography');
})->name('typography');

Route::get('/empleados', [App\Http\Controllers\UsuarioController::class, 'index'])->name('empleados.index');
Route::post('/empleados', [App\Http\Controllers\UsuarioController::class, 'store'])->name('empleados.store');
Route::put('/empleados/{usuario}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('empleados.update');
Route::delete('/empleados/{usuario}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('empleados.destroy');


Route::get('/categorias', [CategoriaProductoController::class, 'index'])->name('categorias.index');
Route::post('/categorias', [CategoriaProductoController::class, 'store'])->name('categorias.store');
Route::put('/categorias/{categoria}', [CategoriaProductoController::class, 'update'])->name('categorias.update');
Route::delete('/categorias/{categoria}', [CategoriaProductoController::class, 'destroy'])->name('categorias.destroy');

// Rutas para productos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas para ventas
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas/{venta}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
Route::put('/ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
Route::delete('/ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/logout', function () {
    return redirect('/login'); // Redirige a la página de login después de cerrar la sesión
})->name('logout');
Route::get('/register', function () {
    return view('dashboard');
})->name('register');
