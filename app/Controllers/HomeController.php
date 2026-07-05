<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        // Não autenticado → login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        // Admin → painel administrativo
        // Usa user_perfil (salvo pelo AuthController) — NÃO user_role
        $perfil = $_SESSION['user_perfil'] ?? $_SESSION['user_role'] ?? '';
        if ($perfil === 'admin') {
            header('Location: /admin/dashboard');
            exit();
        }

        // Qualquer outro usuário (PF ou PJ) → Portal de Veículos
        header('Location: /portal/dashboard');
        exit();
    }
}
