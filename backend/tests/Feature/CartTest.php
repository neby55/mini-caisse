<?php

namespace Tests\Feature;

use App\Models\Cart;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * @return void
     */
    public function test_store_list_of_carts(): void
    {
        $seeded = Cart::all()->count();
        
        Cart::factory()->count(4)->create();

        $this->assertDatabaseCount('carts', $seeded + 4);
    }

    /**
     * @return void
     */
    public function test_store_one_cart(): void
    {
        $cart = Cart::factory()->create();
        $this->assertModelExists($cart);
    }

    /**
     * @return void
     */
    public function test_update_a_cart(): void
    {
        $cart = Cart::factory()->create();
        $this->assertModelExists($cart);
        $cart->quantity = 99;
        $cart->save();
        $this->assertDatabaseHas('carts', [
            'quantity' => 99 // factory generates between 1 and 9
        ]);
    }

    /**
     * @return void
     */
    public function test_delete_one_cart(): void
    {
        $cart = Cart::factory()->create();
        $this->assertModelExists($cart);
        $cart->delete();
        $this->assertSoftDeleted($cart);
    }
}
