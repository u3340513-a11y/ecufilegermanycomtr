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
        $notifService  = new NotificationService();
        $notifications = $notifService->getForUser($this->userId(), 100);
        $this->view('admin/notifications/index', [
            'pageTitle'     => 'Bildirimler',
            'currentPage'   => 'admin-notifications',
            'notifications' => $notifications,
        ], 'admin');
    }

    /**
     * Returns the unread notification count for the admin JS poller.
     * Called by admin.js every 30 seconds to trigger the bell sound.
     */
    public function unreadCount(Request $request): void
    {
        $notifService = new NotificationService();
        $count        = $notifService->getUnreadCount($this->userId());
        $this->json(['success' => true, 'count' => $count]);
    }

    /**
     * Returns the 15 most recent notifications as JSON for the header dropdown.
     */
    public function recent(Request $request): void
    {
        $notifService  = new NotificationService();
        $notifications = $notifService->getForUser($this->userId(), 15);
        $this->json(['success' => true, 'notifications' => $notifications]);
    }

    /**
     * Marks a single notification as read via AJAX.
     */
    public function markRead(Request $request, string $id): void
    {
        $notifService = new NotificationService();
        $notifService->markAsRead((int) $id, $this->userId());
        $this->json(['success' => true]);
    }

    /**
     * Marks all notifications as read via AJAX.
     */
    public function markAllRead(Request $request): void
    {
        $notifService = new NotificationService();
        $notifService->markAllAsRead($this->userId());
        $this->json(['success' => true]);
    }
}

