<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use App\Services\NotificationService;

final class NotificationController extends Controller
{
    private NotificationService $notifService;

    public function __construct()
    {
        parent::__construct();
        $this->notifService = new NotificationService();
    }

    public function index(Request $request): void
    {
        $notifications = $this->notifService->getForUser($this->userId(), 50);
        $this->view('user/notifications', [
            'pageTitle'     => 'Bildirimler',
            'currentPage'   => 'notifications',
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, string $id): void
    {
        $this->notifService->markAsRead((int) $id, $this->userId());
        $this->json(['success' => true]);
    }

    public function markAllAsRead(Request $request): void
    {
        $this->notifService->markAllAsRead($this->userId());
        $this->json(['success' => true]);
    }

    public function unreadCount(Request $request): void
    {
        $count = $this->notifService->getUnreadCount($this->userId());
        $this->json(['success' => true, 'count' => $count]);
    }
}
