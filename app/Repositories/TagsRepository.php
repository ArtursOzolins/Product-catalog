<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Product;

interface TagsRepository
{
    public function getTags(): TagsCollection;
    public function addToTagMap(Product $product, string $tag_id): void;
    public function findByTag(string $tag_id): array;
}
