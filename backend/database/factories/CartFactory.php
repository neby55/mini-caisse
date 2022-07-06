<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $selectedProduct = Product::all()->random();
        $selectedOrder = Order::all()->random();
        return [
            'order_id' => $selectedOrder,
            'product_id' => $selectedProduct,
            'quantity' => fake()->randomDigitNotNull(),
            'price' => $selectedProduct->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }
}
