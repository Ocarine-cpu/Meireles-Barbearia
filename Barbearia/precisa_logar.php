<?php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <script>
    (function() {
      const CHAVE = 'mb-tema';
      const salvo = localStorage.getItem(CHAVE);
      const preferido = salvo === 'light' || salvo === 'dark' ? salvo :
        window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-bs-theme', preferido);
    })();
  </script>

  <meta charset="UTF-8" />
  <title>Faça login para continuar</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      text-align: center;
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
    }
    .container {
      background: var(--bs-body-bg);
      max-width: 400px;
      margin: 0 auto;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px #0d6efd;
    }
    a.btn {
      display: inline-block;
      margin: 10px 15px;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      color: white;
    }
    a.login {
      background-color: #0d6efd;
    }
    a.signup {
      background-color: #198754;
    }
  </style>

  <script src="/Barbearia/assets/js/tema.js" defer></script>
</head>

<body>
  <div class="container">
    <h1>Você precisa estar logado</h1>
    <p>Para acessar seus cortes agendados, faça login ou crie uma conta.</p>
    <a href="/Barbearia/pages/login.php" class="btn login">Fazer Login</a>
    <a href="/Barbearia/pages/cadastro.php" class="btn signup">Criar Conta</a>
  </div>
</body>
</html>
