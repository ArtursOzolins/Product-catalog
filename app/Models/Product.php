<?php

namespace App\Models;

class Product
{
    private string $category;
    private string $name;

    public function __construct(string $category, string $name)
    {
        $this->category = $category;
        $this->name = $name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }
}