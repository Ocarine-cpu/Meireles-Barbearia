<?php
// Para usar PHPMailer, você precisaria instalá-lo via Composer:
// composer require phpmailer/phpmailer
// E então incluir o autoload:
// require 'vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

function enviarEmail2FA(string $destinatario, string $codigo): bool {
    // --- INÍCIO: Configuração PHPMailer (RECOMENDADO PARA PRODUÇÃO) ---
    /*
    $mail = new PHPMailer(true);
    try {
        // Configurações do Servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Seu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'seu_email@example.com'; // Seu e-mail SMTP
        $mail->Password   = 'sua_senha_email';    // Sua senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use ENCRYPTION_SMTPS para porta 465
        $mail->Port       = 587; // Porta SMTP (587 para TLS, 465 para SMTPS)

        // Remetente e Destinatário
        $mail->setFrom('no-reply@meirelesbarbearia.com', 'Meireles Barbearia');
        $mail->addAddress($destinatario);

        // Conteúdo do E-mail
        $mail->isHTML(false); // E-mail em texto puro
        $mail->Subject = "Seu Código de Verificação da Meireles Barbearia";
        $mail->Body    = "Olá,\n\nSeu código de verificação de dois fatores é: " . $codigo . "\n\nEste código é válido por um curto período de tempo. Não o compartilhe com ninguém.\n\nAtenciosamente,\nEquipe Meireles Barbearia";

        $mail->send();
        error_log("E-mail 2FA enviado com PHPMailer para " . $destinatario . " com código: " . $codigo);
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail 2FA com PHPMailer para " . $destinatario . ": " . $mail->ErrorInfo);
        return false;
    }
    */
    // --- FIM: Configuração PHPMailer ---


    // --- INÍCIO: Fallback para mail() do PHP (APENAS PARA TESTES SIMPLES OU AMBIENTES COM MTA CONFIGURADO) ---
    // Em um ambiente de desenvolvimento local (XAMPP/WAMP), você pode precisar configurar o sendmail
    // ou usar uma biblioteca como PHPMailer que pode se conectar a um servidor SMTP externo.
    $assunto = "Seu Código de Verificação da Meireles Barbearia";
    $mensagem = "Olá,\n\nSeu código de verificação de dois fatores é: " . $codigo . "\n\nEste código é válido por um curto período de tempo. Não o compartilhe com ninguém.\n\nAtenciosamente,\nEquipe Meireles Barbearia";
    $headers = "From: no-reply@meirelesbarbearia.com\r\n";
    $headers .= "Reply-To: no-reply@meirelesbarbearia.com\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

    // Para fins de teste, esta função pode retornar true e você verifica o log.
    // Em produção, você verificaria o retorno de mail() ou da biblioteca de e-mail.
    $mail_sent = mail($destinatario, $assunto, $mensagem, $headers);

    if ($mail_sent) {
        error_log("E-mail 2FA enviado (via mail() do PHP) para " . $destinatario . " com código: " . $codigo);
        return true;
    } else {
        error_log("Erro ao enviar e-mail 2FA (via mail() do PHP) para " . $destinatario . ". Verifique a configuração do seu servidor de e-mail.");
        return false;
    }
    // --- FIM: Fallback para mail() do PHP ---
}