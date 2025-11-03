<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';


$usuario = currentUser();
if (!$usuario || $usuario['perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['excluir'])) {
    $id_excluir = (int) $_GET['excluir'];

    $stmt = $pdo->prepare("SELECT perfil FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_excluir]);
    $perfil = $stmt->fetchColumn();

    if ($perfil === 'cliente') {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_excluir]);
        $mensagem = "Usuário excluído com sucesso!";
    } else {
        $erro = "Você não pode excluir outro administrador.";
    }
}

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
if ($busca) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE perfil = 'cliente' AND nome_completo LIKE ?");
    $stmt->execute(["%$busca%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE perfil = 'cliente' ORDER BY nome_completo ASC");
}
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
  <h1 class="mb-4 text-center">Consulta de Usuários</h1>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensagem) ?></div>
  <?php endif; ?>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form class="mb-4 d-flex justify-content-center" method="get">
    <input type="text" name="busca" class="form-control w-50 me-2" placeholder="Buscar por nome..." value="<?= htmlspecialchars($busca) ?>">
    <button type="submit" class="btn btn-primary">Buscar</button>
    <a href="consulta_usuarios.php" class="btn btn-secondary ms-2">Limpar</a>
  </form>

  <?php if (count($usuarios) === 0): ?>
    <p class="text-center text-muted">Nenhum usuário encontrado.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nome Completo</th>
            <th>Login</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CEP</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['id_usuario']) ?></td>
              <td><?= htmlspecialchars($u['nome_completo']) ?></td>
              <td><?= htmlspecialchars($u['login']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['telefone']) ?></td>
              <td><?= htmlspecialchars($u['cep']) ?></td>
              <td>
                <a href="consulta_usuarios.php?excluir=<?= $u['id_usuario'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                   Excluir
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <!-- Botão para baixar PDF. Lembrar de mexer depois -->
  <div class="text-center mt-4">
    <a href="usuarios_pdf.php" class="btn btn-outline-secondary">
      <i class="fa fa-file-pdf"></i> Baixar lista em PDF
    </a>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
