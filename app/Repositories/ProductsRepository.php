<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepository
{
    public function addToProducts(string $user, Product $product): void;
    public function getProducts(string $user): ProductsCollection;
    public function editExistingProduct(string $user, string $productToEdit, string $newProduct): void;
    public function deleteProduct(string $user, string $productToDelete): void;
    public function findByCategory(string $user, string $category): array;
}
