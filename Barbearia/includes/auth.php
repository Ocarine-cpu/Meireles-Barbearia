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
    return isset($_SESSION['user']);
}

function basePath(): string {
    // Detecta se está em localhost
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Caminho base 
    $path = '/barbearia'; // <-- nome da pasta do projeto

    // Retorna sempre URL absoluta
    return "http://{$host}{$path}";
}

