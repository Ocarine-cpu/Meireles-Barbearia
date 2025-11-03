<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php'; 

$usuario_sessao = currentUser();
if (!$usuario_sessao) {
    header("Location: login.php");
    exit;
}

global $pdo;

$id_usuario_logado = $usuario_sessao['id'] ?? null; 

if (empty($id_usuario_logado)) {
    header("Location: logout.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar_dados') {
    
    $novo_nome      = trim($_POST['nome_completo'] ?? '');
    $novo_celular   = preg_replace('/\D/', '', $_POST['telefone_celular'] ?? ''); 
    $novo_fixo      = preg_replace('/\D/', '', $_POST['telefone_fixo'] ?? ''); 
    $erro = null;

    if (empty($novo_nome) || empty($novo_celular)) {
        $erro = "Nome e Telefone Celular são obrigatórios.";
    }
    
    if (!$erro) {
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome_completo = :nome, telefone_celular = :celular, telefone_fixo = :fixo WHERE id_usuario = :id_usuario");
            $sucesso_bd = $stmt->execute([
                ':nome'       => $novo_nome,
                ':celular'    => $novo_celular,
                ':fixo'       => $novo_fixo,
                ':id_usuario' => $id_usuario_logado 
            ]);

            if ($sucesso_bd) { 
                $_SESSION['sucesso'] = "Seus dados do perfil foram atualizados com sucesso!";
            } else {
                $_SESSION['erro'] = "Nenhum dado foi alterado ou ocorreu um erro interno.";
            }

        } catch (\PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar seus dados. Tente novamente.";
            error_log("Erro PDO ao atualizar dados do perfil: " . $e->getMessage());
        }
    } else {
         $_SESSION['erro'] = $erro;
    }
    
    header("Location: perfil.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1");
    $stmt->execute([':id' => $id_usuario_logado]); 
    $usuario_completo = $stmt->fetch(PDO::FETCH_ASSOC); 

    if (!$usuario_completo) {
        $_SESSION['erro'] = "Seu registro não foi encontrado. Por favor, faça login novamente.";
        header("Location: logout.php"); 
        exit;
    }
} catch (\PDOException $e) {
    error_log("Erro PDO ao buscar perfil completo: " . $e->getMessage());
    $usuario_completo = $usuario_sessao; 
}

$nome_exibicao    = $usuario_completo['nome_completo'] ?? 'N/A';
$email_exibicao   = $usuario_completo['email'] ?? 'N/A';
$perfil_exibicao  = $usuario_completo['perfil'] ?? 'N/A';
$celular_limpo    = $usuario_completo['telefone_celular'] ?? '';
$fixo_limpo       = $usuario_completo['telefone_fixo'] ?? '';
$data_cadastro    = $usuario_completo['data_cadastro'] ?? '2023-01-01 10:00:00'; 
$data_formatada   = (new DateTime($data_cadastro))->format('d/m/Y');

?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Meu Perfil</h1>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
    <div class="card sombra-suave mb-4">
        <div class="card-body">
            <h5 class="card-title">Informações do Cadastro</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome Completo:</strong> <?= htmlspecialchars($nome_exibicao) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($email_exibicao) ?></p>
                    <p><strong>Telefone Celular:</strong> <?= htmlspecialchars($celular_limpo) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Telefone Fixo:</strong> <?= htmlspecialchars($fixo_limpo) ?: 'Não informado' ?></p>
                    <p><strong>Perfil:</strong> <?= htmlspecialchars($perfil_exibicao) ?></p>
                    <p class="text-muted small">Membro Desde: **<?= $data_formatada ?>**</p>
                </div>
            </div>
            
            <button class="btn btn-sm btn-outline-primary mt-3" type="button" data-bs-toggle="modal" data-bs-target="#modalAlterarDados">
                Editar Meus Dados
            </button>
        </div>
    </div>
    
    <div class="card sombra-suave">
        <div class="card-body">
            <h5 class="card-title">Alterar Senha de Acesso</h5>
            <p class="card-text text-danger small">
                * Você deve informar sua Senha Atual para prosseguir.
            </p>

            <form action="alterar_senha.php" method="post">
                <div class="mb-3">
                    <label for="senha_atual" class="form-label">Senha Atual:</label>
                    <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                </div>
                
                <div class="mb-3">
                    <label for="nova_senha" class="form-label">Nova Senha:</label>
                    <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                </div>

                <div class="mb-3">
                    <label for="confirma_senha" class="form-label">Confirmar Nova Senha:</label>
                    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                </div>
                
                <button class="btn btn-danger" type="submit" name="acao" value="alterar_senha">Alterar Senha</button>
            </form>
        </div>
    </div>
    
    <div class="modal fade" id="modalAlterarDados" tabindex="-1" aria-labelledby="modalAlterarDadosLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAlterarDadosLabel">Editar Informações de Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="perfil.php" method="post">
              <div class="modal-body">
                <p class="small text-muted">Apenas Nome e Telefones podem ser alterados diretamente.</p>
                
                <div class="mb-3">
                    <label for="nome_completo_modal" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome_completo_modal" name="nome_completo" 
                        value="<?= htmlspecialchars($nome_exibicao) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="telefone_celular_modal" class="form-label">Telefone Celular:</label>
                    <input type="text" class="form-control" id="telefone_celular_modal" name="telefone_celular" 
                        value="<?= htmlspecialchars($celular_limpo) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="telefone_fixo_modal" class="form-label">Telefone Fixo (Opcional):</label>
                    <input type="text" class="form-control" id="telefone_fixo_modal" name="telefone_fixo" 
                        value="<?= htmlspecialchars($fixo_limpo) ?>">
                </div>

                <p class="small text-muted">Email: <?= htmlspecialchars($email_exibicao) ?></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit" name="acao" value="alterar_dados">Salvar Alterações</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>