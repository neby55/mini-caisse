<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Products
        $sandwich = DB::table('products')->where('name', 'Sandwichs')->first();
        $boisson = DB::table('products')->where('name', 'Boissons')->first();
        $biere = DB::table('products')->where('name', 'Bières')->first();
        $chips = DB::table('products')->where('name', 'Chips')->first();
        $dessert = DB::table('products')->where('name', 'Desserts')->first();

        // Adding first order
        $firstOrderId = DB::table('orders')->insertGetId([
            'number' => 193,
            'amount' => 10.5,
            'payment_date' => \Carbon\Carbon::now()->toDateTimeString(),
            'payment_mode' => 'cash',
            'user_id' => DB::table('users')->get()->random()->id,
            'status' => 'completed',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        // And its content
        DB::table('carts')->insert([
            'order_id' => $firstOrderId,
            'product_id' => $sandwich->id,
            'quantity' => 3,
            'price' => $sandwich->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $firstOrderId,
            'product_id' => $boisson->id,
            'quantity' => 3,
            'price' => $boisson->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding second order
        $secondOrderId = DB::table('orders')->insertGetId([
            'number' => 180,
            'amount' => 9.5,
            'user_id' => DB::table('users')->get()->random()->id,
            'status' => 'created',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        // And its content
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $sandwich->id,
            'quantity' => 2,
            'price' => $sandwich->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $boisson->id,
            'quantity' => 1,
            'price' => $boisson->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $biere->id,
            'quantity' => 1,
            'price' => $biere->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $chips->id,
            'quantity' => 2,
            'price' => $chips->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $dessert->id,
            'quantity' => 2,
            'price' => $dessert->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding third order
        $secondOrderId = DB::table('orders')->insertGetId([
            'number' => 188,
            'amount' => 9.5,
            'user_id' => 4,
            'payment_date' => '2022-06-24 18:15',
            'payment_mode' => 'cash',
            'status' => 'paid',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        // And its content
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $sandwich->id,
            'quantity' => 2,
            'price' => $sandwich->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $boisson->id,
            'quantity' => 1,
            'price' => $boisson->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $biere->id,
            'quantity' => 1,
            'price' => $biere->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $chips->id,
            'quantity' => 2,
            'price' => $chips->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $dessert->id,
            'quantity' => 2,
            'price' => $dessert->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding 4th order
        $secondOrderId = DB::table('orders')->insertGetId([
            'number' => 189,
            'amount' => 10,
            'user_id' => 4,
            'payment_date' => '2022-06-24 18:15',
            'payment_mode' => 'cheque',
            'firstname' => 'Gérard',
            'lastname' => 'Manvussa',
            'email' => 'gerard.manvussa@example.com',
            'status' => 'paid',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        // And its content
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $sandwich->id,
            'quantity' => 2,
            'price' => $sandwich->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $biere->id,
            'quantity' => 2,
            'price' => $biere->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $chips->id,
            'quantity' => 2,
            'price' => $chips->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('carts')->insert([
            'order_id' => $secondOrderId,
            'product_id' => $dessert->id,
            'quantity' => 2,
            'price' => $dessert->price,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
    }
}
