<?php

namespace App\Repositories;

use App\Models\Product;
use PDO;
use PDOException;

class MysqlProductsRepository implements ProductsRepository
{

    public function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'product-catalog';
        $user = 'root';
        $pass = '123456';


        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        try {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getCategories(string $user): array
    {
        $sql = "SELECT user, category FROM categories";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        $userData = [];
        foreach ($allData as $data)
        {
            if ($data['user'] === $user)
            {
                array_push($userData, $data);
            }
        }
        return $userData;
    }

    public function addToProducts(string $user, Product $product): void
    {
        $sql = "INSERT INTO products (user, category, product) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $user,
            $product->getCategory(),
            $product->getName()
        ]);
    }

    public function addToCategories(string $user, string $category): void
    {
        $sql = "INSERT INTO categories (user, category) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$user, $category]);
    }

    public function getProducts(string $user): array
    {
        $sql = "SELECT user, product FROM products";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        $userData = [];
        foreach ($allData as $data)
        {
            if ($data['user'] === $user)
            {
                array_push($userData, $data);
            }
        }
        return $userData;
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

    public function find(string $user, string $category): array
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        $found = [];
        foreach ($allData as $data)
        {
            if ($data['user'] === $user && $data['category'] === $category)
            {
                array_push($found, $data['product']);
            }
        }
        return $found;
    }
}
