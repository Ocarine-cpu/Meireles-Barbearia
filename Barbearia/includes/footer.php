<?php
$caminhoBase = function_exists('basePath') ? basePath() : '';
?>
</main>

<footer class="borda-topo py-4 mt-5">
  <div class="container text-center texto-secundario pequeno">
    © <?= date('Y') ?> Meireles Barbearia
  </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $caminhoBase ?>/assets/js/theme.js"></script>
</body>
</html>
