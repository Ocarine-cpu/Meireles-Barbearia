<?php
require_once __DIR__ . '/../config/bd.php'; 
require_once __DIR__ . '/../includes/auth.php';

global $pdo; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $data  = $_POST['data_nascimento'];
    $sexo  = $_POST['sexo'];
    $mae   = trim($_POST['nome_materno']);
    $cpf   = preg_replace('/\D/', '', $_POST['cpf']);
    $email = trim($_POST['email']);
    $cel   = preg_replace('/\D/', '', $_POST['telefone_celular']); // Mantém apenas dígitos
    $fixo  = preg_replace('/\D/', '', $_POST['telefone_fixo']);   // Mantém apenas dígitos
    $cep   = preg_replace('/\D/', '', $_POST['cep']); // Garante que o CEP também seja limpo
    $end   = trim($_POST['endereco']);
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];

    if ($senha !== $confirma) {
        die("As senhas não conferem. <a href='cadastro.php'>Voltar</a>");
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            die("Login já existente. <a href='cadastro.php'>Tente outro</a>");
        }

        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE cpf = ?");
        $stmt->execute([$cpf]);
        if ($stmt->fetch()) {
            die("CPF já cadastrado. <a href='cadastro.php'>Tente outro</a>");
        }

        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            die("E-mail já cadastrado. <a href='cadastro.php'>Tente outro</a>");
        }


        $stmt = $pdo->prepare("INSERT INTO usuarios 
          (nome_completo, data_nascimento, sexo, nome_materno, cpf, email, telefone_celular, telefone_fixo, cep, endereco, login, senha, perfil)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'cliente')");

        $stmt->execute([$nome, $data, $sexo, $mae, $cpf, $email, $cel, $fixo, $cep, $end, $login, $senhaHash]);

        header("Location: login.php?sucesso=1");
        exit;
    } catch (PDOException $e) {
        error_log("Erro de PDO em cadastro_processa.php: " . $e->getMessage());
        die("Erro no banco: Não foi possível completar o cadastro. Por favor, tente novamente mais tarde.");
    }
} else {
    header("Location: cadastro.php");
    exit;
}