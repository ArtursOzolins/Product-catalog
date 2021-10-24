<?php

namespace App\Controllers;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\TagsRepository;
use App\Validations\ProductValidation;
use Exception;
use InvalidArgumentException;
use LengthException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ProductsController
{

    private Environment $twig;
    private ProductsRepository $productsRepository;
    private CategoriesRepository $categoriesRepository;
    private TagsRepository $tagsRepository;
    private string $user;
    private ProductValidation $productValidation;
    private array $productsCollection;
    private array $categoriesCollection;
    private array $tagsCollection;

    public function __construct($container)
    {
        $this->user = $_SESSION['user'];
        $this->productsRepository = $container->get(ProductsRepository::class);
        $this->categoriesRepository = $container->get(CategoriesRepository::class);
        $this->tagsRepository = $container->get(TagsRepository::class);

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
        $this->productValidation = new ProductValidation();

        $this->productsCollection = $this->productsRepository->getProducts($this->user)->getProductsCollection();
        $this->categoriesCollection = $this->categoriesRepository->getCategories($this->user)->getCategoriesCollection();
        $this->tagsCollection = $this->tagsRepository->getTags()->getTagsCollection();
    }

    public function index()
    {
        $tags = $this->productsRepository->getTags();

        echo $this->twig->render('products/index.template.html', [
            'productsCollection' => $this->productsCollection,
            'categoriesCollection' => $this->categoriesCollection,
            'tags' => $tags,
            'user' => $this->user
        ]);
    }

    public function showUserCategories()
    {
        echo $this->twig->render('products/add/category.template.html', [
            'error' => $_GET['error'],
            'categories' => $this->categoriesCollection
        ]);
    }

    public function addUserCategory()
    {
        echo $this->twig->render('products/add/add-category.template.html');
    }

    public function createUserCategory()
    {
        try {
            $this->productValidation->validateNewProduct($_POST['category']);
            $this->categoriesRepository->addToCategories($this->user, $_POST['category']);
            header('Location: /products/categories');
        } catch (LengthException $e)
        {
            header('Location: /products/categories?error=' . $e->getMessage());
        }
    }

    public function addProduct()
    {
        try {
            $this->productValidation->validateNewProduct($_POST['category']);
            echo $this->twig->render('products/add/add-product.template.html', [
                'category' => $_POST['category']
            ]);
        } catch (LengthException $e) {
            header('Location: /products/categories?error=' . $e->getMessage());
        }
    }

    public function addTag()
    {
        try {
            $this->productValidation->validateNewProduct($_POST['product']);
            echo $this->twig->render('products/add/tag.template.html', [
                'category' => $_POST['category'],
                'product' => $_POST['product'],
                'tags' => $this->tagsCollection
            ]);
        } catch (LengthException $e) {
            header('Location: /products/categories?error=' . $e->getMessage());
        }

    }

    public function createData()
    {
        try {
            $this->productValidation->validateNewProduct($_POST['checked_tag_id'][0]);
            $this->productsRepository->addToProducts($this->user, new Product($_POST['category'], $_POST['product']));
            foreach ($_POST['checked_tag_id'] as $tag) {
                $this->tagsRepository->addToTagMap(new Product($_POST['category'], $_POST['product']), $tag);
            }
            header('Location: /products');
        } catch (LengthException $e) {
            header('Location: /products/categories?error=' . $e->getMessage());
        }

    }

    public function editProduct(string $input)
    {
        echo $this->twig->render('products/edit/edit-product.template.html', array('product' => $input));
    }

    public function saveEditedProduct(): void
    {
        $this->productsRepository->editExistingProduct($this->user, $_POST['productToEdit'], $_POST['newproduct']);
        header('Location: /products');
    }

    public function deleteProduct(string $product): void
    {
        $this->productsRepository->deleteProduct($this->user, $product);
        header('Location: /products');
    }

    public function searchByCategory()
    {
        $products = $this->productsRepository->findByCategory($_POST['user'], $_POST['category']);

        echo $this->twig->render('products/found-products.template.html', ['products' => $products]);
    }

    public function searchByTag()
    {
        $productsByTag = $this->tagsRepository->findByTag($_POST['tag_id']);
        $userProductCollection = $this->productsRepository->getProducts($_POST['user'])->getProductsCollection();
        $productsByUser = [];
        foreach ($userProductCollection as $product)
        {
            $productsByUser[] = $product->getName();
        }
        $products = [];
        foreach ($productsByTag as $product)
        {
            if (array_search($product, $productsByUser) !== false)
            {
                $products[] = $product;
            }
        }

        echo $this->twig->render('products/found-products.template.html', ['products' => $products]);
    }

}