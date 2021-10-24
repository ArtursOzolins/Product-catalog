<?php

namespace App\Models;

class Category
{
    private string $user;
    private string $category;

    public function __construct(string $user, string $category)
    {
        $this->user = $user;
        $this->category = $category;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
