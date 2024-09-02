<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

        public function definition()
        {
            return [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'price' => $this->faker->randomFloat(2, 5, 100),
                'stock' => $this->faker->numberBetween(1, 100),
            ];
        }
}
