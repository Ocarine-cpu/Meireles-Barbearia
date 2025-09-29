<?php
$senha = 'Dono123!';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Senha original: $senha\n";
echo "Hash gerada: $hash\n";
