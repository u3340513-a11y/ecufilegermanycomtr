<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use App\Services\NotificationService;

final class NotificationController extends Controller
{
    public function index(Request $request): void
    {
        $notifService = new NotificationService();
        $notifications = $notifService->getForUser($this->userId(), 100);
        $this->view('admin/notifications/index', ['pageTitle' => 'Bildirimler', 'currentPage' => 'admin-notifications', 'notifications' => $notifications], 'admin');
    }

    /**
     * Returns the unread notification count for the currently authenticated admin.
     * Called by the admin panel JS every 30 seconds to trigger the bell sound alert.
     */
    public function unreadCount(Request $request): void
    {
        $notifService = new NotificationService();
        $count = $notifService->getUnreadCount($this->userId());
        $this->json(['success' => true, 'count' => $count]);
    }
}
