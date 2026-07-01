<?php
// AppAuto — Diagnóstico de Servidor
// REMOVA ESTE ARQUIVO APÓS O DIAGNÓSTICO!

// Exibe todos os erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>AppAuto — Diagnóstico</h1>";
echo "<h2>1. Versão do PHP</h2>";
echo "<p>" . phpversion() . "</p>";

echo "<h2>2. Extensões críticas</h2>";
$exts = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'openssl', 'session'];
foreach ($exts as $ext) {
    $ok = extension_loaded($ext);
    echo "<p>" . ($ok ? "✅" : "❌") . " {$ext}</p>";
}

echo "<h2>3. Arquivo .env</h2>";
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    echo "<p>✅ .env encontrado em: {$envPath}</p>";
    $lines = file($envPath);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        // Oculta senha
        if (strpos($line, 'PASSWORD') !== false) {
            $line = preg_replace('/=.*/', '=***OCULTO***', $line);
        }
        echo "<p><code>" . htmlspecialchars($line) . "</code></p>";
    }
} else {
    echo "<p>❌ .env NÃO encontrado em: {$envPath}</p>";
}

echo "<h2>4. Vendor / Autoload</h2>";
$vendorPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($vendorPath)) {
    echo "<p>✅ vendor/autoload.php encontrado</p>";
    require_once $vendorPath;
    echo "<p>✅ Autoload carregado com sucesso</p>";
} else {
    echo "<p>❌ vendor/autoload.php NÃO encontrado</p>";
}

echo "<h2>5. Carregando .env via phpdotenv</h2>";
try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    echo "<p>✅ .env carregado via phpdotenv</p>";
    echo "<p>DB_HOST: " . htmlspecialchars($_ENV['DB_HOST'] ?? 'NÃO DEFINIDO') . "</p>";
    echo "<p>DB_DATABASE: " . htmlspecialchars($_ENV['DB_DATABASE'] ?? 'NÃO DEFINIDO') . "</p>";
    echo "<p>DB_USERNAME: " . htmlspecialchars($_ENV['DB_USERNAME'] ?? 'NÃO DEFINIDO') . "</p>";
    echo "<p>APP_ENV: " . htmlspecialchars($_ENV['APP_ENV'] ?? 'NÃO DEFINIDO') . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Erro ao carregar .env: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h2>6. Conexão com o Banco de Dados</h2>";
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_DATABASE'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    $dsn  = "mysql:host={$host};dbname={$db};charset=utf8";
    $pdo  = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<p>✅ Conexão com o banco de dados OK!</p>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $row  = $stmt->fetch(PDO::FETCH_OBJ);
    echo "<p>✅ Tabela 'usuarios' encontrada — {$row->total} registros</p>";
} catch (Exception $e) {
    echo "<p>❌ Erro de banco: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h2>7. Layouts</h2>";
$layouts = [
    'public_header.php',
    'public_footer.php',
    'erp_header.php',
    'erp_footer.php',
    'app_header.php',
    'app_footer.php',
];
foreach ($layouts as $layout) {
    $path = dirname(__DIR__) . "/app/Views/layout/{$layout}";
    echo "<p>" . (file_exists($path) ? "✅" : "❌") . " {$layout}</p>";
}

echo "<h2>8. Views principais</h2>";
$views = [
    'auth/login.php',
    'auth/cadastro.php',
    'auth/validar_token.php',
    'admin/dashboard.php',
    'veiculos/index.php',
    'placeholder/index.php',
];
foreach ($views as $view) {
    $path = dirname(__DIR__) . "/app/Views/{$view}";
    echo "<p>" . (file_exists($path) ? "✅" : "❌") . " {$view}</p>";
}

echo "<h2>9. Permissões de escrita</h2>";
$dirs = ['storage/logs', 'public/assets/uploads'];
foreach ($dirs as $dir) {
    $path = dirname(__DIR__) . "/{$dir}";
    $exists = is_dir($path);
    $writable = is_writable($path);
    echo "<p>" . ($exists && $writable ? "✅" : "❌") . " {$dir} — " . ($exists ? "existe" : "NÃO existe") . " / " . ($writable ? "gravável" : "SEM permissão de escrita") . "</p>";
}

echo "<hr><p><strong>⚠️ REMOVA este arquivo do servidor após o diagnóstico!</strong></p>";
