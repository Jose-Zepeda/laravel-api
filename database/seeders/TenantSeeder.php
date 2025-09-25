<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Usuario;
use App\Models\Tarea;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tenants de ejemplo
        $tenant1 = Tenant::create([
            'id' => 'empresa-a',
            'name' => 'Empresa A',
            'subdomain' => 'empresa-a',
            'config' => json_encode(['theme' => 'blue', 'features' => ['tasks', 'users']]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $tenant2 = Tenant::create([
            'id' => 'empresa-b',
            'name' => 'Empresa B', 
            'subdomain' => 'empresa-b',
            'config' => json_encode(['theme' => 'green', 'features' => ['tasks']]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simular el tenant activo para crear usuarios
        app()->instance('tenant', $tenant1);
        
        // Usuarios para Empresa A
        $usuario1 = Usuario::create([
            'nombre' => 'Admin Empresa A',
            'email' => 'admin@empresa-a.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant1->id
        ]);

        $usuario2 = Usuario::create([
            'nombre' => 'Usuario Empresa A',
            'email' => 'user@empresa-a.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant1->id
        ]);

        // Tareas para Empresa A
        Tarea::create([
            'titulo' => 'Tarea 1 - Empresa A',
            'descripcion' => 'Descripción de la primera tarea de Empresa A',
            'estado' => 'pendiente',
            'fecha_vencimiento' => now()->addDays(7)->toDateString(),
            'usuario_id' => $usuario1->id,
            'tenant_id' => $tenant1->id
        ]);

        Tarea::create([
            'titulo' => 'Tarea 2 - Empresa A',
            'descripcion' => 'Descripción de la segunda tarea de Empresa A',
            'estado' => 'completada',
            'fecha_vencimiento' => now()->addDays(3)->toDateString(),
            'usuario_id' => $usuario2->id,
            'tenant_id' => $tenant1->id
        ]);

        // Cambiar al tenant 2
        app()->instance('tenant', $tenant2);

        // Usuarios para Empresa B
        $usuario3 = Usuario::create([
            'nombre' => 'Admin Empresa B',
            'email' => 'admin@empresa-b.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant2->id
        ]);

        $usuario4 = Usuario::create([
            'nombre' => 'Usuario Empresa B',
            'email' => 'user@empresa-b.com',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant2->id
        ]);

        // Tareas para Empresa B
        Tarea::create([
            'titulo' => 'Tarea 1 - Empresa B',
            'descripcion' => 'Descripción de la primera tarea de Empresa B',
            'estado' => 'pendiente',
            'fecha_vencimiento' => now()->addDays(5)->toDateString(),
            'usuario_id' => $usuario3->id,
            'tenant_id' => $tenant2->id
        ]);

        Tarea::create([
            'titulo' => 'Tarea 2 - Empresa B',
            'descripcion' => 'Descripción de la segunda tarea de Empresa B',
            'estado' => 'en_progreso',
            'fecha_vencimiento' => now()->addDays(10)->toDateString(),
            'usuario_id' => $usuario4->id,
            'tenant_id' => $tenant2->id
        ]);
    }
}
