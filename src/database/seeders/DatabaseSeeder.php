<?php

namespace Database\Seeders;

use App\Models\User;
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
            'name' => 'Admin Eseis',
            'email' => 'admin@eseis.psc.br',
            'cpf' => '000.000.000-00',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
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
