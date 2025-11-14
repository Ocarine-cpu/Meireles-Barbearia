<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

try {

    $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE perfil='cliente'")->fetchColumn();

    $totalAgendamentos = $pdo->query("SELECT COUNT(*) FROM agendamentos")->fetchColumn();

    $totalServicos = $pdo->query("SELECT COUNT(DISTINCT servico) FROM agendamentos")->fetchColumn();

    $ultimosUsuarios = $pdo->query("
        SELECT nome_completo, login, data_cadastro
        FROM usuarios
        WHERE perfil='cliente'
        ORDER BY data_cadastro DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    $ultimosAgendamentos = $pdo->query("
        SELECT a.*, u.nome_completo
        FROM agendamentos a
        JOIN usuarios u ON u.id_usuario = a.id_usuario
        ORDER BY a.data_hora DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar dados do painel: " . $e->getMessage());
}

require __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">

    <h1 class="fw-bold mb-4">Painel do Administrador</h1>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">Usuários Cadastrados</h5>
                    <p class="fs-2 fw-bold"><?= $totalUsuarios ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title text-success fw-bold">Agendamentos</h5>
                    <p class="fs-2 fw-bold"><?= $totalAgendamentos ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning fw-bold">Serviços Diferentes</h5>
                    <p class="fs-2 fw-bold"><?= $totalServicos ?></p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4 d-flex gap-3">
        <a href="consulta_usuarios.php" class="btn btn-primary">
            Gerenciar Usuários
        </a>

        <a href="gerar_pdf_usuarios.php" class="btn btn-outline-danger">
            Baixar PDF de Usuários
        </a>
    </div>

    <hr class="my-5">

    <h3 class="mb-3">Últimos Usuários Cadastrados</h3>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimosUsuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nome_completo']) ?></td>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($u['data_cadastro'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <hr class="my-5">

    <h3 class="mb-3">Últimos Agendamentos</h3>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Data Agendada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimosAgendamentos as $ag): ?>
                <tr>
                    <td><?= htmlspecialchars($ag['nome_completo']) ?></td>
                    <td><?= htmlspecialchars($ag['servico']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
