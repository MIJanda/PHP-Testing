<?php

namespace App;

class ProductsRepo 
{
    protected $pdo;

    protected function getPdo(): \PDO   
    {
        if ($this->pdo === null) {
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ];
        }

        try {

            $this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=phpunit-demo;charset=utf8mb4", '<username>', '<password>', $options);

        } catch (\PDOException $pdoException) {

            throw new \PDOException($pdoException->getMessage(), (int) $pdoException->getCode());

        }

        return $this->pdo;
    }

    // fetch array of products from the database
    public function fetchProducts(): array
    {
        return $this->getPdo()->prepare('SELECT * FROM products')->fetchAll(\PDO::FETCH_ASSOC);
    }
}