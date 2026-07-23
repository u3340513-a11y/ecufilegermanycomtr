<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use App\Services\CreditService;
use App\Repositories\CreditRepository;
use App\Repositories\UserRepository;
use App\Services\NotificationService;
use App\Services\MailService;

final class CreditController extends Controller
{
    private CreditService $creditService;
    private CreditRepository $creditRepo;

    public function __construct()
    {
        parent::__construct();
        $this->creditService = new CreditService();
        $this->creditRepo = new CreditRepository();
    }

    public function index(Request $request): void
    {
        $page = (int) ($request->get('page') ?: 1);
        $transactions = $this->creditRepo->getAll($page);

        $userRepo = new UserRepository();
        $users = $userRepo->search('');

        $this->view('admin/credits/index', [
            'pageTitle'    => 'Kredi Yönetimi',
            'currentPage'  => 'admin-credits',
            'transactions' => $transactions['data'],
            'total'        => $transactions['total'],
            'page'         => $transactions['page'],
            'totalPages'   => $transactions['totalPages'],
            'users'        => $users,
        ], 'admin');
    }

    public function addCredit(Request $request): void
    {
        $userId = (int) $request->post('user_id');
        $amount = (int) $request->post('amount');
        $description = $request->post('description', 'Admin tarafından eklendi');

        if ($userId <= 0 || $amount <= 0) {
            $this->withError('Geçersiz parametreler.', '/admin/credits');
        }

        $newBalance = $this->creditService->add($userId, $amount, 'admin_add', $description, $this->userId());

        $notifService = new NotificationService();
        $notifService->notifyCreditAdded($userId, $amount);

        $userRepo = new UserRepository();
        $user = $userRepo->findById($userId);
        if ($user) {
            $mailService = new MailService();
            $mailService->sendCreditNotification($user['email'], $user['name'], $amount, 'admin_add');
        }

        $this->withSuccess("{$amount} kredi eklendi. Yeni bakiye: {$newBalance}", '/admin/credits');
    }

    public function refund(Request $request, string $id): void
    {
        $tx = \Core\Database::getInstance()->fetch('SELECT * FROM credit_transactions WHERE id = ?', [(int)$id]);
        if (!$tx || $tx['type'] !== 'usage') {
            $this->withError('Geçersiz işlem.', '/admin/credits');
        }

        $amount = abs((int) $tx['amount']);
        $this->creditService->refund((int) $tx['user_id'], $amount, (int) $tx['request_id'], $this->userId());

        $this->withSuccess("{$amount} kredi iade edildi.", '/admin/credits');
    }
}
