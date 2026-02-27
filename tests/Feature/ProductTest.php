<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_add_product(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 9.99,
            'stock_quantity' => 100,
            'category_id' => null,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Test Product',
                    'price' => 9.99,
                    'stock_quantity' => 100,
                    'category_id' => null,
                ],
            ]);
    }
}
