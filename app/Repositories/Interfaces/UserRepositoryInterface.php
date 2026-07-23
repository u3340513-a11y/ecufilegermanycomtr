<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findById(int $id): ?array;
    public function findByEmail(string $email): ?array;
    public function findByResetToken(string $token): ?array;
    public function findByEmailToken(string $token): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): int;
    public function updatePassword(int $id, string $password): int;
    public function updateCreditBalance(int $id, int $amount): int;
    public function getAll(int $page, int $perPage): array;
    public function search(string $query): array;
    public function count(): int;
}
