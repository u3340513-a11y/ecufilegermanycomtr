<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use App\Repositories\UserRepository;
use App\Services\CreditService;
use App\Services\MailService;
use App\Services\NotificationService;

final class UserController extends Controller
{
    private UserRepository $userRepo;
    private MailService    $mailService;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo    = new UserRepository();
        $this->mailService = new MailService();
    }

    public function index(Request $request): void
    {
        $page   = (int) ($request->get('page') ?: 1);
        $result = $this->userRepo->getAll($page);

        $this->view('admin/users/index', [
            'pageTitle'    => 'Kullanıcılar',
            'currentPage'  => 'admin-users',
            'users'        => $result['data'],
            'total'        => $result['total'],
            'page'         => $result['current_page'],
            'totalPages'   => $result['total_pages'],
            'pendingCount' => $this->userRepo->countPendingVerification(),
        ], 'admin');
    }

    /**
     * Lists users who have registered but not yet verified their e-mail.
     * Allows admin to approve them manually when verification mails land in spam.
     */
    public function pendingVerification(Request $request): void
    {
        $page   = (int) ($request->get('page') ?: 1);
        $result = $this->userRepo->getPendingVerification($page);

        $this->view('admin/users/pending-verification', [
            'pageTitle'   => 'E-posta Onay Bekleyenler',
            'currentPage' => 'admin-users',
            'users'       => $result['data'],
            'total'       => $result['total'],
            'page'        => $result['current_page'],
            'totalPages'  => $result['total_pages'],
        ], 'admin');
    }

    /**
     * Manually approves a user's e-mail verification.
     * Sends a notification e-mail to the user upon success.
     * If the mail fails, the approval is still committed.
     */
    public function approveVerification(Request $request, string $id): void
    {
        $userId = (int) $id;
        $user   = $this->userRepo->findById($userId);

        if (!$user) {
            $this->withError('Kullanıcı bulunamadı.', '/admin/users/pending-verification');
        }

        if ((bool) $user['email_verified']) {
            $this->withError('Bu kullanıcının e-postası zaten onaylı.', '/admin/users/pending-verification');
        }

        $this->userRepo->verifyEmailById($userId);

        // Best-effort: failure does not block the approval
        $this->mailService->sendAccountApprovedEmail($user['email'], $user['name']);

        $this->withSuccess(
            \Core\View::escape($user['name']) . ' kullanıcısının e-postası onaylandı.',
            '/admin/users/pending-verification'
        );
    }

    public function show(Request $request, string $id): void
    {
        $user = $this->userRepo->findById((int) $id);
        if (!$user) { $this->redirect('/admin/users'); }

        $db = \Core\Database::getInstance();
        $requests     = $db->fetchAll('SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 20', [(int)$id]);
        $transactions = $db->fetchAll(
            'SELECT ct.id, ct.type, ct.amount, ct.balance_after, ct.description, ct.created_at,
                    u.name AS admin_name
             FROM credit_transactions ct
             LEFT JOIN users u ON u.id = ct.admin_id
             WHERE ct.user_id = ?
             ORDER BY ct.created_at DESC
             LIMIT 50',
            [(int)$id]
        );

        $this->view('admin/users/show', [
            'pageTitle'    => $user['name'],
            'currentPage'  => 'admin-users',
            'user'         => $user,
            'requests'     => $requests,
            'transactions' => $transactions,
        ], 'admin');
    }

    public function edit(Request $request, string $id): void
    {
        $user = $this->userRepo->findById((int) $id);
        if (!$user) { $this->redirect('/admin/users'); }

        $this->view('admin/users/edit', [
            'pageTitle'   => 'Kullanıcı Düzenle',
            'currentPage' => 'admin-users',
            'user'        => $user,
        ], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        $this->userRepo->update((int) $id, [
            'name'    => $request->post('name'),
            'email'   => $request->post('email'),
            'phone'   => $request->post('phone'),
            'company' => $request->post('company'),
            'role'    => $request->post('role'),
        ]);

        $this->withSuccess('Kullanıcı güncellendi.', '/admin/users/' . $id);
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $user = $this->userRepo->findById((int) $id);
        if ($user) {
            $this->userRepo->update((int) $id, ['is_active' => $user['is_active'] ? 0 : 1]);
        }
        $this->withSuccess('Durum güncellendi.', '/admin/users/' . $id);
    }

    public function delete(Request $request, string $id): void
    {
        $userId    = (int) $id;
        $authUser  = $request->get('_auth_user') ?? ($_SESSION['user_id'] ?? null);
        $sessionId = (int) ($_SESSION['user_id'] ?? 0);

        $target = $this->userRepo->findById($userId);
        if (!$target) {
            $this->withError('Kullanıcı bulunamadı.', '/admin/users');
        }
        if ($target['role'] === 'admin') {
            $this->withError('Admin hesapları silinemez.', '/admin/users');
        }
        if ($userId === $sessionId) {
            $this->withError('Kendi hesabınızı silemezsiniz.', '/admin/users');
        }

        $db = \Core\Database::getInstance();

        $db->query('DELETE m FROM messages m INNER JOIN requests r ON m.request_id = r.id WHERE r.user_id = ?', [$userId]);
        $db->query('DELETE f FROM files f INNER JOIN requests r ON f.request_id = r.id WHERE r.user_id = ?', [$userId]);
        $db->query('DELETE FROM request_services WHERE request_id IN (SELECT id FROM requests WHERE user_id = ?)', [$userId]);
        $db->query('DELETE FROM requests WHERE user_id = ?', [$userId]);
        $db->query('DELETE FROM credit_transactions WHERE user_id = ?', [$userId]);
        $db->query('DELETE FROM payment_links WHERE user_id = ?', [$userId]);
        $db->query('DELETE FROM activity_logs WHERE user_id = ?', [$userId]);
        $db->query('DELETE FROM notifications WHERE user_id = ?', [$userId]);
        $db->query('DELETE FROM users WHERE id = ?', [$userId]);

        $this->withSuccess('Kullanıcı başarıyla silindi.', '/admin/users');
    }
}
