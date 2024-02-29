<?php

namespace App\Queries\Products;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductQuery
{
    public function getAllProducts(): Collection
    {
        return Product::all();
    }
}
