<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

final class NotificationRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(int $userId, string $title, string $content, string $type, ?string $link): int
    {
        return $this->db->insert('notifications', [
            'user_id' => $userId,
            'title'   => $title,
            'content' => $content,
            'type'    => $type,
            'link'    => $link,
        ]);
    }

    public function getByUser(int $userId, int $limit = 20): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?',
            [$userId, $limit]
        );
    }

    public function unreadCount(int $userId): int
    {
        return $this->db->count('notifications', 'user_id = ? AND is_read = 0', [$userId]);
    }

    public function markRead(int $id, int $userId): void
    {
        $this->db->update('notifications', ['is_read' => 1], 'id = ? AND user_id = ?', [$id, $userId]);
    }

    public function markAllRead(int $userId): void
    {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0', [$userId]);
    }
}
