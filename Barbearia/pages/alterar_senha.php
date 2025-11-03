// pages/alterar_senha.php - CORRIGIDO (Final)
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php'; 

$usuario_sessao = currentUser();
if (!$usuario_sessao || !isset($usuario_sessao['id'])) {
    header("Location: login.php");
    exit;
}
$id_usuario_logado = $usuario_sessao['id']; 

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
    $senha_atual    = $_POST['senha_atual'] ?? '';
    $nova_senha     = $_POST['nova_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    $erro = '';

    if ($nova_senha !== $confirma_senha) {
        $erro = "A Nova Senha e a Confirmação não coincidem.";
    } 
    
    if (!$erro) {
        try {
            $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1");
            $stmt->execute([':id_usuario' => $id_usuario_logado]); 
            $resultado_bd = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $hash_senha_do_bd = $resultado_bd['senha'] ?? ''; 
            
            if (empty($hash_senha_do_bd) || !password_verify($senha_atual, $hash_senha_do_bd)) {
                $erro = "A Senha Atual está incorreta.";
                error_log("Falha na senha para ID: " . $id_usuario_logado . ". Hash vazio: " . (empty($hash_senha_do_bd) ? 'Sim' : 'Não'));
            }
            
        } catch (\PDOException $e) {
            $erro = "Erro de conexão com o banco de dados. Tente novamente.";
            error_log("Erro de PDO ao buscar hash: " . $e->getMessage());
        }
    }

    if (empty($erro)) {
        $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = :hash WHERE id_usuario = :id_usuario");
            $sucesso_bd = $stmt->execute([':hash' => $novo_hash, ':id_usuario' => $id_usuario_logado]);

            if ($sucesso_bd && $stmt->rowCount() > 0) { 
                $_SESSION['sucesso'] = "Sua senha foi alterada com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro interno ao tentar salvar a nova senha. Nenhuma alteração foi realizada.";
            }
        } catch (\PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar o banco de dados. Tente novamente.";
            error_log("Erro de PDO ao atualizar senha: " . $e->getMessage());
        }
        
    } else {
        $_SESSION['erro'] = $erro;
    }
} else {
    $_SESSION['erro'] = "Acesso inválido ao processador de senha.";
}

header("Location: perfil.php");
exit;