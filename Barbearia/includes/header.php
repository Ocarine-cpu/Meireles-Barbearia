<?php
require_once __DIR__ . '/auth.php';
$usuario = currentUser();
$caminhoBase = basePath();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Meireles Barbearia</title>
  <!-- Font Awesome (ícones) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


  <!-- Tema inicial (evita flicker) -->
  <script>
  (function () {
    try {
      var CHAVE='mb-tema';
      var t = localStorage.getItem(CHAVE);
      if (t!=='light' && t!=='dark') {
        t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }
      document.documentElement.setAttribute('data-bs-theme', t);
    } catch(e){}
  })();
  </script>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $caminhoBase ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?= $caminhoBase ?>/assets/css/darkMode.css" rel="stylesheet">
</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top sombra-suave">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="<?= $caminhoBase ?>/index.php">Meireles Barbearia</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuSuperior" aria-controls="menuSuperior" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuSuperior">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/Barbearia/meus_agendamentos.php">Meus Serviços</a>


          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Barbearia/agendamento.php">Agendamento</a>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $caminhoBase ?>/pages/contato.php">Contato</a>
          </li>
           <?php if (isset($_SESSION['user']) && $_SESSION['user']['perfil'] === 'admin'): ?>
    <li class="nav-item">
      <a class="nav-link text-warning fw-semibold" href="<?= $caminhoBase ?>/admin.php">Painel do Dono</a>
    </li>
  <?php endif; ?>
        </ul>


        <!-- Dropdown perfil -->
        <div class="dropdown text-end">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="menuPerfil"
             data-bs-toggle="dropdown" aria-expanded="false">
            <?php if ($usuario && !empty($usuario['foto'])): ?>
              <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" width="32" height="32" class="rounded-circle">
            <?php else: ?>
              <span class="fs-4">👤</span>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="menuPerfil">
            <?php if (!$usuario): ?>
              <li><a class="dropdown-item" href="<?= $caminhoBase ?>/pages/login.php">Entrar</a></li>
                <li><button class="dropdown-item" type="button" data-tema-toggle>Mudar tema</button></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="<?= $caminhoBase ?>/pages/perfil.php">Meu Perfil</a></li>
              <li><button class="dropdown-item" type="button" data-tema-toggle>Mudar tema</button></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= $caminhoBase ?>/pages/logout.php">Sair</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>


</header>

<!-- Conteúdo -->
<main class="conteudo-ajustado">
