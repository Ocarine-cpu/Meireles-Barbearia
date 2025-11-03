<?php
require_once __DIR__ . '/includes/verifica_admin.php';

try {
  $pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Busca os agendamentos com nome do cliente
  $stmt = $pdo->query("
    SELECT a.id, a.servico, a.data_hora, u.nome_completo
    FROM agendamentos a
    JOIN usuarios u ON a.id_usuario = u.id_usuario
    ORDER BY a.data_hora ASC
  ");
  $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>

<?php require __DIR__ . '/includes/header.php'; ?>

<div class="container py-5">
  <h1 class="mb-4">Painel do Dono</h1>

  <?php if (empty($agendamentos)): ?>
    <div class="alert alert-info">Nenhum agendamento encontrado.</div>
  <?php else: ?>
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Cliente</th>
          <th>Serviço</th>
          <th>Data e Hora</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($agendamentos as $ag): ?>
          <tr>
            <td><?= htmlspecialchars($ag['nome_completo']) ?></td>
            <td><?= htmlspecialchars($ag['servico']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
            <td>
              <a href="editar_agendamento.php?id=<?= $ag['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
              <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>" 
                 class="btn btn-sm btn-danger" 
                 onclick="return confirm('Deseja realmente excluir este agendamento?');">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
