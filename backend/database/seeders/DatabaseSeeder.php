<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding users
        DB::table('users')->insert([
            'name' => 'Ben',
            'email' => 'neby55@gmail.com',
            'password' => Hash::make('password^^'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('users')->insert([
            'name' => 'Owen',
            'email' => 'hasheur@gmail.com',
            'password' => Hash::make('justmining'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding Orders & Products
        $this->call([
            ProductSeeder::class,
            OrderSeeder::class
        ]);
    }
}
