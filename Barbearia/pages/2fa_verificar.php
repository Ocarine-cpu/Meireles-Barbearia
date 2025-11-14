<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=barbearia;charset=utf8mb4", "root", "");

$user_id = $_SESSION['2fa_user_id'];

$stmt = $pdo->prepare("
    SELECT id_usuario, nome_materno, data_nascimento, cep, perfil,
           login, nome_completo, foto
    FROM usuarios 
    WHERE id_usuario = ?
");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php");
    exit;
}

function gerarPergunta()
{
    $perguntas = [
        'nome_materno'   => 'Qual o nome da sua mãe?',
        'data_nascimento' => 'Qual a data do seu nascimento?',
        'cep'            => 'Qual o CEP do seu endereço?'
    ];

    $chaves = array_keys($perguntas);
    $campo = $chaves[array_rand($chaves)];

    return [
        'campo' => $campo,
        'texto' => $perguntas[$campo]
    ];
}

if (!isset($_SESSION['pergunta_2fa'])) {
    $_SESSION['pergunta_2fa'] = gerarPergunta();
    $_SESSION['tentativas_2fa'] = 0;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta = trim($_POST['resposta']);
    $campo = $_SESSION['pergunta_2fa']['campo'];

    if ($resposta === '') {
        $erro = "Por favor, preencha a resposta.";
    } else {
        $_SESSION['tentativas_2fa']++;

        $valorCorreto = $usuario[$campo];
        $correto = false;

        if ($campo === 'data_nascimento') {
            $convertida = date('Y-m-d', strtotime(str_replace('/', '-', $resposta)));
            $valorCorreto = date('Y-m-d', strtotime($valorCorreto));
            $correto = ($convertida === $valorCorreto);
        }
        elseif ($campo === 'cep') {
            $correto = preg_replace('/\D/', '', $resposta) === preg_replace('/\D/', '', $valorCorreto);
        }
        else {
            $correto = (strcasecmp($resposta, $valorCorreto) === 0);
        }

        if ($correto) {
            $_SESSION['user'] = [
                'id'     => $usuario['id_usuario'],
                'login'  => $usuario['login'],
                'nome'   => $usuario['nome_completo'],
                'perfil' => $usuario['perfil'],
                'foto'   => $usuario['foto'] ?? null
            ];

            $log = $pdo->prepare("
                INSERT INTO logs (id_usuario, acao, segundo_fator)
                VALUES (?, 'login_2fa', ?)
            ");
            $log->execute([$usuario['id_usuario'], $_SESSION['pergunta_2fa']['texto']]);

            unset($_SESSION['2fa_user_id'], $_SESSION['pergunta_2fa'], $_SESSION['tentativas_2fa']);

            header("Location: ../index.php");
            exit;
        } else {

            if ($_SESSION['tentativas_2fa'] >= 3) {
                unset($_SESSION['pergunta_2fa'], $_SESSION['2fa_user_id'], $_SESSION['tentativas_2fa']);
                $_SESSION['erro_login'] = "3 tentativas sem sucesso! Favor realizar Login novamente.";
                header("Location: login.php");
                exit;
            }

            
            $_SESSION['pergunta_2fa'] = gerarPergunta();
            $erro = "Resposta incorreta. Tentativas restantes: " . (3 - $_SESSION['tentativas_2fa']);
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<!-- Toasts do Bootstrap -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <?php if ($erro): ?>
  <div id="toastErro" class="toast align-items-center text-bg-danger border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body">
        <?= htmlspecialchars($erro) ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Conteúdo principal -->
<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="col-11 col-sm-8 col-md-6 col-lg-4 fade-in">
    <form class="p-4 border rounded-3 bg-body sombra-suave" method="post">
      <h1 class="h4 mb-3 fw-bold text-center">Verificação 2FA</h1>

      <p class="text-center text-secondary">
        Tentativa <strong><?= $_SESSION['tentativas_2fa'] + 1 ?></strong> de 3
      </p>

      <div class="form-floating mb-3">
        <input 
            type="text" 
            class="form-control <?= $erro ? 'is-invalid' : '' ?>" 
            id="resposta" 
            name="resposta"
            placeholder="Resposta" required>
        <label for="resposta"><?= htmlspecialchars($_SESSION['pergunta_2fa']['texto']) ?></label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Verificar</button>
    </form>
  </div>
</div>

<style>
.fade-in {
    animation: fade .6s ease-in-out;
}
@keyframes fade {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toastErro = document.getElementById('toastErro');
    if (toastErro) {
        const toast = new bootstrap.Toast(toastErro);
        toast.show();
    }
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
