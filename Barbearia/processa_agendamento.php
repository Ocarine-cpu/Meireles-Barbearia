<?php
session_start();
require_once __DIR__ . '/config/bd.php'; // conexão PDO

if (!isset($_SESSION['user'])) {
    header("Location: agendamento.php?erro=nao_logado");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['user']['id'];
    $nome_cliente = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $servico = trim($_POST['servico']);

    if (empty($nome_cliente) || empty($telefone) || empty($data) || empty($hora) || empty($servico)) {
        die("Por favor, preencha todos os campos.");
    }

    $data_hora = new DateTime("$data $hora");

    // Define duração do corte: 1h30m
    $duracao = new DateInterval('PT1H30M');
    $fim_agendamento = clone $data_hora;
    $fim_agendamento->add($duracao);

    // Verifica conflitos
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM agendamentos
        WHERE data_hora < :fim_agendamento
          AND DATE_ADD(data_hora, INTERVAL 90 MINUTE) > :inicio_agendamento
    ");
    $stmt->execute([
        ':inicio_agendamento' => $data_hora->format('Y-m-d H:i:s'),
        ':fim_agendamento' => $fim_agendamento->format('Y-m-d H:i:s'),
    ]);
    $conflitos = $stmt->fetchColumn();

    if ($conflitos > 0) {
        // Horário ocupado
        header("Location: agendamento.php?erro=horario_ocupado");
        exit;
    }

    // Se não tem conflito, insere normalmente
    try {
        $stmt = $pdo->prepare("INSERT INTO agendamentos (id_usuario, nome_cliente, telefone, servico, data_hora) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $nome_cliente, $telefone, $servico, $data_hora->format('Y-m-d H:i:s')]);

        header("Location: meus_agendamentos.php?sucesso=1");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar agendamento: " . $e->getMessage());
    }
} else {
    die("Método inválido.");
}
