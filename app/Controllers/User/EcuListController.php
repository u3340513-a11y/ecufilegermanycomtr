<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Database;

final class EcuListController extends Controller
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
        $brand  = trim($request->get('brand') ?? '');
        $page   = max(1, (int) ($request->get('page') ?: 1));
        $limit  = 30;
        $offset = ($page - 1) * $limit;

        $where  = 'is_active = 1';
        $params = [];

        if ($search !== '') {
            $where  .= ' AND name LIKE ?';
            $params[] = "%{$search}%";
        }

        if ($brand !== '') {
            $where  .= ' AND brand = ?';
            $params[] = $brand;
        }

        $total = $this->db->count('ecus', $where, $params);

        $ecus = $this->db->fetchAll(
            "SELECT id, name, brand FROM ecus
             WHERE {$where} ORDER BY brand ASC, name ASC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $brands = $this->db->fetchAll(
            "SELECT DISTINCT brand FROM ecus WHERE is_active = 1 AND brand IS NOT NULL ORDER BY brand ASC"
        );

        $this->view('user/ecu-list/index', [
            'pageTitle'  => 'ECU Listesi',
            'currentPage'=> 'ecu-list',
            'ecus'       => $ecus,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => (int) ceil($total / $limit),
            'search'     => $search,
            'brand'      => $brand,
            'brands'     => $brands,
        ]);
    }
}
