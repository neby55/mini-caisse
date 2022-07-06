<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'price' => fake()->randomFloat(1, 0.5, 5),
            'color' => fake()->safeHexColor(),
            'status' => Arr::random(ProductStatus::cases()),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }
}
