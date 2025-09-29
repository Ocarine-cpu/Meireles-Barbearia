<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/email_sender.php'; // Inclui o novo arquivo de envio de e-mail

// Verifica se há um ID de usuário na sessão aguardando 2FA
if (!isset($_SESSION['2fa_user_id']) || !isset($_SESSION['2fa_email'])) {
    header("Location: login.php");
    exit;
}

// 1. Gerar um novo código 2FA
$novo_codigo_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$nova_expiracao_2fa = time() + (5 * 60); // Novo código expira em 5 minutos

// 2. Atualizar o código e a expiração na sessão
$_SESSION['2fa_code'] = $novo_codigo_2fa;
$_SESSION['2fa_expiration'] = $nova_expiracao_2fa;

// 3. Enviar o novo código por e-mail
if (enviarEmail2FA($_SESSION['2fa_email'], $novo_codigo_2fa)) {
    // Redireciona de volta para a página de verificação com uma mensagem de sucesso
    header("Location: 2fa_verify.php?resend=1");
    exit;
} else {
    // Se o envio falhar, redireciona para a página de verificação com um erro
    header("Location: 2fa_verify.php?erro=resend_failed");
    exit;
}
?>