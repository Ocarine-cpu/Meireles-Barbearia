<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: consulta_usuarios.php?erro=1");
    exit;
}

$id = (int) $_GET['id'];

if ($id == $_SESSION['user']['id']) {
    header("Location: consulta_usuarios.php?erro=1");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);

header("Location: consulta_usuarios.php?sucesso=1");
exit;
