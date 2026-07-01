<?php

namespace App\Core;

use Exception;
use App\Middlewares\AuthMiddleware;

class Router
{
    protected static array $routes = [];
    protected static array $groupMiddleware = [];

    // Registro global de middlewares
    protected static array $middlewares = [
        'Auth' => AuthMiddleware::class,
    ];

    /* =========================
     * Registro de Rotas
     * ========================= */

    public static function get(string $uri, string $action): void
    {
        self::$routes['GET'][$uri] = [
            'action' => $action,
            'middleware' => self::$groupMiddleware
        ];
    }

    public static function post(string $uri, string $action): void
    {
        self::$routes['POST'][$uri] = [
            'action' => $action,
            'middleware' => self::$groupMiddleware
        ];
    }

    /* =========================
     * Agrupamento com Middleware
     * ========================= */

    public static function group(array $options, callable $callback): void
    {
        self::$groupMiddleware = (array) ($options['middleware'] ?? []);
        $callback();
        self::$groupMiddleware = [];
    }

    /* =========================
     * Dispatcher com Try-Catch Global
     * ========================= */

    public static function dispatch(): void
    {
        $logger = new Logger();
        
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            // Normaliza barra final
            if ($uri !== '/' && str_ends_with($uri, '/')) {
                $uri = rtrim($uri, '/');
            }

            $logger->router("Dispatch iniciado", [
                'method' => $method,
                'uri' => $uri
            ]);

            if (!isset(self::$routes[$method][$uri])) {
                $logger->router("Rota não encontrada", [
                    'method' => $method,
                    'uri' => $uri
                ]);
                http_response_code(404);
                echo "404 - Página não encontrada";
                return;
            }

            $route = self::$routes[$method][$uri];

            $logger->router("Rota encontrada", [
                'action' => $route['action'],
                'middleware_count' => count($route['middleware'])
            ]);

            // Executa middlewares
            foreach ($route['middleware'] as $middleware) {
                $logger->router("Executando middleware", ['middleware' => $middleware]);
                self::runMiddleware($middleware);
            }

            // Executa Controller@method
            [$controller, $action] = explode('@', $route['action']);
            $controllerClass = "App\\Controllers\\{$controller}";

            $logger->router("Instanciando controller", [
                'controller' => $controllerClass,
                'action' => $action
            ]);

            if (!class_exists($controllerClass)) {
                throw new Exception("Controller {$controllerClass} não encontrado");
            }

            $instance = new $controllerClass();

            if (!method_exists($instance, $action)) {
                throw new Exception("Método {$action} não encontrado em {$controllerClass}");
            }

            $logger->router("Executando ação do controller", [
                'controller' => $controllerClass,
                'action' => $action
            ]);

            call_user_func([$instance, $action]);

            $logger->router("Ação do controller concluída com sucesso", [
                'controller' => $controllerClass,
                'action' => $action
            ]);

        } catch (Exception $e) {
            $logger->error("Erro no dispatch", [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Em produção, exibe mensagem amigável
            if (($_ENV['APP_ENV'] ?? 'dev') === 'prod') {
                http_response_code(500);
                echo "Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.";
            } else {
                // Em desenvolvimento, exibe detalhes do erro
                http_response_code(500);
                echo "<h1>Erro no Router</h1>";
                echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p><strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
        }
    }

    /* =========================
     * Execução de Middleware
     * ========================= */

    protected static function runMiddleware(string $middleware): void
    {
        if (!isset(self::$middlewares[$middleware])) {
            throw new Exception("Middleware '{$middleware}' não encontrado.");
        }

        $middlewareClass = self::$middlewares[$middleware];
        (new $middlewareClass())->handle();
    }
}
