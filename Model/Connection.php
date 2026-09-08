<?php
declare(strict_types=1);

namespace Model;

use PDO;
use PDOException;

require_once __DIR__ . '/../Config/configuration.php';

class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die('Não foi possível conectar ao MySQL. Verifique Config/configuration.php e se o MySQL do XAMPP está iniciado.');
            }
        }

        return self::$instance;
    }
}
