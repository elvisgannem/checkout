<?php

namespace App\Commands\Sales;

use App\Models\ProductSale;
use App\Models\Sale;
use App\Queries\Sales\SaleQuery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SaleCommand
{
    public function __construct(
        private readonly SaleQuery $saleQuery
    )
    {
    }

    public function createSaleWithProducts(array $products): Collection
    {
        $sale = Sale::create([]);
        $productSales = [];
        foreach ($products as $product) {
            $productSales[] = [
                'product_id' => $product['id'],
                'sale_id' => $sale->id,
                'product_amount' => $product['amount'],
                'created_at' => Carbon::now()
            ];
        }
        ProductSale::insert($productSales);
        return $this->saleQuery->getProductsFromSale($sale->id);
    }
}
