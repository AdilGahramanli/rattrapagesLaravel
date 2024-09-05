<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Appelez ici vos seeders personnalisés
                $this->call([
                    RolesTableSeeder::class,
                    AdminUserSeeder::class, // Ajout du seeder AdminUserSeeder
                    ProductSeeder::class
                ]);



        // Créer des artisans avec des stores associés
        User::factory()
            ->count(5) // Nombre d'artisans
            ->has(Store::factory()->count(1)) // Chaque artisan a un store
            ->create();
    }


}
