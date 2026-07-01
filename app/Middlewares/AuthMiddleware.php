<?php

namespace App\Middlewares;

use App\Core\Middleware;

class AuthMiddleware extends Middleware
{
    public function handle(): void
    {
        // Se não houver uma sessão de usuário ativa, redireciona para o login
        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit();
        }
    }
}
