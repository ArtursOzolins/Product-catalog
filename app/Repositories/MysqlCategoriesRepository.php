<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Collections\CategoriesCollection;
use PDO;
use PDOException;

class MysqlCategoriesRepository implements CategoriesRepository
{
    private CategoriesCollection $collection;
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
        $this->collection = new CategoriesCollection();
    }


    public function getCategories(string $user): CategoriesCollection
    {
        $sql = "SELECT user, category FROM categories WHERE user = '$user'";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();


        foreach ($allData as $data)
        {
            $this->collection->addToCategoriesCollection(new Category($data['user'], $data['category']));
        }

        return $this->collection;
    }

    public function addToCategories(string $user, string $category): void
    {
        $sql = "INSERT INTO categories (user, category) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$user, $category]);
    }
}
