<?php
// PDO database connection - singleton so we only connect once

class Database {

    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST
                 . ";dbname=" . DB_NAME
                 . ";charset=utf8mb4";

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log("DB Connection Error: " . $e->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    private function __clone() {}
}
