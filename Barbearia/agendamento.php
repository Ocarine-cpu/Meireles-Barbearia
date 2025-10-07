  <?php require __DIR__ . '/includes/header.php'; ?>

  <div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Agendamento</h2>

<?php if (isset($_GET['erro']) && $_GET['erro'] === 'horario_ocupado'): ?>
  <div class="alert alert-danger text-center mb-4">
    Este horário já está ocupado. Por favor, escolha outro.
  </div>
<?php endif; ?>

<?php if (isset($_GET['erro']) && $_GET['erro'] === 'nao_logado'): ?>
  <div class="alert alert-warning text-center mb-4">
    Você precisa estar <a href="pages/login.php" class="alert-link">logado</a> para agendar um horário.
  </div>
<?php endif; ?>

<p class="text-center texto-secundario mb-5">Escolha o tipo de corte e preencha seus dados</p>
    

    <!-- Tipos de cortes -->
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
      <!-- Corte Simples -->
      <div class="col">
        <div class="card h-100 shadow-sm border-primary">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Corte Simples</h5>
            <p class="card-text">Corte De Cabelo.</p>
            <h6 class="fw-bold">R$30</h6>
            <button class="btn btn-outline-primary selecionar-servico" data-servico="Corte Simples">Selecionar</button>
          </div>
        </div>
      </div>

      <!-- Corte Premium -->
      <div class="col">
        <div class="card h-100 border-primary shadow border-primary">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Corte Comun</h5>
            <p class="card-text">Corte De Cabelo, sombrancelha.</p>
            <h6 class="fw-bold">R$35</h6>
            <button class="btn btn-outline-primary selecionar-servico" data-servico="Corte Premium">Selecionar</button>
          </div>
        </div>
      </div>

      <!-- Plano Mensal -->
      <div class="col">
        <div class="card h-100 shadow-sm border-primary">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Plano Mensal</h5>
            <p class="card-text">Corte De Cabelo, sombrancelha, barba.</p>
            <h6 class="fw-bold">R$40</h6>
            <button class="btn btn-outline-primary selecionar-servico" data-servico="Plano Mensal">Selecionar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulário -->
    <div class="card shadow-sm bg-body-secondary">
      <div class="card-body">
        <h4 class="mb-4">Preencha seus dados</h4>
        <form action="processa_agendamento.php" method="POST">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>

          <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="telefone" name="telefone" required>
          </div>

          <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data" name="data" required>
          </div>

          <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <input type="time" class="form-control" id="hora" name="hora" required>
          </div>

          <div class="mb-3">
            <label for="servico" class="form-label">Serviço selecionado</label>
            <input type="text" class="form-control" id="servico" name="servico" readonly required>
          </div>

          <button type="submit" class="btn btn-success">Confirmar Agendamento</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Script para preencher o campo de serviço ao clicar -->
  <script>
    document.querySelectorAll('.selecionar-servico').forEach(button => {
      button.addEventListener('click', () => {
        const servicoSelecionado = button.getAttribute('data-servico');
        document.getElementById('servico').value = servicoSelecionado;
        window.scrollTo({ top: document.querySelector('form').offsetTop - 50, behavior: 'smooth' });
      });
    });
  </script>

  <?php require __DIR__ . '/includes/footer.php'; ?>
