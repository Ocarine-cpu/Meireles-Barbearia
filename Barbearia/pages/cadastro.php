<?php
$erro = null; // inicializa a variável
require __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="POST" action="cadastro_processa.php">
      <h1 class="h3 mb-3 fw-normal text-center">Cadastro</h1>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="nomeCompleto" name="nome" placeholder="Nome completo" required>
        <label for="nomeCompleto">Nome completo</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="emailUsuario" name="email" placeholder="E-mail" required>
        <label for="emailUsuario">E-mail</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="loginUsuario" name="login" placeholder="Login" required>
        <label for="loginUsuario">Login</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="senhaUsuario" name="senha" placeholder="Senha" required>
        <label for="senhaUsuario">Senha</label>
      </div>

      <button class="w-100 btn btn-lg btn-success" type="submit">Cadastrar</button>

      <hr class="my-4">
      <small class="texto-secundario">
        Já possui cadastro? <a href="login.php">Entrar</a>
      </small>
    </form>
  </div>
</div>

<?php if (isset($_GET['sucesso'])): ?>
  <div class="alert alert-success text-center mt-3">Cadastro realizado com sucesso! Faça login.</div>
<?php endif; ?>

<?php if ($erro): ?>
  <div class="alert alert-danger text-center mt-3"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
