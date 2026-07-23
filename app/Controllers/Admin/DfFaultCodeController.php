<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class DfFaultCodeController extends Controller
{
    private Database $db;
    public function __construct() { parent::__construct(); $this->db = Database::getInstance(); }

    public function index(Request $request): void
    {
        $search = trim($request->get('q') ?? '');
        $where  = '1=1';
        $params = [];

        if ($search !== '') {
            $where  .= ' AND (df_code LIKE ? OR p_code LIKE ? OR description LIKE ?)';
            $like    = "%{$search}%";
            $params  = [$like, $like, $like];
        }

        $codes = $this->db->fetchAll(
            "SELECT * FROM df_fault_codes WHERE {$where} ORDER BY df_code ASC",
            $params
        );

        $this->view('admin/df-fault-codes/index', [
            'pageTitle'   => 'DF Arıza Kodları',
            'currentPage' => 'admin-df-fault-codes',
            'codes'       => $codes,
            'search'      => $search,
        ], 'admin');
    }

    public function create(Request $request): void
    {
        $this->view('admin/df-fault-codes/form', [
            'pageTitle'   => 'Yeni DF Kodu',
            'currentPage' => 'admin-df-fault-codes',
            'code'        => null,
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $this->db->insert('df_fault_codes', [
            'df_code'     => strtoupper(trim($request->post('df_code'))),
            'p_code'      => strtoupper(trim($request->post('p_code'))),
            'description' => trim($request->post('description')),
        ]);
        $this->withSuccess('DF kodu eklendi.', '/admin/df-fault-codes');
    }

    public function edit(Request $request, string $id): void
    {
        $code = $this->db->fetch('SELECT * FROM df_fault_codes WHERE id = ?', [(int)$id]);
        if (!$code) { $this->redirect('/admin/df-fault-codes'); }
        $this->view('admin/df-fault-codes/form', [
            'pageTitle'   => 'DF Kodu Düzenle',
            'currentPage' => 'admin-df-fault-codes',
            'code'        => $code,
        ], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        $this->db->update('df_fault_codes', [
            'df_code'     => strtoupper(trim($request->post('df_code'))),
            'p_code'      => strtoupper(trim($request->post('p_code'))),
            'description' => trim($request->post('description')),
        ], 'id = ?', [(int)$id]);
        $this->withSuccess('DF kodu güncellendi.', '/admin/df-fault-codes');
    }

    public function delete(Request $request, string $id): void
    {
        $this->db->delete('df_fault_codes', 'id = ?', [(int)$id]);
        $this->withSuccess('DF kodu silindi.', '/admin/df-fault-codes');
    }
}
