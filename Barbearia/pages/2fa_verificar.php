<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';

if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: login.php");
    exit;
}

$erro = null;
$tempo_restante = null;
$user_id = $_SESSION['2fa_user_id'];

// Verifica o tempo restante do código mais recente
$stmt = $pdo->prepare("SELECT expires_at FROM two_factor_codes WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$ultima_entrada = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ultima_entrada || time() > $ultima_entrada['expires_at']) {
    // Código expirou ou não existe
    $erro = "O código expirou. Faça login novamente.";
    unset($_SESSION['2fa_user_id']);
    header("Location: login.php?erro=2fa_expired");
    exit;
} else {
    $tempo_restante = round(($ultima_entrada['expires_at'] - time()) / 60);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_digitado = trim($_POST['codigo_2fa']);

    // Busca o código mais recente
    $stmt = $pdo->prepare("SELECT * FROM two_factor_codes WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro && password_verify($codigo_digitado, $registro['code_hash'])) {
        // Código correto, busca dados do usuário
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$user_id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['user'] = [
                'id'     => $usuario['id_usuario'],
                'login'  => $usuario['login'],
                'nome'   => $usuario['nome_completo'],
                'email'  => $usuario['email'],
                'perfil' => $usuario['perfil'],
                'foto'   => $usuario['foto'] ?? null
            ];

            // (Opcional) Deleta os códigos antigos do banco
            $stmt = $pdo->prepare("DELETE FROM two_factor_codes WHERE user_id = ?");
            $stmt->execute([$user_id]);

            unset($_SESSION['2fa_user_id']);
            unset($_SESSION['codigo_2fa_simulado']); // Se ainda estiver usando

            header("Location: ../index.php");
            exit;
        } else {
            $erro = "Usuário não encontrado.";
        }
    } else {
        $erro = "Código inválido.";
    }
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="post">
      <h1 class="h3 mb-3 fw-normal text-center">Verificação de Dois Fatores</h1>
      <p class="text-center">
        Digite o código de 6 dígitos enviado para seu e-mail.<br>
        <strong>Expira em aproximadamente <?= $tempo_restante ?> minuto(s).</strong>
      </p>

      <?php if (isset($_SESSION['codigo_2fa_simulado'])): ?>
        <div class="alert alert-warning text-center">
          <strong>Seu Código é:</strong> <?= $_SESSION['codigo_2fa_simulado'] ?>
        </div>
      <?php endif; ?>

      <?php if ($erro): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="codigo2fa" name="codigo_2fa" placeholder="Código de 6 dígitos" required maxlength="6" pattern="[0-9]{6}">
        <label for="codigo2fa">Código de Verificação</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Verificar</button>

      <hr class="my-4">
      <small class="texto-secundario">
        <a href="logout.php">Cancelar e voltar ao login</a>
      </small>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
