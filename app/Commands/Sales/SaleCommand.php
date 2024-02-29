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
        foreach ($products as $product) {
            $sale->handleProduct($product, $sale->id);
        }
        return $this->saleQuery->getProductsFromSale($sale->id);
    }

    public function addProductsToSale(array $products, int $saleId): Collection
    {
        $sale = $this->saleQuery->find($saleId);
        foreach ($products as $product) {
            $sale->handleProduct($product, $saleId);
        }
        return $this->saleQuery->getProductsFromSale($saleId);
    }

    public function deleteSale(int $saleId): void
    {
        Sale::where('id', $saleId)->delete();
    }
}
