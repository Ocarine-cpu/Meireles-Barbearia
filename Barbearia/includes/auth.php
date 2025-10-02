<?php
// Controle de sessão

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Quando o login estiver pronto:
 * $_SESSION['user'] = [
 *   'id'     => 123,
 *   'login'  => 'abc123',
 *   'nome'   => 'Fulano da Silva',
 *   'perfil' => 'admin' | 'cliente'
 * ];
 */

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool {
    // O usuário está logado se a sessão 'user' existe E a 2FA foi verificada
    // Se a sessão 'user' existe mas '2fa_verified' não, significa que ele está no meio do processo 2FA.
    return isset($_SESSION['user']) && isset($_SESSION['2fa_verified']) && $_SESSION['2fa_verified'] === true;
}

function basePath(): string {
    // Detecta se está em localhost
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Caminho base
    $path = '/barbearia'; // <-- nome da pasta do projeto

    // Retorna sempre URL absoluta
    return "http://{$host}{$path}";

}
