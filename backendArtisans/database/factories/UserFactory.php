<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => Role::where('name', 'user')->first()->id, // Assigner le rôle 'user' par défaut
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

     /**
         * State for creating an admin user.
         */
        public function admin(): static
        {
            return $this->state(function (array $attributes) {
                return [
                    'name' => 'Admin', // Nom de l'administrateur
                    'email' => 'admin@example.com', // Email de l'administrateur
                    'role_id' => Role::where('name', 'admin')->first()->id, // Assigner le rôle 'admin'
                ];
            });
        }
}
