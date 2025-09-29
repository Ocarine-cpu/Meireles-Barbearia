<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => true
    ]);

    $sql = file_get_contents(__DIR__ . '/banco.sql');
    if (!$sql) {
        throw new Exception("Arquivo banco.sql não encontrado ou vazio.");
    }

    $pdo->exec($sql);
    echo "<h2>✅ Banco de dados criado com sucesso!</h2>";
    echo "<p>Agora você pode acessar o sistema com:<br><strong>Login:</strong> admin11<br><strong>Senha:</strong> Dono123!</p>";
} catch (PDOException $e) {
    echo "<h2>❌ Erro com o banco:</h2><pre>" . $e->getMessage() . "</pre>";
} catch (Exception $e) {
    echo "<h2>❌ Erro:</h2><pre>" . $e->getMessage() . "</pre>";
}
