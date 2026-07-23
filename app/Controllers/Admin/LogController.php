<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class LogController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();
        $page = (int) ($request->get('page') ?: 1);
        $perPage = 50;
        $total = $db->count('activity_logs');
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $logs = $db->fetchAll(
            'SELECT al.*, u.name as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );

        $this->view('admin/logs/index', ['pageTitle' => 'Aktivite Logları', 'currentPage' => 'admin-logs', 'logs' => $logs, 'total' => $total, 'page' => $page, 'totalPages' => $totalPages], 'admin');
    }
}
