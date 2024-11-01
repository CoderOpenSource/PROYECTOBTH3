<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\CategoriaProducto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías por nombre
        $categoriaMaquillaje = CategoriaProducto::where('nombre', 'Maquillaje')->first();
        $categoriaCuidadoPiel = CategoriaProducto::where('nombre', 'Cuidado de la Piel')->first();
        $categoriaFragancias = CategoriaProducto::where('nombre', 'Fragancias')->first();
        $categoriaCuidadoCabello = CategoriaProducto::where('nombre', 'Cuidado del Cabello')->first();
        $categoriaAccesorios = CategoriaProducto::where('nombre', 'Accesorios')->first();

        // Crear 10 productos
        $productos = [
            ['nombre' => 'Base de Maquillaje', 'descripcion' => 'Base líquida para todo tipo de piel', 'precio_compra' => 150, 'precio_venta' => 250, 'stock' => 100, 'foto_url' => '/images/base.jpg', 'categoria_id' => $categoriaMaquillaje->id],
            ['nombre' => 'Labial Mate', 'descripcion' => 'Labial de larga duración', 'precio_compra' => 50, 'precio_venta' => 120, 'stock' => 200, 'foto_url' => '/images/labial.jpg', 'categoria_id' => $categoriaMaquillaje->id],
            ['nombre' => 'Serum Facial', 'descripcion' => 'Serum antioxidante para el cuidado de la piel', 'precio_compra' => 100, 'precio_venta' => 200, 'stock' => 80, 'foto_url' => '/images/serum.jpg', 'categoria_id' => $categoriaCuidadoPiel->id],
            ['nombre' => 'Crema Hidratante', 'descripcion' => 'Crema hidratante para piel seca', 'precio_compra' => 90, 'precio_venta' => 180, 'stock' => 70, 'foto_url' => '/images/crema.jpg', 'categoria_id' => $categoriaCuidadoPiel->id],
            ['nombre' => 'Perfume Floral', 'descripcion' => 'Perfume de larga duración con aroma floral', 'precio_compra' => 200, 'precio_venta' => 400, 'stock' => 50, 'foto_url' => '/images/perfume.jpg', 'categoria_id' => $categoriaFragancias->id],
            ['nombre' => 'Perfume Amaderado', 'descripcion' => 'Perfume con aroma amaderado', 'precio_compra' => 180, 'precio_venta' => 360, 'stock' => 45, 'foto_url' => '/images/perfume2.jpg', 'categoria_id' => $categoriaFragancias->id],
            ['nombre' => 'Shampoo Hidratante', 'descripcion' => 'Shampoo para cabello seco', 'precio_compra' => 60, 'precio_venta' => 120, 'stock' => 90, 'foto_url' => '/images/shampoo.jpg', 'categoria_id' => $categoriaCuidadoCabello->id],
            ['nombre' => 'Acondicionador Nutritivo', 'descripcion' => 'Acondicionador para cabello dañado', 'precio_compra' => 65, 'precio_venta' => 130, 'stock' => 85, 'foto_url' => '/images/acondicionador.jpg', 'categoria_id' => $categoriaCuidadoCabello->id],
            ['nombre' => 'Brocha de Maquillaje', 'descripcion' => 'Brocha para base líquida', 'precio_compra' => 30, 'precio_venta' => 60, 'stock' => 120, 'foto_url' => '/images/brocha.jpg', 'categoria_id' => $categoriaAccesorios->id],
            ['nombre' => 'Esponja de Maquillaje', 'descripcion' => 'Esponja para difuminar maquillaje', 'precio_compra' => 20, 'precio_venta' => 50, 'stock' => 150, 'foto_url' => '/images/esponja.jpg', 'categoria_id' => $categoriaAccesorios->id],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
