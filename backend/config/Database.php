<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $configFile = __DIR__ . '/config.php';
        if (!is_file($configFile)) {
            throw new RuntimeException('Missing backend/config/config.php. Copy config.example.php and set database credentials.');
        }

        $config = require $configFile;
        $db = $config['database'] ?? [];
        foreach (['host', 'name', 'username', 'password'] as $key) {
            if (!array_key_exists($key, $db)) {
                throw new RuntimeException("Database configuration is missing: {$key}");
            }
        }

        $port = (int)($db['port'] ?? 3306);
        $charset = $db['charset'] ?? 'utf8mb4';
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $port, $db['name'], $charset);
        self::$connection = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return self::$connection;
    }
}
