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

        //Crear usuario normal de prueba
        $user = User::create([
            'name' => 'Eler',
            'lastname' => 'Borneo Cantalicio',
            'email' => 'eborneoc50@hotmail.com',
            'document' => '40613742',
            'sex' => 0,
            'birthdate' => '2006-01-02',
            'password' => Hash::make('123456789'),
        ]);

        // Asignar rol "user" al usuario normal
        $userRole = Role::where('name', 'administrator')->first();
        $user->assignRole($userRole);
    }
}
