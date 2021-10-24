<?php

namespace App\Repositories;

use App\Models\Collections\CategoriesCollection;

interface CategoriesRepository
{
    public function getCategories(string $user): CategoriesCollection;
    public function addToCategories(string $user, string $category): void;
}
