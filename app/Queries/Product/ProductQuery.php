<?php

namespace App\Queries\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductQuery
{
    public function getAllProducts(): Collection
    {
        return Product::all();
    }
}
