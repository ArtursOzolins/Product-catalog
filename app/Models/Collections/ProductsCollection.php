<?php

namespace App\Models\Collections;

use App\Models\Product;

class ProductsCollection
{
    private array $products = [];

    public function __construct(array $products = null)
    {
        foreach ($products as $product)
        {
            $this->addToProductsCollection($product);
        }
    }

    public function addToProductsCollection(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getProductsCollection(): array
    {
        return $this->products;
    }
}