<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetAllProducts()
    {
        Product::factory()->count(10)->create();
        $response = $this->get('/api/products');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'price', 'description'],
        ]);
    }

    public function testWhenThereAreNoProductsItShouldReturnAnEmptyArray()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([]);
    }
}
