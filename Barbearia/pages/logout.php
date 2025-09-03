<?php
require_once __DIR__ . '/../includes/auth.php';

// Limpa a sessão
session_unset();
session_destroy();

// Redireciona para a página inicial
header("Location: " . basePath() . "/index.php");
exit;
