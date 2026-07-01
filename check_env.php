<?php
/**
 * Script de Verificação Pós-Upload
 * 
 * Este script valida a configuração do sistema após o upload no HostGator.
 * Execute este arquivo no navegador: https://erp.inlaudo.com.br/check_env.php
 * 
 * IMPORTANTE: Remova este arquivo após a verificação bem-sucedida!
 */

// Desabilitar exibição de erros para evitar exposição de informações sensíveis
ini_set('display_errors', 0);
error_reporting(0);

// Definir header como HTML
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Configuração - INLAUDO ERP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 800px; width: 100%; padding: 40px; }
        h1 { color: #333; margin-bottom: 30px; text-align: center; font-size: 28px; }
        .section { margin-bottom: 30px; }
        .section-title { background: #f5f5f5; padding: 15px; border-left: 4px solid #667eea; margin-bottom: 15px; font-weight: bold; color: #333; }
        .check-item { display: flex; align-items: center; padding: 12px; margin-bottom: 10px; border-radius: 4px; background: #f9f9f9; border: 1px solid #e0e0e0; }
        .check-icon { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; margin-right: 15px; font-size: 14px; }
        .check-icon.success { background: #4caf50; }
        .check-icon.error { background: #f44336; }
        .check-icon.warning { background: #ff9800; }
        .check-label { flex: 1; }
        .check-value { color: #666; font-size: 12px; margin-top: 5px; }
        .summary { padding: 20px; border-radius: 4px; margin-top: 30px; text-align: center; font-weight: bold; }
        .summary.success { background: #e8f5e9; color: #2e7d32; border: 1px solid #4caf50; }
        .summary.error { background: #ffebee; color: #c62828; border: 1px solid #f44336; }
        .warning-box { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 4px; margin-top: 20px; color: #856404; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px; margin-top: 10px; overflow-x: auto; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verificação de Configuração</h1>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">INLAUDO ERP - Sistema de Gestão</p>

        <?php
        $checks = [];
        $allPassed = true;

        // ============================================
        // 1. Verificar arquivo .env
        // ============================================
        $envExists = file_exists(__DIR__ . '/.env');
        $checks[] = [
            'title' => 'Arquivo .env existe',
            'passed' => $envExists,
            'details' => $envExists ? 'Arquivo encontrado' : 'Arquivo não encontrado'
        ];
        if (!$envExists) $allPassed = false;

        // ============================================
        // 2. Carregar variáveis de ambiente
        // ============================================
        $envVars = [];
        if ($envExists) {
            $envContent = file_get_contents(__DIR__ . '/.env');
            preg_match_all('/^([A-Z_]+)=(.*)$/m', $envContent, $matches);
            for ($i = 0; $i < count($matches[1]); $i++) {
                $envVars[$matches[1][$i]] = $matches[2][$i];
            }
        }

        // ============================================
        // 3. Verificar variáveis de ambiente críticas
        // ============================================
        $requiredVars = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'APP_ENV'];
        foreach ($requiredVars as $var) {
            $exists = isset($envVars[$var]) && !empty($envVars[$var]);
            $checks[] = [
                'title' => "Variável de ambiente: {$var}",
                'passed' => $exists,
                'details' => $exists ? 'Configurada' : 'NÃO CONFIGURADA'
            ];
            if (!$exists) $allPassed = false;
        }

        // ============================================
        // 4. Verificar diretórios críticos
        // ============================================
        $directories = [
            'app' => __DIR__ . '/app',
            'storage/logs' => __DIR__ . '/storage/logs',
            'public' => __DIR__ . '/public',
            'vendor' => __DIR__ . '/vendor'
        ];

        foreach ($directories as $name => $path) {
            $exists = is_dir($path);
            $checks[] = [
                'title' => "Diretório: {$name}",
                'passed' => $exists,
                'details' => $exists ? 'Encontrado' : 'NÃO ENCONTRADO'
            ];
            if (!$exists) $allPassed = false;
        }

        // ============================================
        // 5. Verificar permissões de escrita
        // ============================================
        $storageLogsPath = __DIR__ . '/storage/logs';
        $logsWritable = is_writable($storageLogsPath);
        $checks[] = [
            'title' => 'Permissão de escrita: storage/logs',
            'passed' => $logsWritable,
            'details' => $logsWritable ? 'Escrita permitida' : 'ESCRITA NEGADA'
        ];
        if (!$logsWritable) $allPassed = false;

        // ============================================
        // 6. Verificar PHP version
        // ============================================
        $phpVersion = phpversion();
        $phpOk = version_compare($phpVersion, '7.4.0', '>=');
        $checks[] = [
            'title' => 'Versão do PHP',
            'passed' => $phpOk,
            'details' => "PHP {$phpVersion} " . ($phpOk ? '(OK)' : '(VERSÃO ANTIGA)')
        ];
        if (!$phpOk) $allPassed = false;

        // ============================================
        // 7. Verificar extensões PHP necessárias
        // ============================================
        $extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            $checks[] = [
                'title' => "Extensão PHP: {$ext}",
                'passed' => $loaded,
                'details' => $loaded ? 'Carregada' : 'NÃO CARREGADA'
            ];
            if (!$loaded) $allPassed = false;
        }

        // ============================================
        // 8. Verificar arquivo bootstrap.php
        // ============================================
        $bootstrapExists = file_exists(__DIR__ . '/app/bootstrap.php');
        $checks[] = [
            'title' => 'Arquivo bootstrap.php',
            'passed' => $bootstrapExists,
            'details' => $bootstrapExists ? 'Encontrado' : 'NÃO ENCONTRADO'
        ];
        if (!$bootstrapExists) $allPassed = false;

        // ============================================
        // 9. Verificar arquivo de rotas
        // ============================================
        $routesExists = file_exists(__DIR__ . '/routes/web.php');
        $checks[] = [
            'title' => 'Arquivo de rotas: routes/web.php',
            'passed' => $routesExists,
            'details' => $routesExists ? 'Encontrado' : 'NÃO ENCONTRADO'
        ];
        if (!$routesExists) $allPassed = false;

        // ============================================
        // 10. Verificar arquivo de configuração de banco
        // ============================================
        $dbConfigExists = file_exists(__DIR__ . '/config/database.php');
        $checks[] = [
            'title' => 'Arquivo de configuração: config/database.php',
            'passed' => $dbConfigExists,
            'details' => $dbConfigExists ? 'Encontrado' : 'NÃO ENCONTRADO'
        ];
        if (!$dbConfigExists) $allPassed = false;

        // Renderizar checks
        foreach ($checks as $check) {
            $icon = $check['passed'] ? '✓' : '✗';
            $class = $check['passed'] ? 'success' : 'error';
            echo "<div class='section'>";
            echo "<div class='check-item'>";
            echo "<div class='check-icon {$class}'>{$icon}</div>";
            echo "<div class='check-label'>";
            echo "<strong>{$check['title']}</strong>";
            echo "<div class='check-value'>{$check['details']}</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        // Resumo final
        echo "<div class='summary " . ($allPassed ? 'success' : 'error') . "'>";
        if ($allPassed) {
            echo "✓ Todas as verificações passaram! O sistema está pronto para uso.";
        } else {
            echo "✗ Algumas verificações falharam. Verifique os erros acima.";
        }
        echo "</div>";

        // Instruções de segurança
        echo "<div class='warning-box'>";
        echo "<strong>⚠️ IMPORTANTE:</strong><br>";
        echo "1. Após verificar a configuração, <strong>remova este arquivo</strong> (check_env.php)<br>";
        echo "2. Verifique os logs em <code>/storage/logs/</code> para diagnosticar problemas<br>";
        echo "3. Em produção, certifique-se de que <code>APP_ENV=prod</code> no arquivo .env<br>";
        echo "4. Nunca compartilhe o arquivo .env ou credenciais do banco de dados<br>";
        echo "</div>";

        // Informações de debug (apenas em desenvolvimento)
        if (isset($envVars['APP_ENV']) && $envVars['APP_ENV'] === 'dev') {
            echo "<div class='section' style='margin-top: 30px;'>";
            echo "<div class='section-title'>📋 Informações de Debug (Modo Desenvolvimento)</div>";
            echo "<div class='code'>";
            echo "APP_ENV: " . htmlspecialchars($envVars['APP_ENV'] ?? 'não definido') . "<br>";
            echo "DB_HOST: " . htmlspecialchars($envVars['DB_HOST'] ?? 'não definido') . "<br>";
            echo "DB_DATABASE: " . htmlspecialchars($envVars['DB_DATABASE'] ?? 'não definido') . "<br>";
            echo "DB_USERNAME: " . htmlspecialchars($envVars['DB_USERNAME'] ?? 'não definido') . "<br>";
            echo "PHP Version: " . phpversion() . "<br>";
            echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
            echo "</div>";
            echo "</div>";
        }
        ?>

        <div class="footer">
            <p>INLAUDO ERP - Sistema de Gestão | Verificação de Configuração</p>
            <p>Gerado em: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
