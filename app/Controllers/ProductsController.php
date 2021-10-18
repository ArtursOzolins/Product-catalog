<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\ProductsRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ProductsController
{

    private Environment $twig;
    private ProductsRepository $repository;
    private string $user;

    public function __construct()
    {
        $this->user = $_SESSION['user'];
        $this->repository = new MysqlProductsRepository();

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        $categories = $this->repository->getCategories($this->user);
        $tags = $this->repository->getTags();
        $products = $this->repository->getProducts($this->user);

        echo $this->twig->render('products/index.template.html', array(
            'categories' => $categories,
            'products' => $products,
            'tags' => $tags,
            'user' => $this->user
        ));
    }

    public function showCategories()
    {
        $categories = $this->repository->getCategories($this->user);
        echo $this->twig->render('products/add/category.template.html', array('categories' => $categories));
    }

    public function addCategory()
    {
        echo $this->twig->render('products/add/add-category.template.html');
    }

    public function createCategory()
    {
        $this->repository->addToCategories($_SESSION['user'], $_POST['category']);
        header('Location: /products/categories');
    }

    public function addProduct()
    {
        echo $this->twig->render('products/add/add-product.template.html', ['category' => $_POST['category']]);
    }

    public function addTag()
    {
        $tags = $this->repository->getTags();
        echo $this->twig->render('products/add/tag.template.html', [
            'category' => $_POST['category'],
            'product' => $_POST['product'],
            'tags' => $tags
        ]);
    }

    public function createData()
    {

       $this->repository->addToProducts($this->user, new Product($_POST['category'], $_POST['product']));
       foreach ($_POST['checked_tag_id'] as $id)
       {
           $this->repository->addToTagMap(new Product($_POST['category'], $_POST['product']), $id);
       }
       header('Location: /products');
    }

    public function editProduct(string $input)
    {
        echo $this->twig->render('products/edit/edit-product.template.html', array('product' => $input));
    }

    public function saveEditedProduct(): void
    {
        $this->repository->editExistingProduct($this->user, $_POST['productToEdit'], $_POST['newproduct']);
        header('Location: /products');
    }

    public function deleteProduct(string $product): void
    {
        $this->repository->deleteProduct($this->user, $product);
        header('Location: /products');
    }

    public function searchByCategory()
    {
        $products = $this->repository->findByCategory($_POST['user'], $_POST['category']);

        echo $this->twig->render('products/found-products.template.html', ['products' => $products]);
    }

    public function searchByTag()
    {
        $productsByTag = $this->repository->findByTag($_POST['tag_id']);
        $productsByUser = $this->repository->getProducts($_POST['user']);
        $products = [];
        foreach ($productsByTag as $product)
        {
            if (array_search($product, $productsByUser) !== false)
            {
                array_push($products, $product);
            }
        }

        echo $this->twig->render('products/found-products.template.html', ['products' => $products]);
    }

}