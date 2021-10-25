<?php

namespace App\Controllers;
;
use App\Models\Product;
use App\Services\ProductsServices\ListService;
use App\Services\ProductsServices\RequestService;
use App\Validations\ProductValidation;
use Exception;
use InvalidArgumentException;
use LengthException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ProductsController
{
    private Environment $twig;
    private ProductValidation $productValidation;
    private RequestService $requestService;
    private ListService $listService;

    public function __construct($container)
    {
        $this->requestService = new RequestService($container);
        $this->listService = new ListService($container);

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
        $this->productValidation = new ProductValidation();
        $this->listService = new ListService($container);
    }

    public function index()
    {
        $tags = $this->listService->getTags();

        echo $this->twig->render('products/index.template.html', [
            'productsCollection' => $this->listService->getProductsCollection(),
            'categoriesCollection' => $this->listService->getCategoriesCollection(),
            'tags' => $tags,
            'user' => $this->listService->getUser()
        ]);
    }

    public function showUserCategories()
    {
        echo $this->twig->render('products/add/category.template.html', [
            'error' => $_GET['error'],
            'categories' => $this->listService->getCategoriesCollection()
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
            $this->requestService->addToCategoriesRepository($this->listService->getUser(), $_POST['category']);
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
                'tags' => $this->listService->getTags()
            ]);
        } catch (LengthException $e) {
            header('Location: /products/categories?error=' . $e->getMessage());
        }

    }

    public function createData()
    {
        try {
            $this->productValidation->validateNewProduct($_POST['checked_tag_id'][0]);
            $this->requestService->addToProductsRepository($this->listService->getUser(), new Product($_POST['category'], $_POST['product']));
            foreach ($_POST['checked_tag_id'] as $tag_id) {
                $this->requestService->addToTagMapRepository(new Product($_POST['category'], $_POST['product']), $tag_id);
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
        $this->requestService->editExistingProductInRepository($this->listService->getUser(), $_POST['productToEdit'], $_POST['newproduct']);
        header('Location: /products');
    }

    public function deleteProduct(string $product): void
    {
        $this->requestService->deleteProductInRepository($this->listService->getUser(), $product);
        header('Location: /products');
    }

    public function searchByCategory()
    {
        $products = $this->listService->findByCategoryFromRepository($_POST['user'], $_POST['category']);

        echo $this->twig->render('products/found-products.template.html', ['products' => $products]);
    }

    public function searchByTag()
    {
        $productsByTag = $this->listService->findByTagFromRepository($_POST['tag_id']);
        $userProductCollection = $this->listService->getProductsCollection();
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