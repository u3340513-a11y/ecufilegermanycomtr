<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;
use App\Services\CreditService;
use App\Services\NotificationService;
use App\Services\MailService;
use App\Repositories\UserRepository;

final class StripeController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();
        $links = $db->fetchAll('SELECT pl.*, u.name as user_name, u.email as user_email, a.name as approver_name FROM payment_links pl LEFT JOIN users u ON pl.user_id = u.id LEFT JOIN users a ON pl.approved_by = a.id ORDER BY pl.created_at DESC');
        $users = $db->fetchAll("SELECT id, name, email FROM users WHERE role = 'user' AND is_active = 1 ORDER BY name ASC");

        $this->view('admin/stripe/index', ['pageTitle' => 'Stripe Ödeme Linkleri', 'currentPage' => 'admin-stripe', 'links' => $links, 'users' => $users], 'admin');
    }

    public function createLink(Request $request): void
    {
        Database::getInstance()->insert('payment_links', [
            'user_id' => (int) $request->post('user_id'),
            'stripe_link' => $request->post('stripe_link'),
            'credit_amount' => (int) $request->post('credit_amount'),
            'price' => (float) $request->post('price'),
            'currency' => $request->post('currency', 'EUR'),
            'status' => 'pending',
        ]);
        $this->withSuccess('Ödeme linki oluşturuldu.', '/admin/stripe');
    }

    public function approve(Request $request, string $id): void
    {
        $db = Database::getInstance();
        $link = $db->fetch('SELECT * FROM payment_links WHERE id = ?', [(int)$id]);
        if (!$link || $link['status'] !== 'pending') { $this->withError('Geçersiz link.', '/admin/stripe'); }

        $db->update('payment_links', ['status' => 'paid', 'approved_by' => $this->userId(), 'approved_at' => date('Y-m-d H:i:s')], 'id = ?', [(int)$id]);

        $creditService = new CreditService();
        $creditService->add((int) $link['user_id'], (int) $link['credit_amount'], 'purchase', 'Stripe ödeme onayı', $this->userId());

        $notifService = new NotificationService();
        $notifService->notifyPaymentApproved((int) $link['user_id'], (int) $link['credit_amount']);

        $userRepo = new UserRepository();
        $user = $userRepo->findById((int) $link['user_id']);
        if ($user) {
            $mailService = new MailService();
            $mailService->sendCreditNotification($user['email'], $user['name'], (int) $link['credit_amount'], 'purchase');
        }

        $this->withSuccess('Ödeme onaylandı ve kredi tanımlandı.', '/admin/stripe');
    }

    public function cancel(Request $request, string $id): void
    {
        Database::getInstance()->update('payment_links', ['status' => 'cancelled'], 'id = ?', [(int)$id]);
        $this->withSuccess('Ödeme linki iptal edildi.', '/admin/stripe');
    }
}
