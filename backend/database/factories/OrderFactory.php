<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMode;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $selectedUser = Order::all()->random();

        return [
            'number' => fake()->randomNumber(3, true),
            'amount' => fake()->randomFloat(1, 6, 20),
            'payment_date' => \Carbon\Carbon::now()->toDateTimeString(),
            'payment_mode' => Arr::random(PaymentMode::cases()),
            'user_id' => $selectedUser->id,
            'status' => Arr::random(OrderStatus::cases()),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }
}
