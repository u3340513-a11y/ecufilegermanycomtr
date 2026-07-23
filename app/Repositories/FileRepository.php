<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

final class FileRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int
    {
        return $this->db->insert('files', $data);
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch('SELECT * FROM files WHERE id = ?', [$id]);
    }

    public function getByRequest(int $requestId): array
    {
        return $this->db->fetchAll(
            'SELECT f.*, u.name as uploader_name FROM files f LEFT JOIN users u ON f.user_id = u.id WHERE f.request_id = ? ORDER BY f.created_at ASC',
            [$requestId]
        );
    }

    public function getNextVersion(int $requestId, string $type): int
    {
        $result = $this->db->fetch(
            'SELECT MAX(version) as max_version FROM files WHERE request_id = ? AND type = ?',
            [$requestId, $type]
        );
        return ($result['max_version'] ?? 0) + 1;
    }
}
