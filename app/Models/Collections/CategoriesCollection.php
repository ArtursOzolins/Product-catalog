<?php


namespace App\Models\Collections;

use App\Models\Category;

class CategoriesCollection
{
    private array $categories = [];

    public function __construct(array $categories = null)
    {
        foreach ($categories as $category) {
            $this->addToCategoriesCollection($category);
        }
    }

    public function addToCategoriesCollection(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function getCategoriesCollection(): array
    {
        return $this->categories;
    }
}
