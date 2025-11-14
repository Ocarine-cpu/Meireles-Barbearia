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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $caminhoBase ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?= $caminhoBase ?>/assets/css/darkMode.css" rel="stylesheet">

  <style>
    .btn-admin-outline {
        padding: 8px 22px;
        border: 2px solid #0a64ea;
        border-radius: 999px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0a64ea;
        background: transparent;
        transition: .25s ease;
    }
    .btn-admin-outline:hover {
        background: #0a64ea;
        color: white !important;
        transform: scale(1.05);
        box-shadow: 0 0 12px rgba(10, 100, 234, .4);
    }
  </style>

    <!-- Toast container -->
    <style>
     #toastContainer {
          position: fixed;
          top: 20px;
          right: 20px;
          z-index: 99999;
      }
    </style>

    <div id="toastContainer">
      <?php if (isset($_GET['msg']) && isset($_GET['tipo'])): ?>
        <div class="toast align-items-center text-white bg-<?= htmlspecialchars($_GET['tipo']) ?> border-0 show" role="alert">
          <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      <?php endif; ?>
    </div>

</head>

<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top sombra-suave">
    <div class="container">

      <!-- logo -->
      <a class="navbar-brand fw-semibold" href="<?= $caminhoBase ?>/index.php">
        <img id="logo-topo" src="<?= $caminhoBase ?>/meireles_barbearia_logo-Photoroom.png" 
             alt="Meireles Barbearia" height="50">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#menuSuperior" aria-controls="menuSuperior" aria-expanded="false" 
              aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuSuperior">

      <!-- os links lá -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link" 
               href="<?= $usuario ? '/barbearia/meus_agendamentos.php' : '/barbearia/precisa_logar.php' ?>">
              Meus Serviços
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/barbearia/agendamento.php">Agendamento</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= $caminhoBase ?>/pages/contato.php">Contato</a>
          </li>

        </ul>


        <?php if ($usuario && $usuario['perfil'] === 'admin'): ?>
            <a href="<?= $caminhoBase ?>/pages/painel.php" class="btn-admin-outline me-3">
                Painel
            </a>
        <?php endif; ?>

        <!-- dropdown do perfil, mudar tema e sair -->
        <div class="dropdown text-end">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
             id="menuPerfil" data-bs-toggle="dropdown" aria-expanded="false">

            <?php if ($usuario && !empty($usuario['foto'])): ?>
              <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" 
                   width="32" height="32" class="rounded-circle">
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

<main class="conteudo-ajustado">
