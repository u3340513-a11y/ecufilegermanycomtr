<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use App\Services\CreditService;

final class CreditController extends Controller
{
    private CreditService $creditService;

    public function __construct()
    {
        parent::__construct();
        $this->creditService = new CreditService();
    }

    public function index(Request $request): void
    {
        $page = (int) ($request->get('page') ?: 1);
        $transactions = $this->creditService->getTransactions($this->userId(), $page);
        $balance = $this->creditService->getBalance($this->userId());

        $db = \Core\Database::getInstance();
        $pendingPayments = $db->fetchAll(
            "SELECT * FROM payment_links WHERE user_id = ? AND status = 'pending' ORDER BY created_at DESC",
            [$this->userId()]
        );

        $this->view('user/credits/index', [
            'pageTitle'       => 'Kredilerim',
            'currentPage'     => 'credits',
            'transactions'    => $transactions['data'],
            'total'           => $transactions['total'],
            'page'            => $transactions['page'],
            'totalPages'      => $transactions['totalPages'],
            'balance'         => $balance,
            'pendingPayments' => $pendingPayments,
        ]);
    }
}
