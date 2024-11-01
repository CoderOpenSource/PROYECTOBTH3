<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaProducto;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de ejemplo
        CategoriaProducto::create(['nombre' => 'Maquillaje']);
        CategoriaProducto::create(['nombre' => 'Cuidado de la Piel']);
        CategoriaProducto::create(['nombre' => 'Fragancias']);
        CategoriaProducto::create(['nombre' => 'Cuidado del Cabello']);
        CategoriaProducto::create(['nombre' => 'Accesorios']);
    }
}
