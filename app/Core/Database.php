<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database — AppAuto SaaS
 *
 * Singleton PDO compatível com MySQL 5.7 / MariaDB / Hostgator.
 * Usa utf8 (não utf8mb4) para compatibilidade máxima com MySQL 5.7.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';

            $host     = $config['host']     ?? 'localhost';
            $port     = $config['port']     ?? 3306;
            $dbname   = $config['database'] ?? '';
            $charset  = $config['charset']  ?? 'utf8';
            $username = $config['username'] ?? '';
            $password = $config['password'] ?? '';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
                // Necessário para MySQL 5.7 / Hostgator
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'",
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                $logger = new Logger();
                $logger->error("Falha na conexão com o banco de dados: " . $e->getMessage());

                http_response_code(500);
                if (($_ENV['APP_ENV'] ?? 'dev') === 'prod') {
                    die("<!DOCTYPE html><html><body><h1>Erro de conexão</h1>"
                      . "<p>Não foi possível conectar ao banco de dados. Verifique o arquivo .env.</p>"
                      . "</body></html>");
                } else {
                    die("<h1>Erro de Conexão com o Banco de Dados</h1>"
                      . "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>"
                      . "<p><strong>DSN:</strong> " . htmlspecialchars($dsn) . "</p>"
                      . "<p>Verifique as configurações no arquivo <code>.env</code></p>");
                }
            }
        }

        return self::$instance;
    }
}
