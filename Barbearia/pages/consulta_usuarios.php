<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$pesquisa = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";

$sql = "
    SELECT 
        u.id_usuario,
        u.nome_completo,
        u.login,
        u.data_cadastro,
        (
            SELECT segundo_fator 
            FROM logs 
            WHERE id_usuario = u.id_usuario 
              AND acao = 'login_2fa' 
            ORDER BY id_log DESC 
            LIMIT 1
        ) AS ultimo_fator
    FROM usuarios u
    WHERE u.perfil = 'cliente'
      AND (u.nome_completo LIKE ? OR u.login LIKE ?)
    ORDER BY u.nome_completo ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$pesquisa%", "%$pesquisa%"]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sucesso = isset($_GET['sucesso']);
$erro = isset($_GET['erro']);
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-4">
    <h2 class="fw-bold mb-4">Consulta de Usuários</h2>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" 
                   placeholder="Pesquisar por nome ou login..." 
                   value="<?= htmlspecialchars($pesquisa) ?>">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Botão PDF -->
    <a href="gerar_pdf_usuarios.php" class="btn btn-outline-danger mb-3">
        Gerar PDF da lista
    </a>

    <table class="table table-dark table-striped table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Último 2FA Respondido</th>
                <th>Data de Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($usuarios) === 0): ?>
                <tr>
                    <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nome_completo']) ?></td>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td><?= htmlspecialchars($u['ultimo_fator'] ?? '—') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($u['data_cadastro'])) ?></td>
                    <td>
                        <a href="excluir_usuario.php?id=<?= $u['id_usuario'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                           Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- TOASTS -->
<?php if ($sucesso): ?>
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show text-bg-success">
        <div class="toast-body">
            Usuário excluído com sucesso!
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($erro): ?>
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show text-bg-danger">
        <div class="toast-body">
            Não foi possível realizar a operação.
        </div>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
