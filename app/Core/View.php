<?php

namespace App\Core;

/**
 * View — AppAuto SaaS
 *
 * Lógica de layout inteligente:
 *  - Views em auth/*  → usam public_header / public_footer (sem sidebar)
 *  - Views em admin/* → usam app_header / app_footer (sidebar admin)
 *  - Demais views     → usam app_header / app_footer (sidebar usuário)
 *
 * As próprias views de auth/* já incluem os layouts via require_once,
 * portanto o View::render() apenas executa o arquivo sem envolver
 * um segundo layout quando a view já gerencia seu próprio HTML.
 */
class View
{
    private static ?Logger $logger = null;

    private static function getLogger(): Logger
    {
        if (self::$logger === null) {
            self::$logger = new Logger();
        }
        return self::$logger;
    }

    /**
     * Renderiza um arquivo de view.
     *
     * Views que já incluem seus próprios layouts (auth/*, admin/*, veiculos/*)
     * são renderizadas diretamente — sem envolver um segundo layout.
     *
     * Views legadas (dashboard/index, home/index) ainda recebem o
     * header/footer genérico original para não quebrar o sistema antigo.
     *
     * @param string $view  Caminho relativo em dot-notation (ex: "auth/login")
     * @param array  $data  Variáveis disponibilizadas para a view
     */
    public static function render(string $view, array $data = []): void
    {
        $logger = self::getLogger();

        try {
            $logger->view("Renderizando view: {$view}");

            // Disponibiliza as variáveis para a view
            extract($data);

            // Monta o caminho físico do arquivo
            $viewFile = dirname(__DIR__)
                . DIRECTORY_SEPARATOR . 'Views'
                . DIRECTORY_SEPARATOR . str_replace(['/', '.'], DIRECTORY_SEPARATOR, $view)
                . '.php';

            $logger->view("Caminho: {$viewFile} | Existe: " . (file_exists($viewFile) ? 'sim' : 'não'));

            if (!file_exists($viewFile)) {
                http_response_code(500);
                echo "<h2>Erro 500 — View não encontrada</h2>";
                echo "<p><code>{$viewFile}</code></p>";
                $logger->error("View não encontrada: {$viewFile}");
                exit;
            }

            // -------------------------------------------------------
            // Views que gerenciam seus próprios layouts (self-contained)
            // Prefixos: auth, admin, veiculos, negocio, perfil
            // -------------------------------------------------------
            $selfContained = [
                'auth/',
                'admin/',
                'veiculos/',
                'negocio/',
                'perfil/',
                'portal/',
            ];

            $isSelfContained = false;
            foreach ($selfContained as $prefix) {
                if (strpos($view, $prefix) === 0) {
                    $isSelfContained = true;
                    break;
                }
            }

            if ($isSelfContained) {
                // A view inclui seus próprios layouts internamente
                require $viewFile;
                $logger->view("View self-contained renderizada: {$view}");
                return;
            }

            // -------------------------------------------------------
            // Views legadas: captura conteúdo e envolve com layout ERP
            // -------------------------------------------------------
            $viewsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR;

            // Preferência: erp_header/erp_footer (layout completo do ERP)
            // Fallback: header/footer (layout genérico)
            $headerFile = file_exists($viewsDir . 'erp_header.php')
                ? $viewsDir . 'erp_header.php'
                : $viewsDir . 'header.php';

            $footerFile = file_exists($viewsDir . 'erp_footer.php')
                ? $viewsDir . 'erp_footer.php'
                : $viewsDir . 'footer.php';

            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            if (file_exists($headerFile)) {
                require $headerFile;
            }

            echo $content;

            if (file_exists($footerFile)) {
                require $footerFile;
            }

            $logger->view("View legada renderizada: {$view}");

        } catch (\Exception $e) {
            $logger->error("Erro ao renderizar view '{$view}': " . $e->getMessage() . " em " . $e->getFile() . ':' . $e->getLine());
            throw $e;
        }
    }

    /**
     * Retorna o campo hidden com o token CSRF.
     */
    public static function csrfField(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
