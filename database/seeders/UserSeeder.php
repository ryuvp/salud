<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador de prueba
        $admin = User::create([
            'name' => 'Administrador',
            'lastname' => 'Administrador',
            'email' => 'admin@example.com',
            'document' => '0000000001',
            'birthdate' => '2006-01-02 15:04:05',
            'password' => Hash::make('password'),
        ]);

        // Asignar rol "superadmin" al usuario administrador
        $superadminRole = Role::where('name', 'superadmin')->first();
        $admin->assignRole($superadminRole);

        // Crear usuario normal de prueba
        $user = User::create([
            'name' => 'Carlos Andrés',
            'lastname' => 'Pérez García',
            'email' => 'carlos.perez@example.com',
            'document' => '12345678',
            'sex' => 1,
            'birthdate' => '1980-05-14',
            'ipress_id' => 35174,
            'phone' => '987654321',
            'ubigeo' => '150101',
            'address' => 'Av. Los Olivos 123, Lima',
            'clinic_history' => 'HX12345',
        ]);

        // Asignar rol "user" al usuario normal
        $userRole = Role::where('name', 'paciente')->first();
        $user->assignRole($userRole);
    }
}
