<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected string $table = "users";

    /**
     * Encontra um usuário pelo seu endereço de e-mail.
     *
     * @param string $email O e-mail do usuário.
     * @return object|false O objeto do usuário se encontrado, ou false caso contrário.
     */
    public function findByEmail(string $email): object|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Cria um novo usuário no banco de dados.
     *
     * @param array $data Os dados do usuário (ex: ["name" => ..., "email" => ..., "password" => ...]).
     * @return string|false O ID do usuário inserido, ou false em caso de falha.
     */
    public function create(array $data): string|false
    {
        $sql = "INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":name", $data["name"]);
        $stmt->bindValue(":email", $data["email"]);
        $stmt->bindValue(":password", $data["password"]); // A senha já deve estar com hash

        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }
}
