<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/email_sender.php'; // Inclui o novo arquivo de envio de e-mail

if (isLoggedIn()) { header("Location: ../index.php"); exit; }

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login']);
  $senha = $_POST['senha'];
  try {
    $pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
    $stmt->execute([$login]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // 1. Gerar um código 2FA (ex: 6 dígitos numéricos)
        $codigo_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiracao_2fa = time() + (5 * 60); // Código expira em 5 minutos

        // 2. Armazenar o código, ID do usuário, e-mail e tempo de expiração na sessão temporariamente
        $_SESSION['2fa_user_id'] = $usuario['id_usuario'];
        $_SESSION['2fa_code'] = $codigo_2fa;
        $_SESSION['2fa_email'] = $usuario['email'];
        $_SESSION['2fa_expiration'] = $expiracao_2fa;

        // 3. Enviar o código por e-mail
        if (enviarEmail2FA($usuario['email'], $codigo_2fa)) {
            // 4. Redirecionar para a página de verificação 2FA
            header("Location: 2fa_verify.php?sent=1");
            exit;
        } else {
            $erro = "Erro ao enviar o código de verificação. Tente novamente.";
            // Limpa a sessão 2FA para evitar que o usuário fique preso
            unset($_SESSION['2fa_user_id']);
            unset($_SESSION['2fa_code']);
            unset($_SESSION['2fa_email']);
            unset($_SESSION['2fa_expiration']);
        }

    } else { $erro = "Login ou senha inválidos."; }
  } catch (PDOException $e) { $erro = "Erro no banco: " . $e->getMessage(); }
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="post">
      <h1 class="h3 mb-3 fw-normal text-center">Entrar</h1>

      <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <?php if (isset($_GET['2fa_required'])): ?>
        <div class="alert alert-info">Um código de verificação foi enviado para o seu e-mail.</div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="loginUsuario" name="login" placeholder="Login" required>
        <label for="loginUsuario">Login</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="senhaUsuario" name="senha" placeholder="Senha" required>
        <label for="senhaUsuario">Senha</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Acessar</button>

      <hr class="my-4">
      <small class="texto-secundario">
        Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
      </small>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>