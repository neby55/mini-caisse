<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @return void
     */
    public function test_store_list_of_products(): void
    {
        $seededProducts = Product::all()->count();
        
        Product::factory()->count(4)->create();

        $this->assertDatabaseCount('products', $seededProducts + 4);
    }

    /**
     * @return void
     */
    public function test_store_one_product(): void
    {
        $product = Product::factory()->create();
        $this->assertModelExists($product);
    }

    /**
     * @return void
     */
    public function test_update_a_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Aubergine',
        ]);
        $this->assertModelExists($product);
        $product->name = 'Betterave';
        $product->save();
        $this->assertDatabaseHas('products', [
            'name' => 'Betterave',
        ]);
    }

    /**
     * @return void
     */
    public function test_delete_one_product(): void
    {
        $product = Product::factory()->create();
        $this->assertModelExists($product);
        $product->delete();
        $this->assertSoftDeleted($product);
    }
}
