<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

final class MessageRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int
    {
        return $this->db->insert('messages', $data);
    }

    public function getByRequest(int $requestId): array
    {
        return $this->db->fetchAll(
            'SELECT m.*, u.name as sender_name, u.avatar as sender_avatar
             FROM messages m LEFT JOIN users u ON m.user_id = u.id
             WHERE m.request_id = ? ORDER BY m.created_at ASC',
            [$requestId]
        );
    }

    public function markAsRead(int $requestId, int $userId): void
    {
        $this->db->query(
            'UPDATE messages SET is_read = 1 WHERE request_id = ? AND user_id != ? AND is_read = 0',
            [$requestId, $userId]
        );
    }
}
