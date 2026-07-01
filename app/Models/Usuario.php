<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Model de Usuário — AppAuto SaaS
 * Controle central por e-mail (PF e PJ)
 */
class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ----------------------------------------------------------------
    // Busca
    // ----------------------------------------------------------------

    public function buscarPorEmail(string $email): ?object
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `usuarios` WHERE `email` = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    public function buscarPorId(int $id): ?object
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `usuarios` WHERE `id` = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    public function listarTodos(string $tipo = null, int $pagina = 1, int $porPagina = 20): array
    {
        $offset = ($pagina - 1) * $porPagina;
        $where  = $tipo ? "WHERE `tipo_conta` = :tipo" : "";
        $params = $tipo ? [':tipo' => $tipo] : [];
        $params[':limit']  = $porPagina;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare(
            "SELECT `id`,`nome_completo`,`email`,`cpf`,`telefone`,`tipo_conta`,
                    `tipo_documento`,`perfil`,`status`,`email_verificado`,
                    `ultimo_login`,`criado_em`
             FROM `usuarios`
             {$where}
             ORDER BY `criado_em` DESC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $k => $v) {
            if ($k === ':limit' || $k === ':offset') {
                $stmt->bindValue($k, (int)$v, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($k, $v);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function contarTodos(string $tipo = null): int
    {
        $where  = $tipo ? "WHERE `tipo_conta` = :tipo" : "";
        $params = $tipo ? [':tipo' => $tipo] : [];
        $stmt   = $this->db->prepare("SELECT COUNT(*) FROM `usuarios` {$where}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    // ----------------------------------------------------------------
    // Criação
    // ----------------------------------------------------------------

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `usuarios`
                (`nome_completo`,`email`,`senha`,`cpf`,`telefone`,
                 `tipo_conta`,`tipo_documento`,`perfil`,`status`,
                 `token_validacao`,`token_expira_em`,`ip_cadastro`)
             VALUES
                (:nome_completo,:email,:senha,:cpf,:telefone,
                 :tipo_conta,:tipo_documento,:perfil,:status,
                 :token_validacao,:token_expira_em,:ip_cadastro)"
        );
        $stmt->execute([
            ':nome_completo'   => $dados['nome_completo'],
            ':email'           => $dados['email'],
            ':senha'           => password_hash($dados['senha'], PASSWORD_ARGON2ID),
            ':cpf'             => $dados['cpf'] ?? null,
            ':telefone'        => $dados['telefone'] ?? null,
            ':tipo_conta'      => $dados['tipo_conta'] ?? 'pessoal',
            ':tipo_documento'  => $dados['tipo_documento'] ?? 'cpf',
            ':perfil'          => $dados['perfil'] ?? 'usuario',
            ':status'          => 'pendente',
            ':token_validacao' => $dados['token'] ?? null,
            ':token_expira_em' => $dados['token_expira_em'] ?? null,
            ':ip_cadastro'     => $dados['ip'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // ----------------------------------------------------------------
    // Validação de token
    // ----------------------------------------------------------------

    public function validarToken(string $email, string $token): bool
    {
        $usuario = $this->buscarPorEmail($email);
        if (!$usuario) return false;
        if ($usuario->token_validacao !== strtoupper($token)) return false;
        if (strtotime($usuario->token_expira_em) < time()) return false;

        $stmt = $this->db->prepare(
            "UPDATE `usuarios`
             SET `email_verificado` = 1,
                 `status` = 'ativo',
                 `token_validacao` = NULL,
                 `token_expira_em` = NULL
             WHERE `id` = :id"
        );
        $stmt->execute([':id' => $usuario->id]);
        return true;
    }

    public function atualizarToken(int $id, string $token, string $expira): void
    {
        $stmt = $this->db->prepare(
            "UPDATE `usuarios`
             SET `token_validacao` = :token,
                 `token_expira_em` = :expira
             WHERE `id` = :id"
        );
        $stmt->execute([':token' => $token, ':expira' => $expira, ':id' => $id]);
    }

    // ----------------------------------------------------------------
    // Autenticação
    // ----------------------------------------------------------------

    public function autenticar(string $email, string $senha): ?object
    {
        $usuario = $this->buscarPorEmail($email);
        if (!$usuario) return null;
        if ($usuario->status !== 'ativo') return null;
        if (!password_verify($senha, $usuario->senha)) return null;

        // Atualizar último login
        $stmt = $this->db->prepare(
            "UPDATE `usuarios` SET `ultimo_login` = NOW() WHERE `id` = :id"
        );
        $stmt->execute([':id' => $usuario->id]);

        return $usuario;
    }

    // ----------------------------------------------------------------
    // Atualização
    // ----------------------------------------------------------------

    public function atualizarStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE `usuarios` SET `status` = :status WHERE `id` = :id"
        );
        $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `usuarios` WHERE `email` = :email"
        );
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function cpfExiste(string $cpf): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `usuarios` WHERE `cpf` = :cpf"
        );
        $stmt->execute([':cpf' => $cpf]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // ----------------------------------------------------------------
    // Geração de token aleatório
    // ----------------------------------------------------------------

    public static function gerarToken(int $tamanho = 6): string
    {
        $chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $token;
    }
}
