<?php

namespace Database\Factories;

use App\Constants\ProductTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->randomDigit(),
            'type' => fake()->randomElement(array_keys(ProductTypes::ALL)),
            'user_id' => fake()->randomElement(User::all()->pluck('id'))
        ];
    }
}
