<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Model de Negócio — AppAuto SaaS
 * Empresas e estabelecimentos vinculados a usuários
 */
class Negocio
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function buscarPorId(int $id): ?object
    {
        $stmt = $this->db->prepare(
            "SELECT n.*, r.nome AS ramo_nome, r.icone AS ramo_icone,
                    u.nome_completo AS dono_nome, u.email AS dono_email
             FROM `negocios` n
             LEFT JOIN `ramos_atividade` r ON r.id = n.ramo_atividade_id
             LEFT JOIN `usuarios` u ON u.id = n.usuario_id
             WHERE n.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    public function buscarPorUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT n.*, r.nome AS ramo_nome, r.icone AS ramo_icone
             FROM `negocios` n
             LEFT JOIN `ramos_atividade` r ON r.id = n.ramo_atividade_id
             WHERE n.usuario_id = :uid
             ORDER BY n.criado_em DESC"
        );
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listarTodos(int $pagina = 1, int $porPagina = 20): array
    {
        $offset = ($pagina - 1) * $porPagina;
        $stmt   = $this->db->prepare(
            "SELECT n.*, r.nome AS ramo_nome,
                    u.nome_completo AS dono_nome, u.email AS dono_email
             FROM `negocios` n
             LEFT JOIN `ramos_atividade` r ON r.id = n.ramo_atividade_id
             LEFT JOIN `usuarios` u ON u.id = n.usuario_id
             ORDER BY n.criado_em DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit',  $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function contarTodos(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM `negocios`")->fetchColumn();
    }

    public function criar(array $dados): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `negocios`
                (`usuario_id`,`ramo_atividade_id`,`razao_social`,`nome_fantasia`,
                 `cnpj`,`telefone`,`celular`,`email`,`status`)
             VALUES
                (:usuario_id,:ramo_atividade_id,:razao_social,:nome_fantasia,
                 :cnpj,:telefone,:celular,:email,:status)"
        );
        $stmt->execute([
            ':usuario_id'        => $dados['usuario_id'],
            ':ramo_atividade_id' => $dados['ramo_atividade_id'],
            ':razao_social'      => $dados['razao_social'],
            ':nome_fantasia'     => $dados['nome_fantasia'] ?? null,
            ':cnpj'              => $dados['cnpj'] ?? null,
            ':telefone'          => $dados['telefone'] ?? null,
            ':celular'           => $dados['celular'] ?? null,
            ':email'             => $dados['email'] ?? null,
            ':status'            => 'pendente',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function listarRamos(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM `ramos_atividade` WHERE `ativo` = 1 ORDER BY `nome` ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function cnpjExiste(string $cnpj): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `negocios` WHERE `cnpj` = :cnpj"
        );
        $stmt->execute([':cnpj' => $cnpj]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
