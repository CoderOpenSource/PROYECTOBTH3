<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    // Definimos la tabla relacionada con el modelo
    protected $table = 'usuarios';

    // Permitimos la asignación masiva de estos atributos
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];


}
