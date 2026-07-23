<?php

declare(strict_types=1);

namespace App\Services;

use Core\Config;
use Core\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class MailService
{
    private array $config;

    public function __construct()
    {
        $this->config = Config::get('mail', []);
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $this->config['host'] ?? 'localhost';
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['username'] ?? '';
            $mail->Password   = $this->config['password'] ?? '';
            $mail->SMTPSecure = $this->config['encryption'] ?? 'tls';
            $mail->Port       = $this->config['port'] ?? 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(
                $this->config['from_email'] ?? 'noreply@example.com',
                $this->config['from_name'] ?? 'ECU Dosya Servis'
            );

            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $this->wrapInTemplate($subject, $body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mail gönderilemedi: ' . $e->getMessage());
            return false;
        }
    }

    public function sendVerificationEmail(string $email, string $name, string $token): bool
    {
        $url = App::url('verify-email/' . $token);
        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p>Hesabınızı doğrulamak için aşağıdaki bağlantıya tıklayın:</p>
            <p style='text-align:center;margin:30px 0;'>
                <a href='{$url}' style='background:#2563eb;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;'>E-posta Doğrula</a>
            </p>
            <p style='color:#6b7280;font-size:13px;'>Bu bağlantı 24 saat geçerlidir.</p>
        ";

        return $this->send($email, 'E-posta Doğrulama', $body);
    }

    public function sendPasswordResetEmail(string $email, string $name, string $token): bool
    {
        $url = App::url('reset-password/' . $token);
        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p>Şifre sıfırlama talebiniz alındı. Aşağıdaki bağlantıya tıklayarak yeni şifrenizi belirleyebilirsiniz:</p>
            <p style='text-align:center;margin:30px 0;'>
                <a href='{$url}' style='background:#2563eb;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;'>Şifremi Sıfırla</a>
            </p>
            <p style='color:#6b7280;font-size:13px;'>Bu bağlantı 1 saat geçerlidir. Şifre sıfırlama talebinde bulunmadıysanız bu e-postayı görmezden gelebilirsiniz.</p>
        ";

        return $this->send($email, 'Şifre Sıfırlama', $body);
    }

    public function sendRequestNotification(string $email, string $name, string $ticketNo, string $status): bool
    {
        $statusLabels = [
            'pending'    => 'Bekliyor',
            'reviewing'  => 'İnceleniyor',
            'processing' => 'İşlemde',
            'revision'   => 'Revizyon',
            'completed'  => 'Tamamlandı',
            'cancelled'  => 'İptal',
        ];

        $label = $statusLabels[$status] ?? $status;
        $url = App::url('dashboard/requests');

        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p><strong>#{$ticketNo}</strong> numaralı talebinizin durumu güncellendi:</p>
            <p style='text-align:center;margin:20px 0;'>
                <span style='background:#f3f4f6;padding:8px 24px;border-radius:6px;font-weight:600;font-size:16px;'>{$label}</span>
            </p>
            <p style='text-align:center;'>
                <a href='{$url}' style='color:#2563eb;'>Talep Detayını Görüntüle</a>
            </p>
        ";

        return $this->send($email, "Talep Durumu Güncellendi - #{$ticketNo}", $body);
    }

    public function sendCreditNotification(string $email, string $name, int $amount, string $type): bool
    {
        $typeLabel = match ($type) {
            'purchase'  => 'Satın Alma',
            'admin_add' => 'Admin Tarafından Ekleme',
            'refund'    => 'İade',
            default     => $type,
        };

        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p>Hesabınıza <strong>{$amount} kredi</strong> ({$typeLabel}) eklendi.</p>
            <p style='text-align:center;margin:20px 0;'>
                <a href='" . App::url('dashboard/credits') . "' style='color:#2563eb;'>Kredi Geçmişinizi Görüntüleyin</a>
            </p>
        ";

        return $this->send($email, 'Kredi Bilgilendirmesi', $body);
    }

    private function wrapInTemplate(string $title, string $body): string
    {
        $siteName = Config::get('app.name', 'ECU Dosya Servis');

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='utf-8'></head>
        <body style='margin:0;padding:0;background:#f9fafb;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif;'>
            <div style='max-width:600px;margin:0 auto;padding:40px 20px;'>
                <div style='background:#fff;border-radius:12px;padding:40px;box-shadow:0 1px 3px rgba(0,0,0,0.1);'>
                    <div style='text-align:center;margin-bottom:30px;'>
                        <h2 style='color:#111827;margin:0;'>{$siteName}</h2>
                    </div>
                    {$body}
                </div>
                <p style='text-align:center;color:#9ca3af;font-size:12px;margin-top:20px;'>
                    © " . date('Y') . " {$siteName}. Tüm hakları saklıdır.
                </p>
            </div>
        </body>
        </html>";
    }
}
