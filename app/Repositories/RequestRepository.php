<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

final class RequestRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch(
            'SELECT r.*, b.name as brand_name, vm.name as model_name, g.name as generation_name,
                    e.name as engine_name, ec.name as ecu_name, rm.name as reading_method_name,
                    u.name as user_name, u.email as user_email,
                    s.name as stage_name, s.slug as stage_slug, s.base_credit as stage_base_credit,
                    tt.name as transmission_type_name
             FROM requests r
             LEFT JOIN brands b ON r.brand_id = b.id
             LEFT JOIN vehicle_models vm ON r.model_id = vm.id
             LEFT JOIN generations g ON r.generation_id = g.id
             LEFT JOIN engines e ON r.engine_id = e.id
             LEFT JOIN ecus ec ON r.ecu_id = ec.id
             LEFT JOIN reading_methods rm ON r.reading_method_id = rm.id
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN stages s ON r.stage_id = s.id
             LEFT JOIN transmission_types tt ON r.transmission_type_id = tt.id
             WHERE r.id = ?',
            [$id]
        );
    }

    public function getByUser(int $userId, int $page = 1, int $perPage = 15): array
    {
        $total = $this->db->count('requests', 'user_id = ?', [$userId]);
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $data = $this->db->fetchAll(
            'SELECT r.*, b.name as brand_name, vm.name as model_name
             FROM requests r
             LEFT JOIN brands b ON r.brand_id = b.id
             LEFT JOIN vehicle_models vm ON r.model_id = vm.id
             WHERE r.user_id = ? ORDER BY r.created_at DESC LIMIT ? OFFSET ?',
            [$userId, $perPage, $offset]
        );

        return compact('data', 'total', 'totalPages', 'page', 'perPage');
    }

    public function getAll(int $page = 1, int $perPage = 15, ?string $status = null): array
    {
        $where = '1=1';
        $params = [];
        if ($status) {
            $where .= ' AND r.status = ?';
            $params[] = $status;
        }

        $total = $this->db->count('requests r', $where, $params);
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $allParams = array_merge($params, [$perPage, $offset]);
        $data = $this->db->fetchAll(
            "SELECT r.*, b.name as brand_name, vm.name as model_name, u.name as user_name, u.email as user_email
             FROM requests r
             LEFT JOIN brands b ON r.brand_id = b.id
             LEFT JOIN vehicle_models vm ON r.model_id = vm.id
             LEFT JOIN users u ON r.user_id = u.id
             WHERE {$where} ORDER BY r.created_at DESC LIMIT ? OFFSET ?",
            $allParams
        );

        return compact('data', 'total', 'totalPages', 'page', 'perPage');
    }

    public function create(array $data): int
    {
        return $this->db->insert('requests', $data);
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->db->update('requests', ['status' => $status], 'id = ?', [$id]);
    }

    public function addServices(int $requestId, array $services): void
    {
        foreach ($services as $service) {
            $this->db->insert('request_services', [
                'request_id'         => $requestId,
                'service_package_id' => (int) $service['id'],
                'credit_cost'        => (int) $service['credit_cost'],
            ]);
        }
    }

    public function getServices(int $requestId): array
    {
        return $this->db->fetchAll(
            'SELECT rs.*, sp.name as service_name FROM request_services rs
             JOIN service_packages sp ON rs.service_package_id = sp.id
             WHERE rs.request_id = ?',
            [$requestId]
        );
    }

    public function generateTicketNo(): string
    {
        do {
            $ticket = 'ECU-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while ($this->db->count('requests', 'ticket_no = ?', [$ticket]) > 0);

        return $ticket;
    }

    public function countByStatus(): array
    {
        return $this->db->fetchAll(
            'SELECT status, COUNT(*) as total FROM requests GROUP BY status'
        );
    }
}
