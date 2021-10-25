<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use LengthException;
use PDO;
use PDOException;

class MysqlProductsRepository implements ProductsRepository
{
    private ProductsCollection $collection;
    public function __construct()
    {
        require 'app/config.php';
        $host = $config['DB_HOST'];
        $db   = $config['DB_DATABASE'];
        $user = $config['DB_USERNAME'];
        $pass = $config['DB_PASSWORD'];


        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        try {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
        $this->collection = new ProductsCollection();
    }

    public function getProducts(string $user): ProductsCollection
    {
        $sql = "SELECT category, product FROM products WHERE user = '$user'";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        foreach ($allData as $data)
        {
            $this->collection->addToProductsCollection(new Product($data['category'], $data['product']));
        }

        return $this->collection;
    }


    public function addToProducts(string $user, Product $product): void
    {
        $sql = "INSERT INTO products (user, category, product) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $user,
            $product->getCategory(),
            $product->getName(),
        ]);
    }

    public function editExistingProduct(string $user, string $productToEdit, string $newProduct): void
    {
        $data = [
            'user' => $user,
            'productToEdit' => $productToEdit,
            'newProduct' => $newProduct
        ];

        $sql = "UPDATE products SET product=:newProduct WHERE user=:user AND product=:productToEdit";
        $stmt= $this->connection->prepare($sql);
        $stmt->execute($data);
    }

    public function deleteProduct(string $user, string $productToDelete): void
    {
        $data = [
            'user' => $user,
            'productToDelete' => $productToDelete
        ];

        $sql = "DELETE FROM products WHERE user=:user AND product=:productToDelete";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
    }

    public function findByCategory(string $user, string $category): array
    {
        $sql = "SELECT product FROM products WHERE user = '$user' AND category = '$category'";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        $found = [];
        foreach ($allData as $data)
        {
                array_push($found, $data['product']);
        }
        return $found;
    }
}
