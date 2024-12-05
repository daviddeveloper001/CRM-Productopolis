<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Condición para buscar el registro
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'), // Los valores que deseas actualizar o crear
            ]
        );
        
    }
}
