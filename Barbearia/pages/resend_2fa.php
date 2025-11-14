<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/email_sender.php'; 

if (!isset($_SESSION['2fa_user_id']) || !isset($_SESSION['2fa_email'])) {
    header("Location: login.php");
    exit;
}

$novo_codigo_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$nova_expiracao_2fa = time() + (5 * 60); 

$_SESSION['2fa_code'] = $novo_codigo_2fa;
$_SESSION['2fa_expiration'] = $nova_expiracao_2fa;

if (enviarEmail2FA($_SESSION['2fa_email'], $novo_codigo_2fa)) {

    header("Location: 2fa_verify.php?resend=1");
    exit;
} else {

    header("Location: 2fa_verify.php?erro=resend_failed");
    exit;
}
?>