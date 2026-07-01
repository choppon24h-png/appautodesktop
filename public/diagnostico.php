<?php
// AppAuto — Diagnóstico Completo
// REMOVA ESTE ARQUIVO APÓS O DIAGNÓSTICO!
// Acesse: https://erp.appauto.com.br/public/diagnostico.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$root = dirname(__DIR__); // pasta raiz do projeto (acima de public/)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>AppAuto — Diagnóstico</title>
<style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 20px; }
h1 { color: #1a1a2e; border-bottom: 3px solid #e94560; padding-bottom: 10px; }
h2 { color: #16213e; margin-top: 30px; }
.ok  { color: #27ae60; font-weight: bold; }
.err { color: #e74c3c; font-weight: bold; }
.warn { color: #f39c12; font-weight: bold; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
td, th { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
th { background: #f5f5f5; }
code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-size: 13px; }
.box { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0; }
</style>
</head>
<body>

<h1>AppAuto — Diagnóstico do Servidor</h1>
<div class="box">⚠️ <strong>REMOVA este arquivo após o diagnóstico!</strong> Ele expõe informações do servidor.</div>

<h2>1. Versão do PHP e Servidor</h2>
<table>
<tr><th>Item</th><th>Valor</th></tr>
<tr><td>PHP Version</td><td><?= phpversion() ?></td></tr>
<tr><td>SAPI</td><td><?= php_sapi_name() ?></td></tr>
<tr><td>Servidor</td><td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></td></tr>
<tr><td>Document Root</td><td><?= $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' ?></td></tr>
<tr><td>Script atual</td><td><?= __FILE__ ?></td></tr>
<tr><td>Raiz do projeto</td><td><?= $root ?></td></tr>
<tr><td>REQUEST_URI</td><td><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?></td></tr>
</table>

<h2>2. Extensões PHP</h2>
<table>
<tr><th>Extensão</th><th>Status</th></tr>
<?php
$exts = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'openssl', 'session', 'fileinfo', 'gd'];
foreach ($exts as $ext) {
    $ok = extension_loaded($ext);
    echo "<tr><td>{$ext}</td><td class='" . ($ok ? 'ok' : 'err') . "'>" . ($ok ? '✅ OK' : '❌ FALTANDO') . "</td></tr>\n";
}
?>
</table>

<h2>3. Arquivo .env</h2>
<?php
$envPath = $root . '/.env';
if (file_exists($envPath)) {
    echo "<p class='ok'>✅ .env encontrado em: <code>{$envPath}</code></p>";
    $lines = file($envPath);
    echo "<table><tr><th>Variável</th><th>Valor</th></tr>";
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        [$key, $val] = array_pad(explode('=', $line, 2), 2, '');
        if (stripos($key, 'PASSWORD') !== false || stripos($key, 'SECRET') !== false) {
            $val = '***OCULTO***';
        }
        echo "<tr><td><code>" . htmlspecialchars($key) . "</code></td><td>" . htmlspecialchars($val) . "</td></tr>\n";
    }
    echo "</table>";
} else {
    echo "<p class='err'>❌ .env NÃO encontrado em: <code>{$envPath}</code></p>";
    echo "<p>Verifique se o arquivo .env foi enviado para a raiz do projeto (pasta acima de public/).</p>";
}
?>

<h2>4. Vendor / Autoload</h2>
<?php
$vendorPath = $root . '/vendor/autoload.php';
if (file_exists($vendorPath)) {
    echo "<p class='ok'>✅ vendor/autoload.php encontrado</p>";
    try {
        require_once $vendorPath;
        echo "<p class='ok'>✅ Autoload carregado com sucesso</p>";
    } catch (Throwable $e) {
        echo "<p class='err'>❌ Erro ao carregar autoload: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p class='err'>❌ vendor/autoload.php NÃO encontrado em: <code>{$vendorPath}</code></p>";
    echo "<p>Execute <code>composer install</code> na raiz do projeto, ou faça upload da pasta <code>vendor/</code>.</p>";
}
?>

<h2>5. Carregamento do .env via phpdotenv</h2>
<?php
if (class_exists('Dotenv\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable($root);
        $dotenv->load();
        echo "<p class='ok'>✅ phpdotenv carregado com sucesso</p>";
        $vars = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'APP_ENV', 'APP_URL'];
        echo "<table><tr><th>Variável</th><th>Valor</th></tr>";
        foreach ($vars as $var) {
            $val = $_ENV[$var] ?? '<span class="err">NÃO DEFINIDO</span>';
            echo "<tr><td><code>{$var}</code></td><td>{$val}</td></tr>\n";
        }
        echo "</table>";
    } catch (Throwable $e) {
        echo "<p class='err'>❌ Erro no phpdotenv: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p class='err'>❌ Classe Dotenv\Dotenv não encontrada — vendor não carregado</p>";
}
?>

<h2>6. Conexão com o Banco de Dados</h2>
<?php
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_DATABASE'] ?? '';
$dbUser = $_ENV['DB_USERNAME'] ?? '';
$dbPass = $_ENV['DB_PASSWORD'] ?? '';

if (empty($dbName) || empty($dbUser)) {
    echo "<p class='warn'>⚠️ DB_DATABASE ou DB_USERNAME não definidos no .env</p>";
} else {
    try {
        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        ]);
        echo "<p class='ok'>✅ Conexão com o banco de dados OK!</p>";

        // Verifica tabelas
        $tabelas = ['usuarios', 'negocios', 'ramos_atividade', 'veiculos', 'tokens_validacao'];
        echo "<table><tr><th>Tabela</th><th>Status</th><th>Registros</th></tr>";
        foreach ($tabelas as $tabela) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM `{$tabela}`");
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                echo "<tr><td><code>{$tabela}</code></td><td class='ok'>✅ Existe</td><td>{$row->total}</td></tr>\n";
            } catch (Throwable $e) {
                echo "<tr><td><code>{$tabela}</code></td><td class='err'>❌ Não existe</td><td>—</td></tr>\n";
            }
        }
        echo "</table>";
        echo "<p><small>Se as tabelas não existem, importe o arquivo <code>database/schema.sql</code> no phpMyAdmin.</small></p>";
    } catch (Throwable $e) {
        echo "<p class='err'>❌ Erro de conexão: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Verifique DB_HOST, DB_DATABASE, DB_USERNAME e DB_PASSWORD no .env</p>";
    }
}
?>

<h2>7. Arquivos de Layout</h2>
<?php
$layouts = [
    'app/Views/layout/public_header.php',
    'app/Views/layout/public_footer.php',
    'app/Views/layout/erp_header.php',
    'app/Views/layout/erp_footer.php',
    'app/Views/layout/app_header.php',
    'app/Views/layout/app_footer.php',
];
echo "<table><tr><th>Arquivo</th><th>Status</th></tr>";
foreach ($layouts as $f) {
    $path = $root . '/' . $f;
    $ok = file_exists($path);
    echo "<tr><td><code>{$f}</code></td><td class='" . ($ok ? 'ok' : 'err') . "'>" . ($ok ? '✅ OK' : '❌ NÃO ENCONTRADO') . "</td></tr>\n";
}
echo "</table>";
?>

<h2>8. Views Principais</h2>
<?php
$views = [
    'app/Views/auth/login.php',
    'app/Views/auth/cadastro.php',
    'app/Views/auth/validar_token.php',
    'app/Views/admin/dashboard.php',
    'app/Views/veiculos/index.php',
    'app/Views/placeholder/index.php',
    'app/Views/dashboard/index.php',
];
echo "<table><tr><th>View</th><th>Status</th></tr>";
foreach ($views as $f) {
    $path = $root . '/' . $f;
    $ok = file_exists($path);
    echo "<tr><td><code>{$f}</code></td><td class='" . ($ok ? 'ok' : 'err') . "'>" . ($ok ? '✅ OK' : '❌ NÃO ENCONTRADO') . "</td></tr>\n";
}
echo "</table>";
?>

<h2>9. Permissões de Escrita</h2>
<?php
$dirs = ['storage/logs', 'public/assets/uploads'];
echo "<table><tr><th>Diretório</th><th>Existe</th><th>Gravável</th></tr>";
foreach ($dirs as $d) {
    $path = $root . '/' . $d;
    $exists = is_dir($path);
    $writable = is_writable($path);
    echo "<tr><td><code>{$d}</code></td>"
       . "<td class='" . ($exists ? 'ok' : 'err') . "'>" . ($exists ? '✅ Sim' : '❌ Não') . "</td>"
       . "<td class='" . ($writable ? 'ok' : 'err') . "'>" . ($writable ? '✅ Sim' : '❌ Não') . "</td>"
       . "</tr>\n";
}
echo "</table>";
?>

<h2>10. Teste de Carregamento do Bootstrap</h2>
<?php
$bootstrapPath = $root . '/app/bootstrap.php';
if (file_exists($bootstrapPath)) {
    echo "<p class='ok'>✅ app/bootstrap.php encontrado</p>";
} else {
    echo "<p class='err'>❌ app/bootstrap.php NÃO encontrado em: <code>{$bootstrapPath}</code></p>";
}

$routesPath = $root . '/routes/web.php';
if (file_exists($routesPath)) {
    echo "<p class='ok'>✅ routes/web.php encontrado</p>";
} else {
    echo "<p class='err'>❌ routes/web.php NÃO encontrado</p>";
}
?>

<hr>
<p><strong>⚠️ REMOVA este arquivo do servidor após o diagnóstico!</strong></p>
<p><small>AppAuto SaaS — Diagnóstico gerado em <?= date('d/m/Y H:i:s') ?></small></p>
</body>
</html>
