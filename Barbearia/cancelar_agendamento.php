<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Agendamento não informado.");
}

$id_agendamento = (int) $_GET['id'];
$id_usuario = $_SESSION['user']['id'];

// Apaga somente se for dono do agendamento
$stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_agendamento, $id_usuario]);

header("Location: meus_agendamentos.php?cancelado=1");
exit;
