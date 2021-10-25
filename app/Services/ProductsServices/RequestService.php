<?php

namespace App\Services\ProductsServices;

use App\Models\Product;
use App\Repositories\CategoriesRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\TagsRepository;

class RequestService
{
    private ProductsRepository $productsRepository;
    private CategoriesRepository $categoriesRepository;
    private TagsRepository $tagsRepository;

    public function __construct($container)
    {
        $this->productsRepository = $container->get(ProductsRepository::class);
        $this->categoriesRepository = $container->get(CategoriesRepository::class);
        $this->tagsRepository = $container->get(TagsRepository::class);
    }

    public function addToCategoriesRepository(string $user, string $category)
    {
        $this->categoriesRepository->addToCategories($user, $category);
    }

    public function addToProductsRepository(string $user, Product $product)
    {
        $this->productsRepository->addToProducts($user, $product);
    }

    public function addToTagMapRepository(Product $product, string $tag_id)
    {
        $this->tagsRepository->addToTagMap($product, $tag_id);
    }

    public function editExistingProductInRepository(string $user, string $productToEdit, string $newProduct)
    {
        $this->productsRepository->editExistingProduct($user, $productToEdit, $newProduct);
    }

    public function deleteProductInRepository(string $user, string $productToDelete)
    {
        $this->productsRepository->deleteProduct($user, $productToDelete);
    }
}
