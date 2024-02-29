<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sales')->withPivot('product_amount');
    }

    public function calculateTotalAmount(): float
    {
        return round($this->products->reduce(function ($total, $product) {
            return $total + ($product->price * $product->amount);
        }, 0), 2);
    }
}
