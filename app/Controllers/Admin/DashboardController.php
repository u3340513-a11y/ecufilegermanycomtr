<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();

        $stats = [
            'total_users'        => $db->count('users', "role = 'user'"),
            'total_requests'     => $db->count('requests'),
            'pending_requests'   => $db->count('requests', "status IN ('pending','reviewing','processing')"),
            'completed_requests' => $db->count('requests', "status = 'completed'"),
            'total_credits_used' => (int)($db->fetch("SELECT COALESCE(SUM(ABS(amount)),0) as t FROM credit_transactions WHERE type='usage'")['t'] ?? 0),
            'pending_payments'   => $db->count('payment_links', "status = 'pending'"),
        ];

        $recentRequests = $db->fetchAll(
            'SELECT r.*, u.name as user_name, b.name as brand_name, vm.name as model_name
             FROM requests r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN brands b ON r.brand_id = b.id
             LEFT JOIN vehicle_models vm ON r.model_id = vm.id
             ORDER BY r.created_at DESC LIMIT 10'
        );

        $recentUsers = $db->fetchAll('SELECT id, name, email, created_at FROM users WHERE role = "user" ORDER BY created_at DESC LIMIT 5');

        $this->view('admin/dashboard', [
            'pageTitle'      => 'Yönetim Paneli',
            'currentPage'    => 'admin-dashboard',
            'stats'          => $stats,
            'recentRequests' => $recentRequests,
            'recentUsers'    => $recentUsers,
        ], 'admin');
    }
}
