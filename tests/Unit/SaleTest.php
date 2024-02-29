<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Sale;
use Tests\TestCase;

class SaleTest extends TestCase
{
    public function testCalculateTotalAmount(): void
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
}
