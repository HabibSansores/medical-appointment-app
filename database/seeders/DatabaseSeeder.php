<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Llamar a los seeders creados
        $this->call(RoleSeeder::class);

        //Crear Usuario de prueba cada vez que se ejecuten las migraciones
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'habibsansorespersonal@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
