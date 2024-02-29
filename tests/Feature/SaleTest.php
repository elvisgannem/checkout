<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public Collection $products;
    public Sale $sale;
    protected function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
        $this->sale = Sale::factory()->create();
        $this->products = Product::factory()->count(4)->create();

        foreach ($this->products as $product) {
            $amount = rand(1, 20);
            $this->sale->products()->attach($product, ['product_amount' => $amount]);
        }
    }

    public function testCanGetAllSalesWithProducts()
    {
        $response = $this->get('/api/sales');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'amount',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'amount'
                    ]
                ]
            ]
        ]);
    }

    public function testShouldReturnAnEmptyArrayWhenSaleDoesntExist()
    {
        $nonExistentSaleId = 9999;
        $response = $this->get("/api/sales/$nonExistentSaleId");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([]);
    }

    public function testShouldReturnSaleWithProductsWhenSaleExists()
    {
        $saleId = Sale::inRandomOrder()->first()->id;
        $response = $this->get("/api/sales/$saleId");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'amount',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'amount',
                    ],
                ],
            ],
        ]);
    }

    public function testCreatesSaleWithProducts()
    {
        $payload = [
            'products' => [
                ['id' => $this->products[0]->id, 'amount' => 2],
                ['id' => $this->products[1]->id, 'amount' => 1],
            ],
        ];

        $response = $this->post('/api/sales', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'amount',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'amount',
                    ],
                ],
            ],
        ]);
    }

    public function testUpdatesSaleWithProducts()
    {
        $payload = [
            'products' => [
                ['id' => $this->products[0]->id, 'amount' => 2],
                ['id' => $this->products[1]->id, 'amount' => 1],
            ],
        ];
        $response = $this->put("/api/sales/{$this->sale->id}", $payload);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'amount',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'amount',
                    ],
                ],
            ],
        ]);
    }

    public function testUpdateSaleNotFound()
    {
        $payload = [
            'products' => [
                ['id' => $this->products[0]->id, 'amount' => 2],
                ['id' => $this->products[1]->id, 'amount' => 1],
            ],
        ];
        $nonExistentSaleId = 999;
        $response = $this->put("/api/sales/{$nonExistentSaleId}", $payload);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDestroySaleShouldReturnA_SuccessMessage()
    {
        $response = $this->delete("/api/sales/{$this->sale->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
           'message' => 'Successfully canceled'
        ]);
    }
}
