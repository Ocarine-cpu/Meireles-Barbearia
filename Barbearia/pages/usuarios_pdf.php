<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../lib/fpdf/fpdf.php'; // tem que ter a biblioteca FPDF rapaziada

$usuario = currentUser();
if (!$usuario || $usuario['perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
$stmt = $pdo->query("SELECT nome_completo, email, telefone, cep, login FROM usuarios WHERE perfil = 'cliente' ORDER BY nome_completo ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Parte de criar o pdf:
$pdf = new FPDF();
$pdf->AddPage(); 
$pdf->SetFont('Arial', 'B', 16); 
$pdf->Cell(0, 10, 'Lista de Usuarios - Meireles Barbearia', 0, 1, 'C'); 
$pdf->Ln(8); 

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 8, 'Nome Completo', 1);
$pdf->Cell(40, 8, 'Login', 1);
$pdf->Cell(60, 8, 'E-mail', 1);
$pdf->Cell(30, 8, 'Telefone', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 11);


// Tá riscado ali pq é um comando obsoleto, mas funciona por hora. Não é erro
foreach ($usuarios as $u) {
    $pdf->Cell(60, 8, utf8_decode($u['nome_completo']), 1);
    $pdf->Cell(40, 8, utf8_decode($u['login']), 1);
    $pdf->Cell(60, 8, utf8_decode($u['email']), 1);
    $pdf->Cell(30, 8, utf8_decode($u['telefone']), 1);
    $pdf->Ln();
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, utf8_decode('Gerado em: ') . date('d/m/Y H:i:s'), 0, 1, 'R');

$pdf->Output('D', 'usuarios_barbearia.pdf');
exit;
?>