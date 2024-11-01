<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Definimos la tabla relacionada con el modelo
    protected $table = 'productos';

    // Permitimos la asignación masiva de estos atributos
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'foto_url',
        'categoria_id',
    ];

    // Relación con la categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }
}
