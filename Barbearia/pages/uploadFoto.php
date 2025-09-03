<?php
require_once __DIR__ . '/../includes/auth.php';
$usuario = currentUser();

if (!$usuario) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $arquivo = $_FILES['foto'];

    if ($arquivo['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $permitidas)) {
            $novoNome = 'user_' . $usuario['id'] . '.' . $ext;
            $destino = __DIR__ . '/../uploads/perfis/' . $novoNome;

            // Cria a pasta se não existir
            if (!is_dir(__DIR__ . '/../uploads/perfis')) {
                mkdir(__DIR__ . '/../uploads/perfis', 0777, true);
            }

            if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
                // Caminho relativo para salvar no banco
                $caminhoRelativo = $caminhoBase . '/uploads/perfis/' . $novoNome;

                // Atualizar banco
                $pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");
                $stmt = $pdo->prepare("UPDATE usuarios SET foto = ? WHERE id_usuario = ?");
                $stmt->execute([$caminhoRelativo, $usuario['id']]);

                // Atualiza sessão
                $_SESSION['user']['foto'] = $caminhoRelativo;

                header("Location: perfil.php");
                exit;
            } else {
                echo "Erro ao mover arquivo.";
            }
        } else {
            echo "Formato inválido. Permitidos: jpg, jpeg, png, gif.";
        }
    } else {
        echo "Erro no upload.";
    }
}
