<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['user']['id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Pega o agendamento
$stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id, $id_usuario]);
$agendamento = $stmt->fetch();

if (!$agendamento) {
    die("Agendamento não encontrado ou você não tem permissão.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_data = $_POST['data'];
    $nova_hora = $_POST['hora'];

    if (!$nova_data || !$nova_hora) {
        $erro = "Preencha data e hora.";
    } else {
        $data_hora = date('Y-m-d H:i:s', strtotime("$nova_data $nova_hora"));
        $update = $pdo->prepare("UPDATE agendamentos SET data_hora = ? WHERE id = ? AND id_usuario = ?");
        $update->execute([$data_hora, $id, $id_usuario]);
        header("Location: meus_agendamentos.php?editado=1");
        exit;
    }
}
?>

<?php require __DIR__ . '/includes/header.php'; ?>

<div class="container py-5">
    <h2>Editar Agendamento</h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="data" class="form-label">Nova Data</label>
            <input type="date" id="data" name="data" class="form-control" required value="<?= date('Y-m-d', strtotime($agendamento['data_hora'])) ?>">
        </div>
        <div class="mb-3">
            <label for="hora" class="form-label">Nova Hora</label>
            <input type="time" id="hora" name="hora" class="form-control" required value="<?= date('H:i', strtotime($agendamento['data_hora'])) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="meus_agendamentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
