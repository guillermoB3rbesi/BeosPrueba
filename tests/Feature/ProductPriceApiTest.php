<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Currency;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_prices_for_product()
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

        $price = ProductPrice::create([
            'price' => 120.00,
            'currency_id' => $currency->id,
            'product_id' => $product->id
        ]);

        $response = $this->getJson("/api/products/{$product->id}/prices");

        $response->assertStatus(200);
        $response->assertJsonFragment(["price" => "120.00"]);
    }

    public function test_create_price_for_product()
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

        $priceData = [
            'price' => 120.00,
            'currency_id' => $currency->id,
            'product_id' => $product->id
        ];

        $response = $this->postJson("/api/products/{$product->id}/prices", $priceData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('product_prices', ['price' => 120.00]);
    }

    public function test_create_price_for_product_with_invalid_data()
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
            'price' => 'invalid_amount',
            'currency_id' => null
        ];

        $response = $this->postJson("/api/products/{$product->id}/prices", $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price', 'currency_id']);
    }

    public function test_get_prices_for_nonexistent_product()
    {
        $response = $this->getJson('/api/products/999/prices');
        $response->assertStatus(404);
    }
}
