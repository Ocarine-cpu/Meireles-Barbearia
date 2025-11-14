<?php
require_once __DIR__ . '/../config/bd.php'; 
require_once __DIR__ . '/../includes/auth.php';

session_start();
global $pdo;
function erro($mensagem) {
    header("Location: cadastro.php?msg=" . urlencode($mensagem) . "&tipo=danger");
    exit;
}

function sucesso($mensagem) {
    header("Location: login.php?msg=" . urlencode($mensagem) . "&tipo=success");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']);
    $data  = $_POST['data_nascimento'];
    $sexo  = $_POST['sexo'];
    $mae   = trim($_POST['nome_materno']);
    $cpf   = preg_replace('/\D/', '', $_POST['cpf']);
    $email = trim($_POST['email']);
    $cel   = preg_replace('/\D/', '', $_POST['telefone_celular']);
    $fixo  = preg_replace('/\D/', '', $_POST['telefone_fixo']);
    $cep   = preg_replace('/\D/', '', $_POST['cep']);
    $end   = trim($_POST['endereco']);
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];

    if ($senha !== $confirma) {
        erro("As senhas não conferem.");
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            erro("Este login já está sendo utilizado.");
        }

        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE cpf = ?");
        $stmt->execute([$cpf]);
        if ($stmt->fetch()) {
            erro("Este CPF já está cadastrado.");
        }

        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            erro("Este e-mail já está cadastrado.");
        }


        $stmt = $pdo->prepare("INSERT INTO usuarios 
            (nome_completo, data_nascimento, sexo, nome_materno, cpf, email, telefone_celular, telefone_fixo, cep, endereco, login, senha, perfil)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'cliente')");

        $stmt->execute([$nome, $data, $sexo, $mae, $cpf, $email, $cel, $fixo, $cep, $end, $login, $senhaHash]);

        sucesso("Cadastro realizado com sucesso! Faça login para continuar.");

    } catch (PDOException $e) {
        error_log("Erro de PDO em cadastro_processa.php: " . $e->getMessage());
        erro("Erro interno no servidor. Tente novamente mais tarde.");
    }

} else {
    header("Location: cadastro.php");
    exit;
}
