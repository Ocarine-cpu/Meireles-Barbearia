<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header("Location: ../index.php");
    exit;
}

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

            $codigo_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $codigo_hash = password_hash($codigo_2fa, PASSWORD_DEFAULT);
            $expiracao = time() + 300; // 5 minutos

            $stmt = $pdo->prepare("INSERT INTO two_factor_codes (user_id, code_hash, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$usuario['id_usuario'], $codigo_hash, $expiracao]);

            $_SESSION['2fa_user_id'] = $usuario['id_usuario'];
            $_SESSION['codigo_2fa_simulado'] = $codigo_2fa;

            header("Location: 2fa_verificar.php");
            exit;

        } else {
            $erro = "Login ou senha inválidos.";
        }

    } catch (PDOException $e) {
        $erro = "Erro no banco: " . $e->getMessage();
    }
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
