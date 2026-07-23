<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require __DIR__ . '/config/mail.php';

$to = $_GET['to'] ?? '';
if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    echo '<form style="font-family:sans-serif;padding:20px;">';
    echo '<h2>Mail Test</h2>';
    echo '<label>Test gönderilecek e-posta (Gmail, Hotmail vb.):</label><br><br>';
    echo '<input type="email" name="to" style="padding:8px;width:300px;" required>';
    echo ' <button type="submit" style="padding:8px 20px;">Gönder</button>';
    echo '</form>';
    exit;
}

echo '<pre style="font-family:monospace;padding:20px;">';
echo "Config dosyasından okunan bilgiler:\n";
echo "  Host     : " . $config['host'] . "\n";
echo "  Port     : " . $config['port'] . "\n";
echo "  Username : " . $config['username'] . "\n";
echo "  Password : " . str_repeat('*', strlen($config['password'])) . "\n";
echo "  To       : " . htmlspecialchars($to) . "\n\n";
echo "Bağlantı kuruluyor...\n\n";

try {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug  = 2;
    $mail->Debugoutput = function($str, $level) {
        echo htmlspecialchars($str) . "\n";
    };
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['encryption'];
    $mail->Port       = $config['port'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'ECU Dosya Servis - Mail Test';
    $mail->Body    = '<p>Bu bir test mailidir. Sistem düzgün çalışıyor.</p>';

    $mail->send();
    echo "\n✅ MAIL KUYRUĞA ALINDI!\n";
    echo "Şimdi '$to' adresini spam dahil kontrol edin.\n";
} catch (Exception $e) {
    echo "\n❌ HATA: " . $e->getMessage() . "\n";
    echo "\nMailer Hata Detayı: " . $mail->ErrorInfo . "\n";
}

echo '</pre>';
