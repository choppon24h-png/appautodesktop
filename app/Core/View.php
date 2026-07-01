<?php

namespace App\Core;

class View
{
    private static ?Logger $logger = null;

    /**
     * Obtém a instância do logger
     */
    private static function getLogger(): Logger
    {
        if (self::$logger === null) {
            self::$logger = new Logger();
        }
        return self::$logger;
    }

    /**
     * Renderiza um arquivo de view com um layout.
     *
     * @param string $view O nome do arquivo da view (ex: "dashboard.index").
     * @param array $data Os dados a serem extraídos e disponibilizados para a view.
     */
    public static function render(string $view, array $data = []): void
    {
        $logger = self::getLogger();

        try {
            $logger->view("Iniciando renderização de view", [
                'view' => $view,
                'data_keys' => array_keys($data)
            ]);

            // Converte as chaves do array em variáveis
            extract($data);

            // Monta o caminho para o arquivo da view
            $viewFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php';

            $logger->view("Caminho da view construído", [
                'view' => $view,
                'path' => $viewFile,
                'exists' => file_exists($viewFile)
            ]);

            if (!file_exists($viewFile)) {
                $logger->error("Arquivo de view não encontrado", [
                    'view' => $view,
                    'path' => $viewFile
                ]);
                http_response_code(500);
                echo "Erro: View '{$view}' não encontrada no caminho: {$viewFile}";
                exit;
            }

            ob_start();
            
            try {
                require $viewFile;
            } catch (\Exception $e) {
                ob_end_clean();
                $logger->error("Erro ao incluir arquivo de view", [
                    'view' => $view,
                    'path' => $viewFile,
                    'exception' => get_class($e),
                    'message' => $e->getMessage()
                ]);
                throw $e;
            }

            $content = ob_get_clean();

            $logger->view("Conteúdo da view capturado com sucesso", [
                'view' => $view,
                'content_length' => strlen($content)
            ]);

            // Inclui o layout, que usará a variável $content
            $headerFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'header.php';
            $footerFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'footer.php';

            $logger->view("Verificando existência de layouts", [
                'header_exists' => file_exists($headerFile),
                'footer_exists' => file_exists($footerFile)
            ]);

            if (file_exists($headerFile)) {
                try {
                    require $headerFile;
                    $logger->view("Header incluído com sucesso");
                } catch (\Exception $e) {
                    $logger->error("Erro ao incluir header", [
                        'path' => $headerFile,
                        'message' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }
            
            echo $content;
            
            if (file_exists($footerFile)) {
                try {
                    require $footerFile;
                    $logger->view("Footer incluído com sucesso");
                } catch (\Exception $e) {
                    $logger->error("Erro ao incluir footer", [
                        'path' => $footerFile,
                        'message' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            $logger->view("Renderização de view concluída com sucesso", [
                'view' => $view
            ]);

        } catch (\Exception $e) {
            $logger->error("Erro crítico ao renderizar view", [
                'view' => $view,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Re-lançar a exceção para ser capturada pelo handler global
            throw $e;
        }
    }

    /**
     * Retorna o campo de input oculto com o token CSRF.
     * @return string
     */
    public static function csrfField(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
