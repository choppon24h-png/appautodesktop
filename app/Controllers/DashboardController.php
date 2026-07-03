<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * DashboardController
 * Redireciona para o portal correto conforme o perfil do usuário.
 * A view antiga do ERP foi removida — o AppAuto usa o Portal de Veículos.
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login?error=session_expired');
            exit();
        }

        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /portal/dashboard');
        }
        exit();
    }
}
