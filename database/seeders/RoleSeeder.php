<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',          'display_name' => 'Administrador'],
            ['name' => 'doctor',         'display_name' => 'Médico'],
            ['name' => 'nurse',          'display_name' => 'Enfermero/a'],
            ['name' => 'kinesiologist',  'display_name' => 'Kinesiólogo/a'],
            ['name' => 'nutritionist',   'display_name' => 'Nutricionista'],
            ['name' => 'social_worker',  'display_name' => 'Trabajador/a Social'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
