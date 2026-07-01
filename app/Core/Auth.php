<?php

namespace App\Core;

class Auth
{
    /**
     * Gera um hash de senha seguro.
     *
     * @param string $password A senha em texto plano.
     * @return string O hash da senha.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Verifica se uma senha corresponde a um hash.
     *
     * @param string $password A senha em texto plano.
     * @param string $hash O hash da senha armazenado.
     * @return bool True se a senha for válida, false caso contrário.
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Regenera o ID da sessão para prevenir ataques de fixação de sessão.
     */
    public static function regenerateSession(): void
    {
        session_regenerate_id(true);
    }
}
