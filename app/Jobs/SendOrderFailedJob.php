<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderFailedJob implements ShouldQueue
{
    use Queueable;
    public array $productData;

    public function __construct(array $productData = [])
    {
        $this->productData = $productData;
    }

    public function handle(): void
    {
        $productId = $this->productData['product_id'] ?? null;
        $quantity = $this->productData['quantity'] ?? null;

        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $product->stock += $quantity;

        $product->save();
    }
}
