<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function handleProduct(array $product, int $saleId): void
    {
        $existingProductSale = $this->products()->where('product_id', $product['id'])->first();

        if ($existingProductSale) {
            $existingProductSale->pivot->product_amount += $product['amount'];
            $existingProductSale->pivot->updated_at = Carbon::now();
            $existingProductSale->pivot->save();
        } else {
            $this->products()->attach($product['id'], [
                'product_amount' => $product['amount'],
                'created_at' => Carbon::now()
            ]);
        }
    }
}
