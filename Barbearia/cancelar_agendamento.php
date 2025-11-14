<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user'])) {
    header("Location: pages/login.php");
    exit;
}

$id_usuario = $_SESSION['user']['id'];
$perfil = $_SESSION['user']['perfil'];
$id_agendamento = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_agendamento <= 0) {
    die("Agendamento inválido.");
}

// ==============================
// Verifica se o agendamento existe e pertence ao usuário
// ==============================
if ($perfil === 'admin') {

    $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ?");
    $stmt->execute([$id_agendamento]);
} else {

    $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ? AND id_usuario = ?");
    $stmt->execute([$id_agendamento, $id_usuario]);
}

$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    die("Agendamento não encontrado ou você não tem permissão para excluir.");
}

// ==============================
// Exclui o agendamento
// ==============================
try {
    $delete = $pdo->prepare("DELETE FROM agendamentos WHERE id = ?");
    $delete->execute([$id_agendamento]);

    header("Location: meus_agendamentos.php?cancelado=1");
    exit;
} catch (PDOException $e) {
    die("Erro ao excluir agendamento: " . $e->getMessage());
}
