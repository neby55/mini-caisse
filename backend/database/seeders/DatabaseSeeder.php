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
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('users')->insert([
            'name' => 'Caisse',
            'email' => 'caisse@gmail.com',
            'password' => Hash::make('caisse'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('users')->insert([
            'name' => 'Preparateur',
            'email' => 'prepa@gmail.com',
            'password' => Hash::make('prepa'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding Roles
        DB::table('roles')->insert([
            'name' => 'admin',
            'slug' => 'admin',
            'permissions' => '{"orders.add": "1", "orders.edit": "1", "orders.view": "1", "products.add": "1", "orders.delete": "1", "products.edit": "1", "products.view": "1", "platform.index": "1", "products.delete": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.attachment": "1"}',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'gerant',
            'slug' => 'gerant',
            'permissions' => '{"orders.add": "1", "orders.edit": "1", "orders.view": "1", "products.add": "1", "orders.delete": "1", "products.edit": "1", "products.view": "1", "platform.index": "1", "products.delete": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "0"}',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'caisse',
            'slug' => 'caisse',
            'permissions' => '{"orders.add": "1", "orders.edit": "1", "orders.view": "1", "products.add": "0", "orders.delete": "0", "products.edit": "0", "products.view": "1", "platform.index": "1", "products.delete": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "0"}',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'serveur',
            'slug' => 'serveur',
            'permissions' => '{"orders.add": "1", "orders.edit": "1", "orders.view": "1", "products.add": "0", "orders.delete": "1", "products.edit": "0", "products.view": "1", "platform.index": "1", "products.delete": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "0"}',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'preparateur',
            'slug' => 'prepa',
            'permissions' => '{"orders.add": "0", "orders.edit": "0", "orders.view": "1", "products.add": "0", "orders.delete": "0", "products.edit": "0", "products.view": "1", "platform.index": "1", "products.delete": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.attachment": "0"}',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Adding Users has roles
        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 4
        ]);
        DB::table('role_users')->insert([
            'user_id' => 2,
            'role_id' => 2
        ]);
        DB::table('role_users')->insert([
            'user_id' => 3,
            'role_id' => 1
        ]);
        DB::table('role_users')->insert([
            'user_id' => 4,
            'role_id' => 3
        ]);
        DB::table('role_users')->insert([
            'user_id' => 5,
            'role_id' => 5
        ]);

        // Adding Orders & Products
        $this->call([
            ProductSeeder::class,
            OrderSeeder::class
        ]);
    }
}
