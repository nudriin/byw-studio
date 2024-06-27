<?php

namespace Sheilla\NailArt\Config;

use Dotenv\Dotenv;
use PDO;

class Database
{
    private static ?\PDO $connection = null;

    public static function getConnect(): \PDO
    {
        $doenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $doenv->load();
        if (self::$connection == null) {
            $host = "localhost";
            $port = 3306;
            $dbName = "kerupuk_basah_julak";
            $username = "root";
            $password = $_ENV["DB_PASS"];

            self::$connection = new \PDO("mysql:host=$host:$port;dbname=$dbName", $username, $password);
        }
        return self::$connection;
    }

    public static function beginTransaction()
    {
        self::$connection->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$connection->commit();
    }

    public static function rollbackTransaction()
    {
        self::$connection->rollBack();
    }
}
