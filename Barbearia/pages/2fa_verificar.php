<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");

$user_id = $_SESSION['2fa_user_id'];

// Recupera dados do usuário (apenas os campos existentes)
$stmt = $pdo->prepare("SELECT id_usuario, nome_materno, data_nascimento, cep, perfil FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php");
    exit;
}

// Gera pergunta aleatória uma única vez por sessão
if (!isset($_SESSION['pergunta_2fa'])) {
    $perguntas = [
        'nome_materno' => 'Qual o nome da sua mãe?',
        'data_nascimento' => 'Qual a data do seu nascimento?',
        'cep' => 'Qual o CEP do seu endereço?'
    ];

    $chaves = array_keys($perguntas);
    $campo = $chaves[array_rand($chaves)];

    $_SESSION['pergunta_2fa'] = [
        'campo' => $campo,
        'texto' => $perguntas[$campo],
        'tentativas' => 0
    ];
}

$erro = null;

// Verifica envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta = trim($_POST['resposta']);
    $campo = $_SESSION['pergunta_2fa']['campo'];

    if ($resposta === '') {
        $erro = "Por favor, preencha a resposta.";
    } else {
        $_SESSION['pergunta_2fa']['tentativas']++;

        $valorCorreto = $usuario[$campo];

        // Normaliza formatos de data e CEP
        if ($campo === 'data_nascimento') {
            $valorCorreto = date('d/m/Y', strtotime($valorCorreto));
        }

        // Comparação case-insensitive
        if (strcasecmp($resposta, $valorCorreto) === 0) {

            // ✅ Login bem-sucedido — cria sessão do usuário
            $_SESSION['user'] = [
                'id'     => $usuario['id_usuario'],
                'login'  => 'usuario_' . $usuario['id_usuario'], // apenas para exibir algo
                'nome'   => 'Usuário ' . $usuario['id_usuario'], // pode mudar depois
                'perfil' => $usuario['perfil'] ?? 'cliente'
            ];

            $_SESSION['2fa_verified'] = true;

            // Limpa variáveis temporárias
            unset($_SESSION['2fa_user_id'], $_SESSION['codigo_2fa_simulado'], $_SESSION['pergunta_2fa']);

            // ✅ Redireciona para a página inicial logado
            header("Location: ../index.php");
            exit;
        } else {
            if ($_SESSION['pergunta_2fa']['tentativas'] >= 3) {
                // 3 tentativas erradas → volta ao login
                unset($_SESSION['2fa_user_id'], $_SESSION['codigo_2fa_simulado'], $_SESSION['pergunta_2fa']);
                $_SESSION['erro_login'] = "3 tentativas sem sucesso! Favor realizar Login novamente.";
                header("Location: login.php");
                exit;
            }

            $erro = "Resposta incorreta. Tentativas restantes: " . (3 - $_SESSION['pergunta_2fa']['tentativas']);
        }
    }
}
?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="post">
      <h1 class="h4 mb-3 fw-normal text-center">Verificação 2FA</h1>

      <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="resposta" name="resposta" placeholder="Resposta" required>
        <label for="resposta"><?= htmlspecialchars($_SESSION['pergunta_2fa']['texto']) ?></label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Verificar</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
