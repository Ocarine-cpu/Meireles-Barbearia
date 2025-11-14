<?php

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
    return isset($_SESSION['user']) && isset($_SESSION['2fa_verified']) && $_SESSION['2fa_verified'] === true;
}

function basePath(): string {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Caminho base
    $path = '/barbearia'; // <-- nome da pasta do projeto, tem que verificar depois se tá certinho
    
    return "http://{$host}{$path}";

}
