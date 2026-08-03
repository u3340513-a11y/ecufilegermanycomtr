<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Core\Database;

final class UserRepository implements UserRepositoryInterface
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->fetch('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function findByResetToken(string $token): ?array
    {
        return $this->db->fetch(
            'SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()',
            [$token]
        );
    }

    public function findByEmailToken(string $token): ?array
    {
        return $this->db->fetch('SELECT * FROM users WHERE email_token = ?', [$token]);
    }

    public function create(array $data): int
    {
        return $this->db->insert('users', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }

    public function updatePassword(int $id, string $password): int
    {
        return $this->db->update('users', [
            'password'    => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'reset_token' => null,
            'reset_expires' => null,
        ], 'id = ?', [$id]);
    }

    public function updateCreditBalance(int $id, int $amount): int
    {
        $this->db->query(
            'UPDATE users SET credit_balance = credit_balance + ? WHERE id = ?',
            [$amount, $id]
        );

        $user = $this->findById($id);
        return $user ? (int) $user['credit_balance'] : 0;
    }

    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $total = $this->count();
        $totalPages = (int) ceil($total / $perPage);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $perPage;

        $data = $this->db->fetchAll(
            'SELECT id, name, email, phone, company, credit_balance, role, is_active, email_verified, created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );

        return [
            'data' => $data,
            'total' => $total,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage,
        ];
    }

    public function search(string $query): array
    {
        $like = "%{$query}%";
        return $this->db->fetchAll(
            'SELECT id, name, email, phone, company, credit_balance, role, is_active FROM users WHERE name LIKE ? OR email LIKE ? OR company LIKE ? ORDER BY name ASC',
            [$like, $like, $like]
        );
    }

    public function count(): int
    {
        return $this->db->count('users');
    }

    public function countByRole(string $role): int
    {
        return $this->db->count('users', 'role = ?', [$role]);
    }

    /**
     * Returns paginated list of users who have not yet verified their e-mail address.
     * Used by the admin panel to allow manual approval when confirmation mails land in spam.
     */
    public function getPendingVerification(int $page = 1, int $perPage = 20): array
    {
        $total      = $this->countPendingVerification();
        $totalPages = (int) ceil($total / $perPage);
        $page       = max(1, min($page, $totalPages ?: 1));
        $offset     = ($page - 1) * $perPage;

        $data = $this->db->fetchAll(
            'SELECT id, name, email, phone, company, created_at FROM users WHERE email_verified = 0 AND role = ? ORDER BY created_at DESC LIMIT ? OFFSET ?',
            ['user', $perPage, $offset]
        );

        return [
            'data'         => $data,
            'total'        => $total,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'per_page'     => $perPage,
        ];
    }

    /**
     * Marks a single user's e-mail address as verified (admin manual approval).
     * Clears the email_token so the old verification link is invalidated.
     */
    public function verifyEmailById(int $id): int
    {
        return $this->db->update('users', [
            'email_verified' => 1,
            'email_token'    => null,
        ], 'id = ?', [$id]);
    }

    /**
     * Returns the count of users whose e-mail address is still unverified.
     */
    public function countPendingVerification(): int
    {
        return $this->db->count('users', 'email_verified = 0 AND role = ?', ['user']);
    }
}
