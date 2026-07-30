<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RequestRepository;
use App\Repositories\FileRepository;
use App\Helpers\FileUploader;
use Core\Database;

final class RequestService
{
    private RequestRepository $requestRepo;
    private FileRepository $fileRepo;
    private CreditService $creditService;
    private NotificationService $notifService;

    public function __construct()
    {
        $this->requestRepo = new RequestRepository();
        $this->fileRepo = new FileRepository();
        $this->creditService = new CreditService();
        $this->notifService = new NotificationService();
    }

    public function create(int $userId, array $data, array $serviceIds, int $stageId): int
    {
        $db = Database::getInstance();

        $stage = $db->fetch('SELECT * FROM stages WHERE id = ? AND is_active = 1', [$stageId]);
        if (!$stage) {
            throw new \RuntimeException('Geçersiz stage seçimi.');
        }

        $baseCredit = (int) $stage['base_credit'];

        $pricingRows = $db->fetchAll(
            'SELECT ssp.service_package_id, ssp.credit_cost, sp.name
             FROM stage_service_pricing ssp
             JOIN service_packages sp ON ssp.service_package_id = sp.id
             WHERE ssp.stage_id = ? AND ssp.is_visible = 1 AND sp.is_active = 1',
            [$stageId]
        );

        $pricingMap = [];
        foreach ($pricingRows as $row) {
            $pricingMap[(int) $row['service_package_id']] = $row;
        }

        $selectedServices = [];
        $serviceTotalCredits = 0;

        $showServices = (int) $stage['show_services'];

        if ($showServices > 0 && !empty($serviceIds)) {
            foreach ($serviceIds as $sid) {
                $sid = (int) $sid;
                if (isset($pricingMap[$sid])) {
                    $cost = (int) $pricingMap[$sid]['credit_cost'];
                    $selectedServices[] = [
                        'id'          => $sid,
                        'credit_cost' => $cost,
                        'name'        => $pricingMap[$sid]['name'],
                    ];
                    $serviceTotalCredits += $cost;
                }
            }
        }

        $totalCredits = $baseCredit + $serviceTotalCredits;

        if ($totalCredits > 0 && !$this->creditService->hasEnoughCredits($userId, $totalCredits)) {
            throw new \RuntimeException('Yetersiz kredi bakiyesi. Gerekli: ' . $totalCredits);
        }

        $db->beginTransaction();

        try {
            $ticketNo = $this->requestRepo->generateTicketNo();

            $requestId = $this->requestRepo->create([
                'user_id'              => $userId,
                'ticket_no'            => $ticketNo,
                'brand_id'             => $data['brand_id'] ?: null,
                'model_id'             => $data['model_id'] ?: null,
                'generation_id'        => $data['generation_id'] ?: null,
                'engine_id'            => $data['engine_id'] ?: null,
                'ecu_id'               => $data['ecu_id'] ?: null,
                'stage_id'             => $stageId,
                'transmission_type_id' => $data['transmission_type_id'] ?: null,
                'year'                 => $data['year'] ?: null,
                'ecu_sw'               => $data['ecu_sw'] ?? null,
                'ecu_hw'               => $data['ecu_hw'] ?? null,
                'reading_method_id'    => $data['reading_method_id'] ?: null,
                'plate_number'         => $data['plate_number'] ?? null,
                'customer_note'        => $data['customer_note'] ?? null,
                'status'               => 'pending',
                'total_credits'        => $totalCredits,
            ]);

            if (!empty($selectedServices)) {
                $this->requestRepo->addServices($requestId, $selectedServices);
            }

            if ($totalCredits > 0) {
                $this->creditService->deduct($userId, $totalCredits, $requestId, "Talep #{$ticketNo}");
            }

            $db->commit();
            return $requestId;

        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getActivePackages(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll('SELECT * FROM service_packages WHERE is_active = 1 ORDER BY sort_order ASC');
    }

    public function getStagesWithPricing(): array
    {
        $db = Database::getInstance();
        $stages = $db->fetchAll('SELECT * FROM stages WHERE is_active = 1 ORDER BY sort_order ASC');
        return $stages;
    }

    public function getUserRequests(int $userId, int $page = 1): array
    {
        return $this->requestRepo->getByUser($userId, $page);
    }

    public function getRequestDetail(int $requestId): ?array
    {
        $request = $this->requestRepo->findById($requestId);
        if (!$request) return null;

        $request['services'] = $this->requestRepo->getServices($requestId);
        $request['files'] = $this->fileRepo->getByRequest($requestId);

        return $request;
    }

    /**
     * Updates the status of a request and handles side-effects:
     * - When transitioning to 'cancelled', automatically refunds the total_credits
     *   back to the user if they haven't been refunded already (idempotent guard).
     * - Wraps status update + refund in a transaction to ensure atomicity.
     *
     * @param int    $requestId Target request ID.
     * @param string $status    New status value (must be a valid status string).
     * @param int    $adminId   ID of the admin performing the action.
     */
    public function updateStatus(int $requestId, string $status, int $adminId): void
    {
        $request = $this->requestRepo->findById($requestId);
        if (!$request) {
            return;
        }

        $previousStatus  = (string) $request['status'];
        $totalCredits    = (int)   $request['total_credits'];
        $userId          = (int)   $request['user_id'];
        $isCancellation  = $status === 'cancelled';
        $wasAlreadyCancelled = $previousStatus === 'cancelled';

        // Determine whether a credit refund should be issued:
        // Only refund when transitioning INTO cancelled for the first time and
        // there are credits to return.
        $shouldRefund = $isCancellation && !$wasAlreadyCancelled && $totalCredits > 0;

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            $this->requestRepo->updateStatus($requestId, $status);

            if ($shouldRefund) {
                $this->creditService->refund(
                    $userId,
                    $totalCredits,
                    $requestId,
                    $adminId
                );
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        // Send notifications and mail outside the transaction
        // (non-critical; failure here should not roll back the status change).
        $this->notifService->notifyRequestUpdate($userId, $request['ticket_no'], $status);

        if ($shouldRefund) {
            $this->notifService->notifyRefund($userId, $totalCredits, $request['ticket_no']);
        }

        $mailService = new MailService();
        $mailService->sendRequestNotification(
            $request['user_email'],
            $request['user_name'],
            $request['ticket_no'],
            $status
        );
    }

    public function addServiceToRequest(int $requestId, int $servicePackageId, int $adminId): array
    {
        $db = Database::getInstance();

        $request = $this->requestRepo->findById($requestId);
        if (!$request) {
            throw new \RuntimeException('Talep bulunamadı.');
        }

        $existing = $db->fetchAll(
            'SELECT id FROM request_services WHERE request_id = ? AND service_package_id = ?',
            [$requestId, $servicePackageId]
        );
        if (!empty($existing)) {
            throw new \RuntimeException('Bu servis zaten talebe eklenmiş.');
        }

        $pricing = $db->fetch(
            'SELECT ssp.credit_cost, sp.name
             FROM stage_service_pricing ssp
             JOIN service_packages sp ON ssp.service_package_id = sp.id
             WHERE ssp.stage_id = ? AND ssp.service_package_id = ? AND ssp.is_visible = 1',
            [$request['stage_id'], $servicePackageId]
        );

        if (!$pricing) {
            $pkg = $db->fetch('SELECT * FROM service_packages WHERE id = ? AND is_active = 1', [$servicePackageId]);
            if (!$pkg) {
                throw new \RuntimeException('Geçersiz servis paketi.');
            }
            $creditCost = 0;
            $serviceName = $pkg['name'];
        } else {
            $creditCost = (int) $pricing['credit_cost'];
            $serviceName = $pricing['name'];
        }

        $userId = (int) $request['user_id'];

        if ($creditCost > 0 && !$this->creditService->hasEnoughCredits($userId, $creditCost)) {
            throw new \RuntimeException("Müşteri bakiyesi yetersiz. Gerekli: {$creditCost} kredi.");
        }

        $db->beginTransaction();
        try {
            $db->insert('request_services', [
                'request_id'         => $requestId,
                'service_package_id' => $servicePackageId,
                'credit_cost'        => $creditCost,
            ]);

            $newTotal = (int) $request['total_credits'] + $creditCost;
            $db->update('requests', ['total_credits' => $newTotal], 'id = ?', [$requestId]);

            if ($creditCost > 0) {
                $this->creditService->deduct(
                    $userId,
                    $creditCost,
                    $requestId,
                    "Talep #{$request['ticket_no']} — {$serviceName} eklendi (admin)"
                );
            }

            $db->commit();

            return [
                'service_name' => $serviceName,
                'credit_cost'  => $creditCost,
                'new_total'    => $newTotal,
            ];
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
