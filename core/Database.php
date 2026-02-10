<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $readPdo = null;
    private static ?PDO $writePdo = null;

    public static function connection(bool $write = false): PDO
    {
        return $write ? self::writeConnection() : self::readConnection();
    }

    public static function readConnection(): PDO
    {
        if (self::$readPdo instanceof PDO) {
            return self::$readPdo;
        }
        self::$readPdo = self::connect('read');
        return self::$readPdo;
    }

    public static function writeConnection(): PDO
    {
        if (self::$writePdo instanceof PDO) {
            return self::$writePdo;
        }
        self::$writePdo = self::connect('write');
        return self::$writePdo;
    }

    private static function connect(string $mode): PDO
    {
        $config = require __DIR__ . '/../config/database.php';
        $db = $config[$mode];
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $db['host'], $db['port'], $db['dbname'], $config['charset']);

        try {
            return new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', ['mode' => $mode, 'exception' => $e->getMessage()]);
            throw $e;
        }
    }
}
