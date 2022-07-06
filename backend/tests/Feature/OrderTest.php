<?php

namespace Tests\Feature;

use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * @return void
     */
    public function test_store_list_of_orders(): void
    {
        $seeded = Order::all()->count();
        
        Order::factory()->count(4)->create();

        $this->assertDatabaseCount('orders', $seeded + 4);
    }

    /**
     * @return void
     */
    public function test_store_one_order(): void
    {
        $order = Order::factory()->create();
        $this->assertModelExists($order);
    }

    /**
     * @return void
     */
    public function test_update_a_order(): void
    {
        $order = Order::factory()->create([
            'lastname' => 'Aubergine',
        ]);
        $this->assertModelExists($order);
        $order->lastname = 'Betterave';
        $order->save();
        $this->assertDatabaseHas('orders', [
            'lastname' => 'Betterave',
        ]);
    }

    /**
     * @return void
     */
    public function test_delete_one_order(): void
    {
        $order = Order::factory()->create();
        $this->assertModelExists($order);
        $order->delete();
        $this->assertSoftDeleted($order);
    }
}
