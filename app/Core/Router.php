<?php

namespace App\Core;

use App\Middlewares\AuthMiddleware;

/**
 * Router — AppAuto SaaS
 *
 * Suporta:
 *  - Rotas estáticas:  /login, /dashboard
 *  - Rotas dinâmicas:  /veiculos/{id}, /admin/usuario/{id}
 *  - Grupos com middleware
 *  - PHP 7.4+ (sem str_ends_with nativo — usa substr())
 */
class Router
{
    /** @var array<string, array<string, array>> */
    protected static array $routes = [];

    /** @var string[] */
    protected static array $groupMiddleware = [];

    /** @var array<string, string> */
    protected static array $middlewares = [
        'Auth' => AuthMiddleware::class,
    ];

    /* =========================================================
     * Registro de Rotas
     * ========================================================= */

    public static function get(string $uri, string $action): void
    {
        self::$routes['GET'][$uri] = [
            'action'     => $action,
            'middleware' => self::$groupMiddleware,
        ];
    }

    public static function post(string $uri, string $action): void
    {
        self::$routes['POST'][$uri] = [
            'action'     => $action,
            'middleware' => self::$groupMiddleware,
        ];
    }

    /* =========================================================
     * Agrupamento com Middleware
     * ========================================================= */

    public static function group(array $options, callable $callback): void
    {
        self::$groupMiddleware = (array) ($options['middleware'] ?? []);
        $callback();
        self::$groupMiddleware = [];
    }

    /* =========================================================
     * Dispatcher
     * ========================================================= */

    public static function dispatch(): void
    {
        $logger = new Logger();

        try {
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $uri    = '/' . trim($uri, '/');
            if ($uri === '//') {
                $uri = '/';
            }

            $logger->router("Dispatch: {$method} {$uri}");

            // 1. Tenta match exato primeiro
            if (isset(self::$routes[$method][$uri])) {
                $route  = self::$routes[$method][$uri];
                $params = [];
                $logger->router("Match exato: {$route['action']}");
                self::execute($route, $params, $logger);
                return;
            }

            // 2. Tenta match com parâmetros dinâmicos {param}
            foreach (self::$routes[$method] as $pattern => $route) {
                if (strpos($pattern, '{') === false) {
                    continue; // pula rotas sem parâmetros
                }

                $params = [];
                if (self::matchDynamic($pattern, $uri, $params)) {
                    $logger->router("Match dinâmico: {$route['action']} | params: " . json_encode($params));
                    self::execute($route, $params, $logger);
                    return;
                }
            }

            // 3. Rota não encontrada
            $logger->router("404: {$method} {$uri}");
            http_response_code(404);
            echo "<!DOCTYPE html><html><body><h1>404 — Página não encontrada</h1>"
               . "<p><a href='/'>Voltar ao início</a></p></body></html>";

        } catch (\Exception $e) {
            $logger->error("Erro no dispatch: " . $e->getMessage() . " em " . $e->getFile() . ':' . $e->getLine());

            if (($_ENV['APP_ENV'] ?? 'dev') === 'prod') {
                http_response_code(500);
                echo "<!DOCTYPE html><html><body><h1>Erro interno</h1>"
                   . "<p>Tente novamente em instantes.</p></body></html>";
            } else {
                http_response_code(500);
                echo "<h1>Erro no Router</h1>";
                echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p><strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . "</p>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
        }
    }

    /* =========================================================
     * Match de rota dinâmica
     * ========================================================= */

    /**
     * Converte /veiculos/{id} em regex e extrai parâmetros.
     *
     * @param string $pattern  Padrão da rota (ex: /veiculos/{id})
     * @param string $uri      URI real da requisição
     * @param array  &$params  Parâmetros extraídos (ex: ['id' => '42'])
     */
    protected static function matchDynamic(string $pattern, string $uri, array &$params): bool
    {
        // Extrai nomes dos parâmetros
        preg_match_all('/\{(\w+)\}/', $pattern, $names);
        $paramNames = $names[1];

        // Converte o padrão em regex
        $regex = preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $uri, $matches)) {
            return false;
        }

        // Remove o match completo (índice 0)
        array_shift($matches);

        // Mapeia nomes → valores
        foreach ($paramNames as $i => $name) {
            $params[$name] = $matches[$i] ?? null;
        }

        return true;
    }

    /* =========================================================
     * Execução de rota (middlewares + controller)
     * ========================================================= */

    /**
     * @param array $route   ['action' => 'Controller@method', 'middleware' => [...]]
     * @param array $params  Parâmetros dinâmicos extraídos da URI
     */
    protected static function execute(array $route, array $params, Logger $logger): void
    {
        // Executa middlewares
        foreach ($route['middleware'] as $middleware) {
            self::runMiddleware($middleware, $logger);
        }

        // Resolve Controller@method
        [$controllerName, $actionName] = explode('@', $route['action']);
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller não encontrado: {$controllerClass}");
        }

        $instance = new $controllerClass();

        if (!method_exists($instance, $actionName)) {
            throw new \Exception("Método '{$actionName}' não encontrado em {$controllerClass}");
        }

        $logger->router("Executando: {$controllerClass}@{$actionName}");

        // Injeta parâmetros dinâmicos via $_GET para compatibilidade
        foreach ($params as $key => $value) {
            $_GET[$key] = $value;
        }

        // Chama o método — se aceitar parâmetros, passa como argumento
        $reflection = new \ReflectionMethod($instance, $actionName);
        if ($reflection->getNumberOfParameters() > 0 && !empty($params)) {
            call_user_func_array([$instance, $actionName], array_values($params));
        } else {
            call_user_func([$instance, $actionName]);
        }
    }

    /* =========================================================
     * Execução de Middleware
     * ========================================================= */

    protected static function runMiddleware(string $middleware, Logger $logger): void
    {
        if (!isset(self::$middlewares[$middleware])) {
            throw new \Exception("Middleware '{$middleware}' não registrado.");
        }

        $middlewareClass = self::$middlewares[$middleware];
        $logger->router("Middleware: {$middlewareClass}");
        (new $middlewareClass())->handle();
    }
}
