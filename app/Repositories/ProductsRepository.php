<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductsRepository
{
    public function getCategories(string $user): array;
    public function addToProducts(string $user, Product $product): void;
    public function addToTagMap(Product $product, string $tag_id): void;
    public function addToCategories(string $user, string $category): void;
    public function getProducts(string $user): array;
    public function getTags(): array;
    public function editExistingProduct(string $user, string $productToEdit, string $newProduct): void;
    public function deleteProduct(string $user, string $productToDelete): void;
    public function findByCategory(string $user, string $category): array;
    public function findByTag(string $tag_id): array;
}
