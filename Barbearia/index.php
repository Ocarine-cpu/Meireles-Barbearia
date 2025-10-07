<?php require __DIR__ . '/includes/header.php'; ?>

<!-- Hero -->
<div class="px-4 py-5 text-center">
  <h1 class="display-5 fw-bold">
    Agende seu corte na <span class="text-primary">Meireles Barbearia</span>
  </h1>

  <div class="col-lg-8 mx-auto">
    <p class="lead texto-secundario mb-4">
      Escolha o profissional, o estilo e o melhor horário para você — rápido e sem complicação.
    </p>

   <img id="logo-site" class="d-block mx-auto mb-4 logo-site" src="/Barbearia/meireles_barbearia_logo-Photoroom.png" alt="logo Meireles Barbearia" width="150">


    <!-- Card explicativo (fora do grid de botões) -->
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto">
      <div class="card sombra-suave shadow-sm rounded-3 mb-4 bg-body-secondary">
        <div class="card-body text-start">
          <h5 class="card-title fw-bold mb-3">Como funciona?</h5>
          <ol class="mb-0">
            <li>Crie sua conta ou faça login.</li>
            <li>Escolha o barbeiro e o horário.</li>
            <li>Confirme o agendamento e pronto!</li>
          </ol>
        </div>
      </div>
    </div>

    <!-- Botões -->
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
      <a class="btn btn-outline-secondary btn-lg px-4" href="agendamento.php">Agendar agora</a>
     <a class="btn btn-outline-secondary btn-lg px-4" href="agendamento.php">Ver serviços</a>
    </div>
  </div>
</div>

<!-- Serviços -->
<section id="servicos" class="py-5">
  <div class="row text-center">
    <h2 class="fw-bold mb-5">Nossos Serviços</h2>

    <div class="col-lg-4">
      <div class="card mb-4 sombra-suave border-primary">
        <div class="card-body">
          <h4 class="card-title">Corte Simples</h4>
          <p class="card-text texto-secundario">Corte De Cabelo.</p>
          <h5 class="fw-bold">R$30</h5>
          <a class="btn btn-outline-secondary btn-lg px-4" href="agendamento.php">Ver serviços</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-4 sombra-suave border-primary">
        <div class="card-body">
          <h4 class="card-title">Corte comun</h4>
          <p class="card-text texto-secundario">Corte De Cabelo, sombrancelha.</p>
          <h5 class="fw-bold">R$35</h5>
          <a class="btn btn-outline-secondary btn-lg px-4" href="agendamento.php">Ver serviços</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-4 sombra-suave border-primary">
        <div class="card-body">
          <h4 class="card-title">Plano Premium</h4>
          <p class="card-text texto-secundario">Corte De Cabelo, sombrancelha, barba.</p>
          <h5 class="fw-bold">R$40</h5>
          <a class="btn btn-outline-secondary btn-lg px-4" href="agendamento.php">Ver serviços</a>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container py-4 text-center">
  <h4>Alguns dos nossos trabalhos</h4>
  <div class="row mt-4">
    <div class="col-md-4 mb-3">
      <img src="/Barbearia/imagens/img1.jpg" class="img-fluid rounded shadow same-size" alt="Corte 1">
    </div>
    <div class="col-md-4 mb-3">
      <img src="/Barbearia/imagens/img2.jpg" class="img-fluid rounded shadow same-size" alt="Corte 2">
    </div>
    <div class="col-md-4 mb-3">
      <img src="/Barbearia/imagens/img3.jpg" class="img-fluid rounded shadow same-size" alt="Corte 3">
    </div>
  </div>
</div>

<style>
  .same-size {
    width: 100%;
    height: 400px; /* ajuste conforme o visual desejado */
    object-fit: cover; /* mantém o corte proporcional */
    object-position: center; /* centraliza o foco da imagem */
  }
</style>

<?php require __DIR__ . '/includes/footer.php'; ?>
