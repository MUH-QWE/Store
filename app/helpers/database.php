<?php


namespace App\Helpers;

use PDO;
use PDOException;

class Database {
    private static $pdo = null;

    public static function connect($dbConfig) {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
                self::$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if (php_sapi_name() !== 'cli') {
                    header('Content-Type: application/json', true, 500);
                    echo json_encode([
                        'error' => 'Database Sync Failed',
                        'details' => $e->getMessage()
                    ]);
                    exit;
                }
                die("Database Connection Error: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    
    public static function getConnection() {
        return self::$pdo;
    }
}
