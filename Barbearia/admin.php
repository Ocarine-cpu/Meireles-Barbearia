<?php
require_once __DIR__ . '/includes/verifica_admin.php';

try {
  $pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");

  $stmt = $pdo->query("SELECT * FROM agendamentos ORDER BY data_hora ASC");
  $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>

<?php require __DIR__ . '/includes/header.php'; ?>

<div class="container py-5">
  <h1 class="mb-4">Painel do Dono</h1>

  <?php if (count($agendamentos) === 0): ?>
    <div class="alert alert-info">Nenhum agendamento encontrado.</div>
  <?php else: ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Cliente</th>
          <th>Telefone</th>
          <th>Serviço</th>
          <th>Data e Hora</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($agendamentos as $ag): ?>
      <tr>
      <td><?= htmlspecialchars($ag['nome_cliente']) ?></td>
      <td><?= htmlspecialchars($ag['telefone']) ?></td> <!-- NOVO -->
      <td><?= htmlspecialchars($ag['servico']) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
    </tr>
  <?php endforeach; ?>
</tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
