<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5 empleados con rol de "empleado"
        $empleados = [
            ['nombre' => 'Empleado1', 'email' => 'empleado1@rosayflor.com', 'password' => Hash::make('empleado123'), 'rol' => 'empleado'],
            ['nombre' => 'Empleado2', 'email' => 'empleado2@rosayflor.com', 'password' => Hash::make('empleado123'), 'rol' => 'empleado'],
            ['nombre' => 'Empleado3', 'email' => 'empleado3@rosayflor.com', 'password' => Hash::make('empleado123'), 'rol' => 'empleado'],
            ['nombre' => 'Empleado4', 'email' => 'empleado4@rosayflor.com', 'password' => Hash::make('empleado123'), 'rol' => 'empleado'],
            ['nombre' => 'Empleado5', 'email' => 'empleado5@rosayflor.com', 'password' => Hash::make('empleado123'), 'rol' => 'empleado'],
        ];

        foreach ($empleados as $empleado) {
            Usuario::create($empleado);
        }
    }
}
