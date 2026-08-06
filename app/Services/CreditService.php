<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CreditRepository;
use App\Repositories\UserRepository;

final class CreditService
{
    private CreditRepository $creditRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->creditRepo = new CreditRepository();
        $this->userRepo = new UserRepository();
    }

    public function deduct(int $userId, int $amount, ?int $requestId = null, string $description = 'Kredi kullanımı'): bool
    {
        $user = $this->userRepo->findById($userId);
        if (!$user || (int) $user['credit_balance'] < $amount) {
            return false;
        }

        $newBalance = $this->userRepo->updateCreditBalance($userId, -$amount);
        $this->creditRepo->addTransaction($userId, 'usage', -$amount, $newBalance, $description, $requestId);

        return true;
    }

    /**
     * Admin tarafından kullanıcıdan kredi düşer.
     * Bakiye kontrolü controller katmanında yapılmış olmalıdır.
     * İşlemi admin_deduct tipiyle kaydeder ve admin_id'yi loglar.
     *
     * @param int    $userId      Hedef kullanıcı ID'si
     * @param int    $amount      Düşülecek pozitif kredi miktarı
     * @param string $description İşlem açıklaması
     * @param int    $adminId     İşlemi yapan admin ID'si
     * @return int Yeni bakiye
     */
    public function deductByAdmin(int $userId, int $amount, string $description, int $adminId): int
    {
        $newBalance = $this->userRepo->updateCreditBalance($userId, -$amount);
        $this->creditRepo->addTransaction($userId, 'admin_deduct', -$amount, $newBalance, $description, null, $adminId);

        return $newBalance;
    }

    public function add(int $userId, int $amount, string $type = 'admin_add', string $description = 'Kredi eklendi', ?int $adminId = null): int
    {
        $newBalance = $this->userRepo->updateCreditBalance($userId, $amount);
        $this->creditRepo->addTransaction($userId, $type, $amount, $newBalance, $description, null, $adminId);

        return $newBalance;
    }

    public function refund(int $userId, int $amount, ?int $requestId = null, ?int $adminId = null): int
    {
        $newBalance = $this->userRepo->updateCreditBalance($userId, $amount);
        $this->creditRepo->addTransaction($userId, 'refund', $amount, $newBalance, 'Kredi iadesi', $requestId, $adminId);

        return $newBalance;
    }

    public function hasEnoughCredits(int $userId, int $amount): bool
    {
        $user = $this->userRepo->findById($userId);
        return $user && $user['credit_balance'] >= $amount;
    }

    public function getBalance(int $userId): int
    {
        $user = $this->userRepo->findById($userId);
        return $user ? (int) $user['credit_balance'] : 0;
    }

    public function getTransactions(int $userId, int $page = 1): array
    {
        return $this->creditRepo->getByUser($userId, $page);
    }

    /**
     * Gives credits to a user as debt.
     * Increases credit_balance (so user can use them) and increases debt_balance (for tracking).
     * Every Saturday the admin is warned about users with outstanding debt.
     *
     * @param int    $userId      Target user ID
     * @param int    $amount      Credits to give as debt (positive integer)
     * @param string $description Transaction description
     * @param int    $adminId     Admin user ID performing the action
     * @return array{credit_balance: int, debt_balance: int} New balances
     */
    public function addDebt(int $userId, int $amount, string $description, int $adminId): array
    {
        $creditBalance = $this->userRepo->updateCreditBalance($userId, $amount);
        $this->creditRepo->addTransaction($userId, 'debt', $amount, $creditBalance, $description, null, $adminId);

        $debtBalance = $this->userRepo->updateDebtBalance($userId, $amount);

        return [
            'credit_balance' => $creditBalance,
            'debt_balance'   => $debtBalance,
        ];
    }
}
