<?php
// Dados do usuário + opção de alterar a senha
require_once __DIR__ . '/../includes/auth.php';

$usuario = currentUser();
if (!$usuario) {
    header("Location: login.php");
    exit;
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<h1 class="mb-4">Meu Perfil</h1>

<div class="card sombra-suave">
  <div class="card-body">
    <h5 class="card-title">Informações do Usuário</h5>
    <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
    <p><strong>Perfil:</strong> <?= htmlspecialchars($usuario['perfil']) ?></p>

    <hr>

    <h6>Foto de perfil</h6>
    <?php if (!empty($usuario['foto'])): ?>
      <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" width="100" class="rounded-circle mb-3">
    <?php else: ?>
      <p class="text-muted">Nenhuma foto cadastrada</p>
    <?php endif; ?>

    <!-- Form de upload -->
    <form action="upload_foto.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="foto" class="form-label">Trocar foto de perfil:</label>
        <input class="form-control" type="file" name="foto" id="foto" accept="image/*">
      </div>
      <button class="btn btn-primary" type="submit">Enviar</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
