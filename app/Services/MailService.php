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
        $isDeduction = in_array($type, ['admin_deduct', 'usage'], true);

        $typeLabel = match ($type) {
            'purchase'     => 'Satın Alma',
            'admin_add'    => 'Admin Tarafından Ekleme',
            'admin_deduct' => 'Admin Tarafından Düşme',
            'debt'         => 'Borç Olarak Ekleme',
            'refund'       => 'İade',
            default        => $type,
        };

        $action = $isDeduction ? 'düşüldü' : 'eklendi';

        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p>Hesabınızdan <strong>{$amount} kredi</strong> ({$typeLabel}) {$action}.</p>
            <p style='text-align:center;margin:20px 0;'>
                <a href='" . \Core\App::url('dashboard/credits') . "' style='color:#2563eb;'>Kredi Geçmişinizi Görüntüleyin</a>
            </p>
        ";

        return $this->send($email, 'Kredi Bilgilendirmesi', $body);
    }

    /**
     * Sends an alert email to the admin inbox (ADMIN_NOTIFY_EMAIL env variable).
     * Used to notify the admin of new requests, new messages, and payment events
     * without requiring them to keep the admin panel open.
     *
     * @param string $subject Short subject line for the email.
     * @param string $body    HTML body content.
     */
    public function sendAdminNotification(string $subject, string $body): bool
    {
        $adminEmail = env('ADMIN_NOTIFY_EMAIL', '');
        if ($adminEmail === '') {
            return false;
        }

        return $this->send($adminEmail, $subject, $body);
    }

    /**
     * Notifies the admin that a new request has been submitted by a user.
     *
     * @param string $userName   Display name of the user who submitted the request.
     * @param string $ticketNo   Ticket number for the new request.
     * @param string $adminUrl   Full URL to the admin request detail page.
     */
    public function sendAdminNewRequestAlert(string $userName, string $ticketNo, string $adminUrl): bool
    {
        $body = "
            <p>Merhaba,</p>
            <p><strong>{$userName}</strong> tarafından yeni bir talep oluşturuldu.</p>
            <p style='text-align:center;margin:20px 0;'>
                <span style='background:#f3f4f6;padding:8px 24px;border-radius:6px;font-weight:600;font-size:16px;'>#{$ticketNo}</span>
            </p>
            <p style='text-align:center;'>
                <a href='{$adminUrl}' style='background:#0ea5e9;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;'>Talebi İncele</a>
            </p>
        ";

        return $this->sendAdminNotification("🆕 Yeni Talep #{$ticketNo} — {$userName}", $body);
    }

    /**
     * Notifies the admin that a user sent a new message on a request.
     *
     * @param string $userName Display name of the message sender.
     * @param string $ticketNo Ticket number the message belongs to.
     * @param string $adminUrl Full URL to the admin request detail page.
     */
    public function sendAdminNewMessageAlert(string $userName, string $ticketNo, string $adminUrl): bool
    {
        $body = "
            <p>Merhaba,</p>
            <p><strong>{$userName}</strong>, <strong>#{$ticketNo}</strong> numaralı talebe yeni bir mesaj gönderdi.</p>
            <p style='text-align:center;margin:20px 0;'>
                <a href='{$adminUrl}' style='background:#0ea5e9;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;'>Mesajı Görüntüle</a>
            </p>
        ";

        return $this->sendAdminNotification("💬 Yeni Mesaj #{$ticketNo} — {$userName}", $body);
    }

    /**
     * Notifies the user that their account has been manually approved by an admin.
     * Intentionally kept link-free to minimise spam filter triggers.
     *
     * @param string $email Recipient e-mail address.
     * @param string $name  Recipient display name.
     */
    public function sendAccountApprovedEmail(string $email, string $name): bool
    {
        $loginUrl = App::url('login');
        $body = "
            <p>Merhaba <strong>{$name}</strong>,</p>
            <p>Hesabınız yönetici tarafından onaylandı. Artık sisteme giriş yapabilirsiniz.</p>
            <p style='text-align:center;margin:30px 0;'>
                <a href='{$loginUrl}' style='background:#16a34a;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;'>Giriş Yap</a>
            </p>
            <p style='color:#6b7280;font-size:13px;'>Herhangi bir sorunuz olursa lütfen bizimle iletişime geçin.</p>
        ";

        return $this->send($email, 'Hesabınız Onaylandı', $body);
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
