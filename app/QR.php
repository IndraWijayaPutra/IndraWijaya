<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '..vendor/autoload.php'; // Pastikan ini mengarah ke lokasi autoload.php Anda

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Ganti dengan server SMTP Anda
    $mail->SMTPAuth = true;
    $mail->Username = 'username@example.com'; // Ganti dengan username SMTP Anda
    $mail->Password = 'password'; // Ganti dengan password SMTP Anda
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('from@example.com', 'Your Name');
    $mail->addAddress($pemesanan['email']); // Email pengunjung

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Konfirmasi Pemesanan Anda';
    $mail->Body    = 'Terima kasih telah memesan. Status pemesanan Anda: ' . ucfirst($pemesanan['status']) . '.';

    $mail->send();
    echo 'Email telah dikirim.';
} catch (Exception $e) {
    echo "Email tidak dapat dikirim. Mailer Error: {$mail->ErrorInfo}";
}
?>
