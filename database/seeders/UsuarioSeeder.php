<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario administrador
        Usuario::create([
            'nombre' => 'Admin',
            'email' => 'admin@rosayflor.com',
            'password' => Hash::make('admin12345'), // Hasheando la contraseña
            'rol' => 'administrador',
        ]);
    }
}
