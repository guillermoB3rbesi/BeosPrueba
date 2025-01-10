<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $data = [
            'name' => 'Producto A',
            'description' => 'Descripción del producto',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ];

        $response = $this->postJson('/api/products', $data);
        
        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => 'Producto A',
            'price' => 100.00
        ]);
    }

    public function test_update_product()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $product = Product::create([
            'name' => 'Producto A',
            'description' => 'Descripción inicial',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ]);

        $updatedData = [
            'name' => 'Producto A Actualizado',
            'description' => 'Descripción actualizada',
            'price' => 120.00,
            'currency_id' => $currency->id,
            'tax_cost' => 12.00,
            'manufacturing_cost' => 55.00
        ];

        $response = $this->putJson('/api/products/' . $product->id, $updatedData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Producto A Actualizado',
            'price' => 120.00
        ]);
    }


    public function test_show_product()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $product = Product::create([
            'name' => 'Producto A',
            'description' => 'Descripción del producto',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Producto A',
            'description' => 'Descripción del producto'
        ]);
    }


    public function test_delete_product()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $product = Product::create([
            'name' => 'Producto A',
            'description' => 'Descripción del producto',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ]);

        $response = $this->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }


    public function test_create_product_with_invalid_data()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $invalidData = [
            'description' => 'Descripción del producto',
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ];

        $response = $this->postJson('/api/products', $invalidData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'price', 'currency_id']);
    }


    public function test_update_product_with_invalid_data()
    {

        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $product = Product::create([
            'name' => 'Producto A',
            'description' => 'Descripción del producto',
            'price' => 100.00,
            'currency_id' => $currency->id,
            'tax_cost' => 10.00,
            'manufacturing_cost' => 50.00
        ]);

        $invalidData = [
            'price' => 'invalid_value',
            'currency_id' => $currency->id
        ];

        $response = $this->putJson('/api/products/' . $product->id, $invalidData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['price']);
    }

    public function test_delete_product_with_invalid_id()
    {
        $response = $this->deleteJson('/api/products/999999');
        $response->assertStatus(404);
    }
}
