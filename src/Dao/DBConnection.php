<?php
namespace App\Dao;

use PDO;
use PDOException;

class DBConnection
{
    public static function create(string $host, string $dbname, string $user, string $pass, 
                                  string $charset = 'utf8mb4', ?Logger $logger = null): PDO
    {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            if ($logger) {
                $logger->error('DB Connection failed: ' . $e->getMessage());
            }
            throw $e;
        }
    }
}