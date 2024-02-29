<?php

namespace App\Queries\Sales;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleQuery
{
    public function find(int $saleId): ?Sale
    {
        return Sale::findOrFail($saleId);
    }
    public function getProductsFromSale(?int $saleId = null): Collection
    {
        $query = Sale::with(['products' => function ($query) {
            $query->select('products.id', 'name', 'price', 'product_sales.product_amount as amount');
        }]);

        if ($saleId !== null) {
            $query->where('id', $saleId);
        }

        $sales = $query->get();

        $sales->transform(function ($sale) {
            $sale->amount = $sale->calculateTotalAmount();
            return $sale;
        });

        return $sales;
    }
}
