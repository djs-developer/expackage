<?php

namespace djs\expackage;
use djs\expackage\Database;
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
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS user_role (
                role_id INT AUTO_INCREMENT PRIMARY KEY,
                userrole VARCHAR(255) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS permission (
                permission_id INT AUTO_INCREMENT PRIMARY KEY,
                permission VARCHAR(255) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS userrole_mapping (
                role_mapping_id INT AUTO_INCREMENT PRIMARY KEY,
                role_id INT NOT NULL,
                user_id INT NOT NULL,
                FOREIGN KEY (role_id) REFERENCES user_role(role_id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS role_permission (
                id INT AUTO_INCREMENT PRIMARY KEY,
                role_mapping_id INT NOT NULL,
                permission_id INT NOT NULL,
                FOREIGN KEY (role_mapping_id) REFERENCES userrole_mapping(role_mapping_id) ON DELETE CASCADE,
                FOREIGN KEY (permission_id) REFERENCES permission(permission_id) ON DELETE CASCADE
            )"
            // Add more table creation queries here
        ];

        foreach ($queries as $query) {
            $this->pdo->exec($query);
        }
    }

        public static function setup()
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $host = 'localhost';
        $dbname = 'phpownpackage';
        $user = 'root';
        $password = '1234';

        $database = new Database($host, $dbname, $user, $password);
        $installer = new self($database);

        $installer->createTables();
        echo "Database and tables created successfully.";
    }

}

