<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Models\Tag;
use PDO;
use PDOException;

class MysqlTagsRepository implements TagsRepository
{
    private TagsCollection $collection;
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
    }

    public function getTags(): TagsCollection
    {
        $sql = "SELECT tag_id, tag FROM tags";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();

        return $this->collection = new TagsCollection($allData);
    }

    public function addToTagMap(Product $product, string $tag_id): void
    {
        $sql = "INSERT INTO tag_map (product, tag_id) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $product->getName(),
            $tag_id
        ]);
    }

    public function findByTag(string $tag_id): array
    {
        $sql = "SELECT product FROM tag_map WHERE tag_id = '$tag_id'";
        $stmt = $this->connection->query($sql);
        $allData = $stmt->fetchAll();
        $found = [];
        foreach ($allData as $data)
        {
            $found[] = $data['product'];
        }
        return $found;
    }
}