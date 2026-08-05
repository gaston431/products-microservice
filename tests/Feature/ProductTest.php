<?php

namespace Tests\Feature;

use App\Models\Product;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_making_an_api_get_request(): void
    {

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->getAdminToken()}",
        ])->getJson('/api/products');

        // $response
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'message' => 'Autenticado con éxito',
        //     ])->assertJson(
        //         fn(AssertableJson $json) =>
        //         $json->has('token')
        //     );

        // $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Laptop'
            ])
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function test_get_a_product_request(): void
    {
        $product = Product::first();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->getAdminToken()}",
        ])->getJson("/api/products/{$product->id}");

        $response
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Laptop'
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'price',
                'stock',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_making_an_api_put_request(): void
    {
        $product = Product::first();

        // $product = Product::create([
        //     'name' => 'Keyboard',
        //     'price' => 100,
        //     'stock' => 5,
        // ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->getAdminToken()}",
        ])->putJson("/api/products/{$product->id}", ['stock' => 10]);

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Producto actualizado con éxito')
            ->assertJsonPath('product.stock', 10)
            ->assertJsonPath('product.name', 'Laptop')
            ->assertJsonStructure([
                'message',
                'product' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                    'created_at',
                    'updated_at',
                ],
            ]);
        // ->assertOk()
        // ->assertJson(
        //     fn(AssertableJson $json) =>
        //     $json
        //         ->where('message', 'Producto actualizado con éxito')
        //         ->where('product.stock', 10)
        //         ->where('product.name', 'Laptop')
        //         ->has('product.id')
        //         ->etc()
        // );
    }
}
