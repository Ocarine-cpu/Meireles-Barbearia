<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica se login já existe
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            // Se já existe, volta com erro
            header("Location: cadastro.php?erro=loginexistente");
            exit;
        }

        // Insere novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome_completo, email, login, senha, perfil) 
                               VALUES (?, ?, ?, ?, 'cliente')");
        $stmt->execute([$nome, $email, $login, $senha]);

        // Redireciona para login com mensagem de sucesso
        header("Location: login.php?sucesso=1");
        exit;
    } catch (PDOException $e) {
        die("Erro no banco: " . $e->getMessage());
    }
} else {
    header("Location: cadastro.php");
    exit;
}
