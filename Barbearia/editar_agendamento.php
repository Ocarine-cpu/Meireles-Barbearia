<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user'])) {
    header("Location: pages/login.php");
    exit;
}

$id_usuario = $_SESSION['user']['id'];
$perfil = $_SESSION['user']['perfil'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID de agendamento inválido.");
}

// ==============================
// Busca o agendamento
// ==============================
if ($perfil === 'admin') {

    $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ?");
    $stmt->execute([$id]);
} else {

    $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id = ? AND id_usuario = ?");
    $stmt->execute([$id, $id_usuario]);
}

$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    die("Agendamento não encontrado ou você não tem permissão para editá-lo.");
}

// ==============================
// Atualiza o agendamento
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_data = $_POST['data'] ?? '';
    $nova_hora = $_POST['hora'] ?? '';

    if (empty($nova_data) || empty($nova_hora)) {
        $erro = "Preencha data e hora corretamente.";
    } else {
        $data_hora = date('Y-m-d H:i:s', strtotime("$nova_data $nova_hora"));

        try {
            $update = $pdo->prepare("UPDATE agendamentos SET data_hora = ? WHERE id = ?");
            $update->execute([$data_hora, $id]);

            header("Location: meus_agendamentos.php?editado=1");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>

<?php require __DIR__ . '/includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Editar Agendamento</h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="data" class="form-label">Nova Data</label>
            <input type="date" id="data" name="data" class="form-control" required
                   value="<?= date('Y-m-d', strtotime($agendamento['data_hora'])) ?>">
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Nova Hora</label>
            <input type="time" id="hora" name="hora" class="form-control" required
                   value="<?= date('H:i', strtotime($agendamento['data_hora'])) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="meus_agendamentos.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
