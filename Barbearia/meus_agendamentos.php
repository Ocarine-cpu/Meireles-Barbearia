<?php
session_start();
require_once __DIR__ . '/config/bd.php';

// Verifica se está logado
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['user']['id'];
$perfil = $_SESSION['user']['perfil'];

// Busca os agendamentos
try {
    if ($perfil === 'admin') {
        // Admin vê todos os agendamentos
        $stmt = $pdo->query("SELECT a.*, u.nome_completo FROM agendamentos a JOIN usuarios u ON a.id_usuario = u.id_usuario ORDER BY a.data_hora DESC");
        $agendamentos = $stmt->fetchAll();
    } else {
        // Cliente vê só os próprios agendamentos
        $stmt = $pdo->prepare("SELECT * FROM agendamentos WHERE id_usuario = ? ORDER BY data_hora DESC");
        $stmt->execute([$id_usuario]);
        $agendamentos = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    die("Erro ao buscar agendamentos: " . $e->getMessage());
}
?>

<?php require __DIR__ . '/includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Meus Agendamentos</h2>

    <?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success">Agendamento realizado com sucesso!</div>
<?php endif; ?>

<?php if (isset($_GET['cancelado'])): ?>
    <div class="alert alert-success">Agendamento cancelado com sucesso!</div>
<?php endif; ?>

<?php if (isset($_GET['editado'])): ?>
    <div class="alert alert-success">Agendamento editado com sucesso!</div>
<?php endif; ?>


    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Agendamento realizado com sucesso!</div>
    <?php endif; ?>

    <?php if (empty($agendamentos)): ?>
        <p>Nenhum agendamento encontrado.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
    <tr>
        <?php if ($perfil === 'admin'): ?>
            <th>Cliente</th>
        <?php endif; ?>
        <th>Serviço</th>
        <th>Data e Hora</th>
        <th>Criado em</th>
        <?php if ($perfil !== 'admin'): ?>
            <th>Ações</th>
        <?php endif; ?>
    </tr>
</thead>
<tbody>
    <?php foreach ($agendamentos as $ag): ?>
        <tr>
            <?php if ($perfil === 'admin'): ?>
                <td><?= htmlspecialchars($ag['nome_completo']) ?></td>
            <?php endif; ?>
            <td><?= htmlspecialchars($ag['servico']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($ag['criado_em'])) ?></td>
            <?php if ($perfil !== 'admin'): ?>
                <td>
                    <a href="editar_agendamento.php?id=<?= $ag['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Tem certeza que deseja cancelar este agendamento?');">Cancelar</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</tbody>

            
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
