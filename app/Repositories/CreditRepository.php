<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

final class CreditRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function addTransaction(int $userId, string $type, int $amount, int $balanceAfter, ?string $description = null, ?int $requestId = null, ?int $adminId = null): int
    {
        return $this->db->insert('credit_transactions', [
            'user_id'       => $userId,
            'request_id'    => $requestId,
            'type'          => $type,
            'amount'        => $amount,
            'balance_after' => $balanceAfter,
            'description'   => $description,
            'admin_id'      => $adminId,
        ]);
    }

    public function getByUser(int $userId, int $page = 1, int $perPage = 20): array
    {
        $total = $this->db->count('credit_transactions', 'user_id = ?', [$userId]);
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $data = $this->db->fetchAll(
            'SELECT ct.*, u.name as admin_name FROM credit_transactions ct
             LEFT JOIN users u ON ct.admin_id = u.id
             WHERE ct.user_id = ? ORDER BY ct.created_at DESC LIMIT ? OFFSET ?',
            [$userId, $perPage, $offset]
        );

        return compact('data', 'total', 'totalPages', 'page', 'perPage');
    }

    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $total = $this->db->count('credit_transactions');
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $data = $this->db->fetchAll(
            'SELECT ct.*, u.name as user_name, u.email as user_email, a.name as admin_name
             FROM credit_transactions ct
             LEFT JOIN users u ON ct.user_id = u.id
             LEFT JOIN users a ON ct.admin_id = a.id
             ORDER BY ct.created_at DESC LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );

        return compact('data', 'total', 'totalPages', 'page', 'perPage');
    }
}
