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

    /**
     * Admin tarafından kullanıcı bakiyesinden kredi düşer.
     * Bakiye yetersizse hata döner; başarılıysa bildirim ve mail gönderilir.
     */
    public function deductCredit(Request $request): void
    {
        $userId      = (int) $request->post('user_id');
        $amount      = (int) $request->post('amount');
        $description = trim((string) $request->post('description', '')) ?: 'Admin tarafından düşüldü';

        if ($userId <= 0 || $amount <= 0) {
            $this->withError('Geçersiz parametreler.', '/admin/credits');
        }

        $userRepo = new UserRepository();
        $user     = $userRepo->findById($userId);

        if (!$user) {
            $this->withError('Kullanıcı bulunamadı.', '/admin/credits');
        }

        $currentBalance = (int) $user['credit_balance'];
        if ($currentBalance < $amount) {
            $this->withError(
                "Yetersiz bakiye. Kullanıcının mevcut bakiyesi: {$currentBalance} kredi.",
                '/admin/credits'
            );
        }

        $newBalance = $this->creditService->deductByAdmin($userId, $amount, $description, $this->userId());

        $notifService = new NotificationService();
        $notifService->notifyCreditDeducted($userId, $amount);

        $mailService = new MailService();
        $mailService->sendCreditNotification($user['email'], $user['name'], $amount, 'admin_deduct');

        $this->withSuccess("{$amount} kredi düşüldü. Yeni bakiye: {$newBalance}", '/admin/credits');
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

    /**
     * Gives credits to a user as debt (borç).
     * The user can use the credits immediately; debt_balance tracks the owed amount.
     * Admin is warned every Saturday about users with outstanding debt.
     */
    public function addDebtCredit(Request $request): void
    {
        $userId      = (int) $request->post('user_id');
        $amount      = (int) $request->post('amount');
        $description = trim((string) $request->post('description', '')) ?: 'Borç olarak verildi';

        if ($userId <= 0 || $amount <= 0) {
            $this->withError('Geçersiz parametreler.', '/admin/credits');
        }

        $userRepo = new UserRepository();
        $user     = $userRepo->findById($userId);
        if (!$user) {
            $this->withError('Kullanıcı bulunamadı.', '/admin/credits');
        }

        $balances = $this->creditService->addDebt($userId, $amount, $description, $this->userId());

        $notifService = new NotificationService();
        $notifService->notifyCreditAdded($userId, $amount);

        $mailService = new MailService();
        $mailService->sendCreditNotification($user['email'], $user['name'], $amount, 'debt');

        $this->withSuccess(
            "{$amount} kredi borç olarak eklendi. Kredi bakiyesi: {$balances['credit_balance']}, Borç bakiyesi: {$balances['debt_balance']}",
            '/admin/credits'
        );
    }
}
