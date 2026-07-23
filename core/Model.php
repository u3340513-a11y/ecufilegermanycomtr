<?php

declare(strict_types=1);

namespace Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    public function all(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}"
        );
    }

    public function where(string $column, mixed $value): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }

    public function findBy(string $column, mixed $value): ?array
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }

    public function create(array $data): int
    {
        $filtered = $this->filterFillable($data);

        if (empty($filtered)) {
            throw new \InvalidArgumentException('Veri sağlanmadı.');
        }

        return $this->db->insert($this->table, $filtered);
    }

    public function update(int $id, array $data): int
    {
        $filtered = $this->filterFillable($data);

        if (empty($filtered)) {
            return 0;
        }

        return $this->db->update(
            $this->table,
            $filtered,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function deleteRecord(int $id): int
    {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function count(string $where = '1=1', array $params = []): int
    {
        return $this->db->count($this->table, $where, $params);
    }

    public function paginate(int $page, int $perPage = 15, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array
    {
        $total = $this->count($where, $params);
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $rows = $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy} LIMIT ? OFFSET ?",
            [...$params, $perPage, $offset]
        );

        return [
            'data'        => $rows,
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page' => $page,
            'total_pages' => $totalPages,
        ];
    }

    public function exists(string $column, mixed $value, ?int $exceptId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$column} = ?";
        $params = [$value];

        if ($exceptId !== null) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $exceptId;
        }

        $result = $this->db->fetch($sql, $params);
        return ($result['total'] ?? 0) > 0;
    }

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function getTable(): string
    {
        return $this->table;
    }
}
