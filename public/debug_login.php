<?php
// Diagnóstico rápido - remover após uso
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnóstico AppAuto - Login</h2>";
echo "<p>PHP: " . PHP_VERSION . "</p>";

// Testar autoload
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
echo "<p>Vendor autoload: " . ($autoload) . " - " . (file_exists($autoload) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";

// Testar .env
$env = dirname(__DIR__) . '/.env';
echo "<p>.env: " . ($env) . " - " . (file_exists($env) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";

// Testar view login
$login = dirname(__DIR__) . '/app/Views/auth/login.php';
echo "<p>login.php: " . ($login) . " - " . (file_exists($login) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";

// Testar public_header
$header = dirname(__DIR__) . '/app/Views/layout/public_header.php';
echo "<p>public_header.php: " . ($header) . " - " . (file_exists($header) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";

// Testar Controller base
$ctrl = dirname(__DIR__) . '/app/Core/Controller.php';
echo "<p>Controller.php: " . ($ctrl) . " - " . (file_exists($ctrl) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";

// Testar bootstrap
echo "<hr><h3>Tentando carregar bootstrap...</h3>";
try {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    echo "<p>✅ autoload OK</p>";
    
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    echo "<p>✅ .env carregado</p>";
    
    $db = App\Core\Database::getInstance();
    echo "<p>✅ Banco de dados conectado</p>";
    
    echo "<p>✅ Tudo OK - o problema pode ser no .htaccess ou no roteamento</p>";
} catch (Throwable $e) {
    echo "<p>❌ ERRO: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Arquivo: " . htmlspecialchars($e->getFile()) . " linha " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
