<?php

namespace Djs\Expackage;

class Installer
{
    private $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getPdo();
    }

    public function createTables()
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            // Add more table creation queries here
        ];

        foreach ($queries as $query) {
            $this->pdo->exec($query);
        }
    }

        public static function setup()
    {
        $host = 'localhost';
        $dbname = 'my_project';
        $user = 'root';
        $password = '1234';

        $database = new Database($host, $dbname, $user, $password);
        $installer = new self($database);

        $installer->createTables();
        echo "Database and tables created successfully.";
    }

}

