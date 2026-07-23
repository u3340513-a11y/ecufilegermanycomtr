<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Database;

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
        $limit  = 20;
        $offset = ($page - 1) * $limit;

        $where  = "is_published = 1";
        $params = [];

        if ($search !== '') {
            $where   .= " AND (code LIKE ? OR title LIKE ? OR description LIKE ?)";
            $like     = "%{$search}%";
            $params   = [$like, $like, $like];
        }

        $total = $this->db->count('fault_codes', $where, $params);

        $codes = $this->db->fetchAll(
            "SELECT id, code, title, description, solution, slug
             FROM fault_codes
             WHERE {$where}
             ORDER BY code ASC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $totalPages = (int) ceil($total / $limit);

        $dfSearch = trim($request->get('dq') ?? '');
        $dfWhere  = '1=1';
        $dfParams = [];
        if ($dfSearch !== '') {
            $dfWhere  .= ' AND (df_code LIKE ? OR p_code LIKE ? OR description LIKE ?)';
            $dfLike    = "%{$dfSearch}%";
            $dfParams  = [$dfLike, $dfLike, $dfLike];
        }
        $dfCodes = $this->db->fetchAll(
            "SELECT * FROM df_fault_codes WHERE {$dfWhere} ORDER BY df_code ASC",
            $dfParams
        );

        $this->view('user/fault-codes/index', [
            'pageTitle'  => 'Arıza Kodları',
            'currentPage'=> 'fault-codes',
            'codes'      => $codes,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
            'search'     => $search,
            'dfCodes'    => $dfCodes,
            'dfSearch'   => $dfSearch,
        ]);
    }
}
