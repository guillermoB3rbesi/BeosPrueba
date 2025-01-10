<?php

namespace Tests\Feature;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_currencies()
    {
        Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $response = $this->getJson('/api/currencies');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'USD']);
    }

    public function test_create_currency()
    {
        $currencyData = [
            'name' => 'EUR',
            'symbol' => '€',
            'exchange_rate' => 0.85
        ];

        $response = $this->postJson('/api/currencies', $currencyData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('currencies', ['name' => 'EUR']);
    }

    public function test_create_currency_with_invalid_data()
    {
        $invalidData = [
            'name' => '',
            'symbol' => '€',
            'exchange_rate' => 'not_a_number'
        ];

        $response = $this->postJson('/api/currencies', $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'exchange_rate']);
    }

    public function test_update_currency()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $updatedData = [
            'name' => 'USD Updated',
            'exchange_rate' => 1.2,
            'symbol' => '$',
        ];

        $response = $this->putJson('/api/currencies/' . $currency->id, $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('currencies', ['name' => 'USD Updated']);
    }

    public function test_delete_currency()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $response = $this->deleteJson('/api/currencies/' . $currency->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('currencies', ['id' => $currency->id]);
    }

    public function test_show_currency()
    {
        $currency = Currency::create([
            'name' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.0
        ]);

        $response = $this->getJson('/api/currencies/' . $currency->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'USD']);
    }
}
