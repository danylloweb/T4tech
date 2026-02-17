<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            [
                'id' => 1,
                'name' => 'Administrador',
                'description' => 'Pode criar, ler, atualizar e deletar registros',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Usuário',
                'description' => 'Pode criar, ler e atualizar registros. Não pode deletar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

