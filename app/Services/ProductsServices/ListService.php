<?php

namespace App\Services\ProductsServices;

use App\Repositories\CategoriesRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\TagsRepository;

class ListService
{
    private string $user;

    private ProductsRepository $productsRepository;
    private CategoriesRepository $categoriesRepository;
    private TagsRepository $tagsRepository;
    private array $productsCollection;
    private array $categoriesCollection;
    private array $tagsCollection;

    public function __construct($container)
    {
        $this->user = $_SESSION['user'];

        $this->productsRepository = $container->get(ProductsRepository::class);
        $this->categoriesRepository = $container->get(CategoriesRepository::class);
        $this->tagsRepository = $container->get(TagsRepository::class);

        $this->productsCollection = $this->productsRepository->getProducts($this->user)->getProductsCollection();
        $this->categoriesCollection = $this->categoriesRepository->getCategories($this->user)->getCategoriesCollection();
        $this->tagsCollection = $this->tagsRepository->getTags()->getTagsCollection();
    }

    public function getTags(): array
    {
        return $this->tagsCollection;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getProductsCollection(): array
    {
        return $this->productsCollection;
    }

    public function getCategoriesCollection(): array
    {
        return $this->categoriesCollection;
    }

    public function findByCategoryFromRepository(string $user, string $category): array
    {
        return $this->productsRepository->findByCategory($user, $category);
    }

    public function findByTagFromRepository(string $tag_id): array
    {
        return $this->tagsRepository->findByTag($tag_id);
    }
}
