<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sala;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nome' => 'Admin Eseis',
            'email' => 'admin@eseis.psc.br',
            'cpf' => '000.000.000-00',
            'password' => Hash::make('admin123'),
            'perfil' => 'admin',
        ]);

        Sala::create([
            'nome'=> 'Sala 1',
            'descricao' => 'Sala de reunião',
            'capacidade' => 1,
            'valor_hora' => 100.00,
            'infantil' => false,
            'online' => false,
            'ar_condicionado' => true,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'cpf' => '111.111.111-11',
        //     'password' => Hash::make('test123'),
        //     'role' => 'user',
        // ]);
    }
}
