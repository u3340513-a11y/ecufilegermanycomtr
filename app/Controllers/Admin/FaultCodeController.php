<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;
use App\Helpers\Slug;

final class FaultCodeController extends Controller
{
    private Database $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
    }

    public function index(Request $request): void
    {
        $search = trim($request->get('q') ?? '');
        $page   = max(1, (int) ($request->get('page') ?: 1));
        $limit  = 50;
        $offset = ($page - 1) * $limit;

        $where  = '1=1';
        $params = [];

        if ($search !== '') {
            $where  .= ' AND (code LIKE ? OR title LIKE ?)';
            $like    = "%{$search}%";
            $params  = [$like, $like];
        }

        $total = $this->db->count('fault_codes', $where, $params);

        $codes = $this->db->fetchAll(
            "SELECT id, code, title, is_published, created_at
             FROM fault_codes WHERE {$where}
             ORDER BY code ASC LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $this->view('admin/fault-codes/index', [
            'pageTitle'   => 'Arıza Kodları',
            'currentPage' => 'admin-fault-codes',
            'codes'       => $codes,
            'total'       => $total,
            'page'        => $page,
            'totalPages'  => (int) ceil($total / $limit),
            'search'      => $search,
        ], 'admin');
    }

    public function create(Request $request): void
    {
        $this->view('admin/fault-codes/form', ['pageTitle' => 'Yeni Arıza Kodu', 'currentPage' => 'admin-fault-codes', 'code' => null], 'admin');
    }

    public function store(Request $request): void
    {
        $this->db->insert('fault_codes', [
            'code' => $request->post('code'), 'title' => $request->post('title'),
            'description' => $request->post('description'), 'solution' => $request->post('solution'),
            'meta_title' => $request->post('meta_title'), 'meta_description' => $request->post('meta_description'),
            'slug' => Slug::unique($request->post('code') . '-' . $request->post('title'), 'fault_codes', 'slug'),
            'is_published' => (int) $request->post('is_published', '0'),
        ]);
        $this->withSuccess('Arıza kodu eklendi.', '/admin/fault-codes');
    }

    public function edit(Request $request, string $id): void
    {
        $code = $this->db->fetch('SELECT * FROM fault_codes WHERE id = ?', [(int)$id]);
        if (!$code) { $this->redirect('/admin/fault-codes'); }
        $this->view('admin/fault-codes/form', ['pageTitle' => 'Arıza Kodu Düzenle', 'currentPage' => 'admin-fault-codes', 'code' => $code], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        $this->db->update('fault_codes', [
            'code' => $request->post('code'), 'title' => $request->post('title'),
            'description' => $request->post('description'), 'solution' => $request->post('solution'),
            'meta_title' => $request->post('meta_title'), 'meta_description' => $request->post('meta_description'),
            'is_published' => (int) $request->post('is_published', '0'),
        ], 'id = ?', [(int)$id]);
        $this->withSuccess('Arıza kodu güncellendi.', '/admin/fault-codes');
    }

    public function delete(Request $request, string $id): void
    {
        $this->db->delete('fault_codes', 'id = ?', [(int)$id]);
        $this->withSuccess('Arıza kodu silindi.', '/admin/fault-codes');
    }
}
