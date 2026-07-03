<?php

namespace App\Core;

abstract class Controller
{
    /**
     * Redireciona para uma URL e encerra a execução.
     */
    protected function redir(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Retorna JSON e encerra a execução.
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Verifica se o usuário está autenticado; se não, redireciona para login.
     */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redir('/login?error=session_expired');
        }
    }

    /**
     * Verifica se o usuário é admin; se não, redireciona para o portal.
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->redir('/portal/dashboard');
        }
    }
}
