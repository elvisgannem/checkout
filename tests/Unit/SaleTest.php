<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function testCalculateTotalAmount_CalculatesTotalAmountOfSaleCorrectly(): void
    {
        $sale = new Sale();

        $product1 = new Product(['price' => 10]);
        $product2 = new Product(['price' => 20]);
        $product1['amount'] = 5;
        $product2['amount'] = 2;

        $sale->products = collect([$product1, $product2]);

        $total = $sale->calculateTotalAmount();

        $this->assertEquals(90.0, $total);
    }

    public function testHandleProduct_AttachesProductToSaleIfNotExisting_UpdatesProductAmountIfExisting()
    {
        $sale = Sale::factory()->create();
        $product = Product::factory()->create();
        $product['amount'] = 1;

        $sale->handleProduct($product->toArray(), $sale->id);

        $this->assertDatabaseHas('product_sales', [
            'sale_id' => $sale->id,
            'product_id' => $product['id'],
            'product_amount' => $product['amount'],
        ]);
    }
}
