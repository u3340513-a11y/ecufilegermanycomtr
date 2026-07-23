<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Database;

final class BoschEcuLookupController extends Controller
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
        $limit  = 30;
        $offset = ($page - 1) * $limit;

        $where  = '1=1';
        $params = [];

        if ($search !== '') {
            $where  .= ' AND (ecu_number LIKE ? OR ecu_type LIKE ?)';
            $like    = "%{$search}%";
            $params  = [$like, $like];
        }

        $total = $this->db->count('bosch_ecus', $where, $params);

        $ecus = $this->db->fetchAll(
            "SELECT id, ecu_number, ecu_type FROM bosch_ecus
             WHERE {$where} ORDER BY ecu_number ASC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $this->view('user/bosch-ecu/index', [
            'pageTitle'  => 'Bosch ECU Sorgula',
            'currentPage'=> 'bosch-ecu',
            'ecus'       => $ecus,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => (int) ceil($total / $limit),
            'search'     => $search,
        ]);
    }
}
