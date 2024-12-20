<?php

namespace Djs\Expackage;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct($host, $dbname, $user, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        // Create the database if it doesn't exist
        $this->createDatabase($dbname);
    }

    private function createDatabase($dbname)
    {
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $this->pdo->exec("USE `$dbname`");
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
