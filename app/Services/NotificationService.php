<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NotificationRepository;

final class NotificationService
{
    private NotificationRepository $repo;

    public function __construct()
    {
        $this->repo = new NotificationRepository();
    }

    public function create(int $userId, string $title, string $content, string $type = 'info', ?string $link = null): int
    {
        return $this->repo->create($userId, $title, $content, $type, $link);
    }

    public function getForUser(int $userId, int $limit = 20): array
    {
        return $this->repo->getByUser($userId, $limit);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->repo->unreadCount($userId);
    }

    public function markAsRead(int $id, int $userId): void
    {
        $this->repo->markRead($id, $userId);
    }

    public function markAllAsRead(int $userId): void
    {
        $this->repo->markAllRead($userId);
    }

    public function notifyRequestUpdate(int $userId, string $ticketNo, string $status): void
    {
        $labels = [
            'pending' => 'Bekliyor', 'reviewing' => 'İnceleniyor', 'processing' => 'İşlemde',
            'revision' => 'Revizyon', 'completed' => 'Tamamlandı', 'cancelled' => 'İptal',
        ];
        $label = $labels[$status] ?? $status;

        $this->create(
            $userId,
            'Talep Güncellendi',
            "#{$ticketNo} numaralı talebinizin durumu: {$label}",
            'request',
            '/dashboard/requests'
        );
    }

    public function notifyNewMessage(int $userId, string $ticketNo): void
    {
        $this->create(
            $userId,
            'Yeni Mesaj',
            "#{$ticketNo} numaralı talebinize yeni bir mesaj geldi.",
            'message',
            '/dashboard/requests'
        );
    }

    /**
     * Notifies the user that credits were refunded due to a request cancellation.
     *
     * @param int    $userId   The user receiving the refund notification.
     * @param int    $amount   Number of credits refunded.
     * @param string $ticketNo Ticket number of the cancelled request.
     */
    public function notifyRefund(int $userId, int $amount, string $ticketNo): void
    {
        $this->create(
            $userId,
            'Kredi İadesi',
            "#{$ticketNo} numaralı talebiniz iptal edildi. {$amount} kredi bakiyenize iade edildi.",
            'credit',
            '/dashboard/credits'
        );
    }

    public function notifyCreditAdded(int $userId, int $amount): void
    {
        $this->create(
            $userId,
            'Kredi Eklendi',
            "Hesabınıza {$amount} kredi eklendi.",
            'credit',
            '/dashboard/credits'
        );
    }

    public function notifyCreditDeducted(int $userId, int $amount): void
    {
        $this->create(
            $userId,
            'Kredi Düşüldü',
            "Hesabınızdan {$amount} kredi düşüldü.",
            'credit',
            '/dashboard/credits'
        );
    }

    public function notifyPaymentApproved(int $userId, int $amount): void
    {
        $this->create(
            $userId,
            'Ödeme Onaylandı',
            "Ödemeniz onaylandı ve {$amount} kredi hesabınıza tanımlandı.",
            'payment',
            '/dashboard/credits'
        );
    }
}
