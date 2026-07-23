<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use Core\Session;

final class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function register(array $data): int
    {
        $emailToken = bin2hex(random_bytes(32));

        $userId = $this->userRepo->create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'phone'         => $data['phone'] ?? null,
            'company'       => $data['company'] ?? null,
            'email_token'   => $emailToken,
            'email_verified' => 0,
            'role'          => 'user',
            'credit_balance' => 0,
        ]);

        $mailService = new MailService();
        $mailService->sendVerificationEmail($data['email'], $data['name'], $emailToken);

        return $userId;
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        if (!$user['is_active']) {
            Session::flash('error', 'Hesabınız devre dışı bırakılmış.');
            return false;
        }

        if (!$user['email_verified']) {
            Session::flash('error', 'Lütfen e-posta adresinizi doğrulayın.');
            Session::flash('show_resend', true);
            return false;
        }

        $userId = (int) $user['id'];

        Session::regenerate();
        Session::set('user_id', $userId);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);

        $this->logActivity($userId, 'login', 'user', $userId);

        return true;
    }

    public function logout(): void
    {
        $userId = Session::userId();
        if ($userId) {
            $this->logActivity($userId, 'logout', 'user', $userId);
        }
        Session::destroy();
    }

    public function sendResetLink(string $email): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            return true;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->userRepo->update((int) $user['id'], [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        $mailService = new MailService();
        $mailService->sendPasswordResetEmail($user['email'], $user['name'], $token);

        return true;
    }

    public function resetPassword(string $token, string $password): bool
    {
        $user = $this->userRepo->findByResetToken($token);
        if (!$user) {
            return false;
        }

        $userId = (int) $user['id'];
        $this->userRepo->updatePassword($userId, $password);
        $this->logActivity($userId, 'password_reset', 'user', $userId);

        return true;
    }

    public function verifyEmail(string $token): bool
    {
        $user = $this->userRepo->findByEmailToken($token);
        if (!$user) {
            return false;
        }

        $userId = (int) $user['id'];

        $this->userRepo->update($userId, [
            'email_verified' => 1,
            'email_token'    => null,
        ]);

        $this->logActivity($userId, 'email_verified', 'user', $userId);

        return true;
    }

    public function resendVerification(int $userId): bool
    {
        $user = $this->userRepo->findById($userId);
        if (!$user || $user['email_verified']) {
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $this->userRepo->update($userId, ['email_token' => $token]);

        $mailService = new MailService();
        $mailService->sendVerificationEmail($user['email'], $user['name'], $token);

        return true;
    }

    private function logActivity(int $userId, string $action, string $entityType, int $entityId): void
    {
        try {
            $db = \Core\Database::getInstance();
            $db->insert('activity_logs', [
                'user_id'     => $userId,
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ]);
        } catch (\Throwable) {
        }
    }
}
