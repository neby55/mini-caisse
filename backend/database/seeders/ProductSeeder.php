<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add useful products
        DB::table('products')->insert([
            'name' => 'Sandwichs',
            'price' => 2,
            'color' => '#d35400',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('products')->insert([
            'name' => 'Boissons',
            'price' => 1.5,
            'color' => '#e74c3c',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('products')->insert([
            'name' => 'Bières',
            'price' => 2,
            'color' => '#2ecc71',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('products')->insert([
            'name' => 'Chips',
            'price' => 0.5,
            'color' => '#d4ac0d',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('products')->insert([
            'name' => 'Desserts',
            'price' => 0.5,
            'color' => '#fbeee6',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('products')->insert([
            'name' => 'Eau 50cl',
            'price' => 0.5,
            'color' => '#3498db',
            'status' => 'enabled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
    }
}
