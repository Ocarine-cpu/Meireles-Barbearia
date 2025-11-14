<?php
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../fpdf/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,10,'Lista de Usuarios Registrados',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,'Nome',1);
$pdf->Cell(40,10,'Login',1);
$pdf->Cell(60,10,'Ultimo 2FA',1);
$pdf->Cell(30,10,'Cadastro',1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);

$stmt = $pdo->query("
    SELECT u.*, 
    (SELECT segundo_fator FROM logs 
     WHERE id_usuario = u.id_usuario 
     ORDER BY id_log DESC LIMIT 1) AS ultimo_fator
    FROM usuarios u WHERE perfil='cliente'
");

while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(60,10, utf8_decode($u['nome_completo']),1);
    $pdf->Cell(40,10, utf8_decode($u['login']),1);
    $pdf->Cell(60,10, utf8_decode($u['ultimo_fator'] ?? '—'),1);
    $pdf->Cell(30,10, date('d/m/Y', strtotime($u['data_cadastro'])),1);
    $pdf->Ln();
}

$pdf->Output();
