<style>
  #contato .btn {
    color: #fff !important;
  }
  #contato .btn:hover {
    filter: brightness(0.9);
  }
  #contato a {
    color: inherit !important;
  }

  /* Ícones */
  #contato .icon-whatsapp {
    color: #25D366 !important; /* Verde oficial do WhatsApp */
  }

  #contato .icon-location {
    color: #e63946 !important; /* Vermelho estilo marcador de mapa */
  }

  #contato .icon-clock {
    color: #1e90ff !important; /* Azul estilo ícone de relógio */
  }

  #contato .icon-warning {
    color: #ffc107 !important; /* Amarelo padrão Bootstrap (ainda usado nas redes sociais) */
  }
</style>

<section id="contato" class="contato-section py-5 text-center text-white" style="background-color: #1e1e1e;">
  <div class="container">
    <h2 class="mb-4">Fale Conosco</h2>

    <div class="row justify-content-center g-4">

      <!-- WhatsApp -->
      <div class="col-md-3 text-center">
        <a href="https://wa.me/5521982030713" target="_blank" class="text-decoration-none icon-whatsapp">
          <i class="fab fa-whatsapp fa-2x mb-2"></i>
        </a>
        <p class="mb-0 fw-semibold text-white">Entre em contato</p>
        <small class="d-block text-white-50 mb-2">(21) 98203-0713</small>
        <a href="https://wa.me/5521982030713" target="_blank" class="btn btn-success btn-sm">
          Chamar no WhatsApp
        </a>
      </div>

      <!-- Endereço -->
      <div class="col-md-3 text-center">
        <a href="https://www.google.com/maps/place/Estrada+do+tingui,+4285+-+Conjunto+Campinho,+Rio+de+Janeiro+-+RJ"
           target="_blank" class="text-decoration-none icon-location">
          <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
        </a>
        <p class="mb-0 fw-semibold text-white">Endereço</p>
        <p>Estrada do tingui, 4285 - Conjunto Campinho, Rio de Janeiro - RJ</p>
        <a href="https://www.google.com/maps/place/Estrada+do+tingui,+4285+-+Conjunto+Campinho,+Rio+de+Janeiro+-+RJ"
           target="_blank" class="btn btn-danger btn-sm mt-2">
          Ver no Maps
        </a>
      </div>

      <!-- Horários -->
      <div class="col-md-3 text-center">
        <a href="/Barbearia/agendamento.php" class="text-decoration-none icon-clock">
          <i class="fas fa-clock fa-2x mb-2"></i>
        </a>
        <p class="mb-0 fw-semibold text-white">Horários</p>
        <small>
          Terça a Sábado: 09:30h às 20h<br>
          Segunda: Fechado<br>
          Domingo: Fechado
        </small>
        <div class="mt-2">
          <a href="/Barbearia/agendamento.php" class="btn btn-primary btn-sm">
            Agendar Agora
          </a>
        </div>
      </div>

      <!-- Redes Sociais -->
      <div class="col-12 mt-4">
        <i class="fas fa-share-alt fa-2x mb-3 icon-warning"></i>
        <div>
          <a href="https://www.instagram.com/meirelesbarbearia_?igsh=eTVwb2NsdmM4bG5u" class="text-white fs-4 mx-3" target="_blank">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://www.facebook.com/share/1CAZjjQex9/?mibextid=wwXIfr" class="text-white fs-4 mx-3" target="_blank">
            <i class="fab fa-facebook"></i>
          </a>
          <a href="https://www.tiktok.com/@brunnomeireles10?_t=ZM-908o43cJesS&_r=1" class="text-white fs-4 mx-3" target="_blank">
            <i class="fab fa-tiktok"></i>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>
