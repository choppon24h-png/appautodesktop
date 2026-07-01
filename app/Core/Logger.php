<?php
namespace App\Core;

/**
 * Logger — Sistema de log do AppAuto
 *
 * Suporta chamada estática (Logger::info()) e de instância ($logger->info())
 * para compatibilidade com bootstrap (instância) e controllers (estático).
 *
 * Padrão: Singleton com métodos estáticos e de instância.
 */
class Logger
{
    private static ?Logger $instance = null;
    private string $logDir;

    public function __construct()
    {
        $this->logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
        // Registra esta instância como singleton
        self::$instance = $this;
    }

    /**
     * Retorna a instância singleton do Logger
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // =========================================================
    // Métodos ESTÁTICOS — usados nos Controllers
    // =========================================================

    public static function info(string $message, array $context = []): void
    {
        self::getInstance()->log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getInstance()->log('warning', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (($_ENV['APP_ENV'] ?? 'dev') !== 'prod') {
            self::getInstance()->log('debug', $message, $context);
        }
    }

    public static function error(string $message, array $context = []): void
    {
        self::getInstance()->log('error', $message, $context);
    }

    // =========================================================
    // Métodos de INSTÂNCIA — usados no bootstrap ($logger->xxx())
    // =========================================================

    public function auth(string $message, array $context = []): void
    {
        $this->log('auth', $message, $context);
    }

    public function bootstrap(string $message, array $context = []): void
    {
        $this->log('bootstrap', $message, $context);
    }

    public function router(string $message, array $context = []): void
    {
        $this->log('router', $message, $context);
    }

    public function view(string $message, array $context = []): void
    {
        $this->log('view', $message, $context);
    }

    /**
     * Alias de instância para error() — compatibilidade com bootstrap
     * O bootstrap usa $logger->error() (instância), controllers usam Logger::error() (estático)
     * Como error() é estático, o PHP permite chamar via instância também em PHP 8+
     * Este método garante compatibilidade explícita
     */
    public function logError(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    // =========================================================
    // Método privado de escrita no arquivo de log
    // =========================================================

    private function log(string $type, string $message, array $context = []): void
    {
        $timestamp  = date('Y-m-d H:i:s');
        $logFile    = $this->logDir . '/' . $type . '.log';
        $userId     = $_SESSION['user_id'] ?? '-';
        $ipAddress  = $_SERVER['REMOTE_ADDR'] ?? '-';
        $method     = $_SERVER['REQUEST_METHOD'] ?? '-';
        $uri        = $_SERVER['REQUEST_URI'] ?? '-';

        $logMessage  = "[{$timestamp}] ";
        $logMessage .= "[IP: {$ipAddress}] ";
        $logMessage .= "[User: {$userId}] ";
        $logMessage .= "[Method: {$method}] ";
        $logMessage .= "[URI: {$uri}] ";
        $logMessage .= "- {$message}";

        if (!empty($context)) {
            $logMessage .= ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $logMessage .= "\n";

        // Stack trace apenas em logs de erro
        if ($type === 'error' && function_exists('debug_backtrace')) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $logMessage .= "Stack Trace:\n";
            foreach ($trace as $index => $frame) {
                $file      = $frame['file'] ?? 'unknown';
                $line      = $frame['line'] ?? 'unknown';
                $function  = $frame['function'] ?? 'unknown';
                $class     = $frame['class'] ?? '';
                $typeFrame = $frame['type'] ?? '';
                $logMessage .= "  #{$index} {$class}{$typeFrame}{$function}() called at [{$file}:{$line}]\n";
            }
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}
