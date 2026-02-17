<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@t4tech.com',
            'password' => Hash::make('admin123'),
            'user_type_id' => 1,
        ]);

        // Regular User
        User::create([
            'name' => 'Usuário Regular',
            'email' => 'user@t4tech.com',
            'password' => Hash::make('user123'),
            'user_type_id' => 2,
        ]);
    }
}

