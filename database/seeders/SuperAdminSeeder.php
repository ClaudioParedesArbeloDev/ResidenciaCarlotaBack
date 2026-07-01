<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea el super admin inicial del sistema.
 * Cambiá el email y password antes de correr en producción.
 */
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'claudioparedesarbelo@gmail.com'],
            [
                'name'           => 'Super',
                'last_name'      => 'Admin',
                'email'          => 'claudioparedesarbelo@gmail.com',
                'password'       => Hash::make('cabeza80'),
                'is_active'      => true,
                'is_super_admin' => true,
            ]
        );

        $this->command->info('Super admin creado: claudioparedesarbelo@gmail.com / cabeza80');
        $this->command->warn('¡Acordate de cambiar la contraseña en producción!');
    }
}
