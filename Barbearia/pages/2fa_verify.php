<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php'; // Para buscar os dados do usuário após a 2FA

// Verifica se há um ID de usuário na sessão aguardando 2FA
if (!isset($_SESSION['2fa_user_id']) || !isset($_SESSION['2fa_code']) || !isset($_SESSION['2fa_email']) || !isset($_SESSION['2fa_expiration'])) {
    header("Location: login.php"); // Redireciona se não houver 2FA pendente
    exit;
}

// Verifica se o código expirou
if (time() > $_SESSION['2fa_expiration']) {
    // Limpa a sessão 2FA e redireciona para login com erro de expiração
    unset($_SESSION['2fa_user_id']);
    unset($_SESSION['2fa_code']);
    unset($_SESSION['2fa_email']);
    unset($_SESSION['2fa_expiration']);
    header("Location: login.php?erro=2fa_expired");
    exit;
}

$erro = null;
$email_mascarado = substr($_SESSION['2fa_email'], 0, 3) . '***' . substr($_SESSION['2fa_email'], strpos($_SESSION['2fa_email'], '@') - 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_digitado = trim($_POST['codigo_2fa']);

    if ($codigo_digitado === $_SESSION['2fa_code']) {
        // Código correto! Finaliza o login.

        // Busca os dados completos do usuário do banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$_SESSION['2fa_user_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Define a sessão 'user' completa
            $_SESSION['user'] = [
                'id'     => $usuario['id_usuario'],
                'login'  => $usuario['login'],
                'nome'   => $usuario['nome_completo'],
                'email'  => $usuario['email'],
                'perfil' => $usuario['perfil'],
                'foto'   => $usuario['foto'] ?? null
            ];
            $_SESSION['2fa_verified'] = true; // Marca que a 2FA foi verificada

            // Limpa as variáveis temporárias da sessão 2FA
            unset($_SESSION['2fa_user_id']);
            unset($_SESSION['2fa_code']);
            unset($_SESSION['2fa_email']);
            unset($_SESSION['2fa_expiration']);

            header("Location: " . basePath() . "/index.php"); // Redireciona para a página inicial
            exit;
        } else {
            $erro = "Erro ao carregar dados do usuário.";
            // Limpa a sessão 2FA para evitar loops
            unset($_SESSION['2fa_user_id']);
            unset($_SESSION['2fa_code']);
            unset($_SESSION['2fa_email']);
            unset($_SESSION['2fa_expiration']);
            header("Location: login.php?erro=user_not_found");
            exit;
        }
    } else {
        $erro = "Código de verificação inválido. Tente novamente.";
    }
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="post">
      <h1 class="h3 mb-3 fw-normal text-center">Verificação de Dois Fatores</h1>
      <p class="text-center">Um código de 6 dígitos foi enviado para o seu e-mail: <strong><?= htmlspecialchars($email_mascarado) ?></strong></p>
      <p class="text-center small text-muted">O código expira em <?= round(($_SESSION['2fa_expiration'] - time()) / 60) ?> minutos.</p>

      <?php if (isset($_GET['resend'])): ?>
        <div class="alert alert-success">Um novo código foi enviado para o seu e-mail.</div>
      <?php endif; ?>

      <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="codigo2fa" name="codigo_2fa" placeholder="Código de 6 dígitos" required maxlength="6" pattern="[0-9]{6}">
        <label for="codigo2fa">Código de Verificação</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Verificar</button>

      <hr class="my-4">
      <small class="texto-secundario">
        Não recebeu o código? <a href="resend_2fa.php">Reenviar</a>
      </small>
      <br>
      <small class="texto-secundario">
        <a href="logout.php">Cancelar e voltar ao login</a>
      </small>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>