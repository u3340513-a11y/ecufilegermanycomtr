<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class BoschEcuController extends Controller
{
    private Database $db;
    public function __construct() { parent::__construct(); $this->db = Database::getInstance(); }

    public function index(Request $request): void
    {
        $ecus = $this->db->fetchAll('SELECT * FROM bosch_ecus ORDER BY ecu_number ASC');
        $this->view('admin/bosch-ecu/index', ['pageTitle' => 'Bosch ECU', 'currentPage' => 'admin-bosch-ecu', 'ecus' => $ecus], 'admin');
    }

    public function create(Request $request): void
    { $this->view('admin/bosch-ecu/form', ['pageTitle' => 'Yeni Bosch ECU', 'currentPage' => 'admin-bosch-ecu', 'ecu' => null], 'admin'); }

    public function store(Request $request): void
    {
        $this->db->insert('bosch_ecus', ['ecu_number' => $request->post('ecu_number'), 'ecu_type' => $request->post('ecu_type')]);
        $this->withSuccess('Bosch ECU eklendi.', '/admin/bosch-ecu');
    }

    public function edit(Request $request, string $id): void
    {
        $ecu = $this->db->fetch('SELECT * FROM bosch_ecus WHERE id = ?', [(int)$id]);
        if (!$ecu) { $this->redirect('/admin/bosch-ecu'); }
        $this->view('admin/bosch-ecu/form', ['pageTitle' => 'Bosch ECU Düzenle', 'currentPage' => 'admin-bosch-ecu', 'ecu' => $ecu], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        $this->db->update('bosch_ecus', ['ecu_number' => $request->post('ecu_number'), 'ecu_type' => $request->post('ecu_type')], 'id = ?', [(int)$id]);
        $this->withSuccess('Bosch ECU güncellendi.', '/admin/bosch-ecu');
    }

    public function delete(Request $request, string $id): void
    {
        $this->db->delete('bosch_ecus', 'id = ?', [(int)$id]);
        $this->withSuccess('Bosch ECU silindi.', '/admin/bosch-ecu');
    }
}
