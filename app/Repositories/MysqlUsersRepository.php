<?php

namespace App\Repositories;

use App\Models\User;
use PDO;
use PDOException;


class MysqlUsersRepository implements UsersRepository
{
    private PDO $connection;

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

    public function registrate(User $user): void
    {
        $sql = "INSERT INTO users (user, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $user->getUserLogin(),
            $user->getUserPassword()
        ]);
    }

    public function getOne(string $userName): ?User
    {
        $sql = "SELECT * FROM users WHERE user = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$userName]);

        $result = $stmt->fetch();
        if (isset($result['user']))
        {
            $user = new User($result['user'], $result['password']);
        }
        return $user;
    }
}
