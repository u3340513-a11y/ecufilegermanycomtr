<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Session;
use Core\Database;

final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();
        $userId = $this->userId();

        $stats = [
            'total_requests'     => $db->count('requests', 'user_id = ?', [$userId]),
            'pending_requests'   => $db->count('requests', 'user_id = ? AND status IN ("pending","reviewing","processing")', [$userId]),
            'completed_requests' => $db->count('requests', 'user_id = ? AND status = "completed"', [$userId]),
            'credit_balance'     => $db->fetch('SELECT credit_balance FROM users WHERE id = ?', [$userId])['credit_balance'] ?? 0,
        ];

        $recentRequests = $db->fetchAll(
            'SELECT r.*, b.name as brand_name, vm.name as model_name
             FROM requests r
             LEFT JOIN brands b ON r.brand_id = b.id
             LEFT JOIN vehicle_models vm ON r.model_id = vm.id
             WHERE r.user_id = ? ORDER BY r.created_at DESC LIMIT 5',
            [$userId]
        );

        $recentTransactions = $db->fetchAll(
            'SELECT * FROM credit_transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5',
            [$userId]
        );

        $this->view('user/dashboard', [
            'pageTitle'          => 'Dashboard',
            'currentPage'        => 'dashboard',
            'stats'              => $stats,
            'recentRequests'     => $recentRequests,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
