<?php
/**
 * Database Connection Manager (PDO)
 * Automatically connects to SQLite or MySQL depending on config
 */

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/config.php';
            $dbConfig = $config['database'];
            $driver = $dbConfig['driver'];

            try {
                if ($driver === 'mysql') {
                    $mysql = $dbConfig['mysql'];
                    $dsn = "mysql:host={$mysql['host']};port={$mysql['port']};dbname={$mysql['database']};charset={$mysql['charset']}";
                    self::$instance = new PDO($dsn, $mysql['username'], $mysql['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                } else {
                    // SQLite fallback
                    $sqlitePath = $dbConfig['sqlite']['path'];
                    $dir = dirname($sqlitePath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    self::$instance = new PDO("sqlite:" . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    // Enable WAL mode & foreign keys for SQLite
                    self::$instance->exec("PRAGMA journal_mode = WAL;");
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                }
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
