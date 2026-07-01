<?php

namespace App\Core;

class Logger
{
    private string $logDir = __DIR__ . '/../../storage/logs';

    public function __construct()
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * Log de erro com stack trace
     */
    public function error(string $message, array $context = []): void
    {
        $this->log("error", $message, $context);
    }

    /**
     * Log de autenticação com detalhes
     */
    public function auth(string $message, array $context = []): void
    {
        $this->log("auth", $message, $context);
    }

    /**
     * Log de informação
     */
    public function info(string $message, array $context = []): void
    {
        $this->log("info", $message, $context);
    }

    /**
     * Log de debug (apenas em modo dev)
     */
    public function debug(string $message, array $context = []): void
    {
        if (($_ENV['APP_ENV'] ?? 'dev') !== 'prod') {
            $this->log("debug", $message, $context);
        }
    }

    /**
     * Log de inicialização do bootstrap
     */
    public function bootstrap(string $message, array $context = []): void
    {
        $this->log("bootstrap", $message, $context);
    }

    /**
     * Log de rota e dispatcher
     */
    public function router(string $message, array $context = []): void
    {
        $this->log("router", $message, $context);
    }

    /**
     * Log de view rendering
     */
    public function view(string $message, array $context = []): void
    {
        $this->log("view", $message, $context);
    }

    /**
     * Método privado para registrar logs
     */
    private function log(string $type, string $message, array $context = []): void
    {
        $timestamp = date("Y-m-d H:i:s");
        $logFile = $this->logDir . "/" . $type . ".log";
        $userId = $_SESSION["user_id"] ?? "-";
        $ipAddress = $_SERVER["REMOTE_ADDR"] ?? "-";
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "-";
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? "-";
        $requestUri = $_SERVER["REQUEST_URI"] ?? "-";

        $logMessage = "[{$timestamp}] ";
        $logMessage .= "[IP: {$ipAddress}] ";
        $logMessage .= "[User: {$userId}] ";
        $logMessage .= "[Method: {$requestMethod}] ";
        $logMessage .= "[URI: {$requestUri}] ";
        $logMessage .= "- {$message}";

        if (!empty($context)) {
            $logMessage .= " | Context: " . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $logMessage .= "\n";

        // Adiciona stack trace em caso de erro
        if ($type === 'error' && function_exists('debug_backtrace')) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $logMessage .= "Stack Trace:\n";
            foreach ($trace as $index => $frame) {
                $file = $frame['file'] ?? 'unknown';
                $line = $frame['line'] ?? 'unknown';
                $function = $frame['function'] ?? 'unknown';
                $class = $frame['class'] ?? '';
                $type_frame = $frame['type'] ?? '';
                $logMessage .= "  #{$index} {$class}{$type_frame}{$function}() called at [{$file}:{$line}]\n";
            }
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
