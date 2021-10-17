<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductsRepository
{
    public function getCategories(string $user): array;
    public function addToProducts(string $user, Product $product): void;
    public function addToCategories(string $user, string $category): void;
    public function getProducts(string $user): array;
    public function editExistingProduct(string $user, string $productToEdit, string $newProduct): void;
    public function deleteProduct(string $user, string $productToDelete): void;
    public function find(string $user, string $category): array;
}
