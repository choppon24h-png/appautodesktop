<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Logger;

class DashboardController extends Controller
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function index()
    {
        try {
            $this->logger->router("DashboardController::index() iniciado", [
                'user_id' => $_SESSION["user_id"] ?? null,
                'user_name' => $_SESSION["user_name"] ?? null
            ]);

            // Validar se a sessão do usuário está intacta
            if (!isset($_SESSION["user_id"])) {
                $this->logger->error("Sessão do usuário não encontrada no Dashboard", [
                    'session_data' => $_SESSION
                ]);
                header("Location: /login?error=session_expired");
                exit();
            }

            if (!isset($_SESSION["user_name"])) {
                $this->logger->error("Nome do usuário não encontrado na sessão", [
                    'user_id' => $_SESSION["user_id"]
                ]);
                $_SESSION["user_name"] = "Usuário";
            }

            $this->logger->router("Validações de sessão concluídas com sucesso", [
                'user_id' => $_SESSION["user_id"],
                'user_name' => $_SESSION["user_name"]
            ]);

            // Preparar dados para a view
            $data = [
                "title" => "Dashboard",
                "userName" => $_SESSION["user_name"] ?? "Usuário"
            ];

            $this->logger->view("Renderizando view dashboard/index", [
                'data' => $data
            ]);

            // Renderizar a view
            View::render("dashboard/index", $data);

            $this->logger->router("DashboardController::index() concluído com sucesso");

        } catch (\Exception $e) {
            $this->logger->error("Erro ao renderizar Dashboard", [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Re-lançar a exceção para ser capturada pelo handler global
            throw $e;
        }
    }
}
