<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifie si un utilisateur avec le rôle 'admin' existe déjà
                $adminExists = User::whereHas('role', function($query) {
                    $query->where('name', 'admin');
                })->exists();

        // Si aucun administrateur n'existe, en créer un
                if (!$adminExists) {
                    User::factory()->admin()->create();
                }
    }
}
