<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class PricingController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();
        $packages = $db->fetchAll('SELECT * FROM service_packages ORDER BY sort_order ASC');
        $stages = $db->fetchAll('SELECT * FROM stages WHERE is_active = 1 ORDER BY sort_order ASC');

        $pricingRows = $db->fetchAll(
            'SELECT ssp.*, sp.name as service_name, sp.slug as service_slug
             FROM stage_service_pricing ssp
             JOIN service_packages sp ON ssp.service_package_id = sp.id
             ORDER BY sp.sort_order ASC'
        );

        $stagePricing = [];
        foreach ($pricingRows as $row) {
            $sid = (int) $row['stage_id'];
            $stagePricing[$sid][] = $row;
        }

        $this->view('admin/pricing/index', [
            'pageTitle'    => 'Fiyat Listesi',
            'currentPage'  => 'admin-pricing',
            'packages'     => $packages,
            'stages'       => $stages,
            'stagePricing' => $stagePricing,
        ], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        Database::getInstance()->update('service_packages', [
            'name'        => $request->post('name'),
            'credit_cost' => (int) $request->post('credit_cost'),
            'description' => $request->post('description'),
            'is_active'   => (int) $request->post('is_active', '1'),
            'sort_order'  => (int) $request->post('sort_order', '0'),
        ], 'id = ?', [(int)$id]);
        $this->withSuccess('Fiyat güncellendi.', '/admin/pricing');
    }

    public function updateStagePricing(Request $request, string $id): void
    {
        $db = Database::getInstance();
        $stageId = (int) $id;

        $stage = $db->fetch('SELECT id FROM stages WHERE id = ?', [$stageId]);
        if (!$stage) {
            $this->withError('Geçersiz stage.', '/admin/pricing');
        }

        $services = $request->post('services');
        if (!is_array($services)) {
            $this->withSuccess('Değişiklik yok.', '/admin/pricing');
        }

        $db->beginTransaction();
        try {
            foreach ($services as $serviceId => $data) {
                $serviceId = (int) $serviceId;
                $creditCost = (int) ($data['credit_cost'] ?? 0);
                $isVisible = isset($data['is_visible']) ? 1 : 0;

                $exists = $db->fetch(
                    'SELECT id FROM stage_service_pricing WHERE stage_id = ? AND service_package_id = ?',
                    [$stageId, $serviceId]
                );

                if ($exists) {
                    $db->update('stage_service_pricing', [
                        'credit_cost' => $creditCost,
                        'is_visible'  => $isVisible,
                    ], 'stage_id = ? AND service_package_id = ?', [$stageId, $serviceId]);
                }
            }
            $db->commit();
            $this->withSuccess('Stage fiyatları güncellendi.', '/admin/pricing');
        } catch (\Throwable $e) {
            $db->rollBack();
            $this->withError('Güncelleme hatası: ' . $e->getMessage(), '/admin/pricing');
        }
    }

    public function addServiceToStage(Request $request, string $id): void
    {
        $db = Database::getInstance();
        $stageId = (int) $id;
        $serviceId = (int) $request->post('service_package_id');
        $creditCost = (int) $request->post('credit_cost', '0');

        $exists = $db->fetch(
            'SELECT id FROM stage_service_pricing WHERE stage_id = ? AND service_package_id = ?',
            [$stageId, $serviceId]
        );

        if ($exists) {
            $this->withError('Bu servis zaten bu stage\'de mevcut.', '/admin/pricing');
        }

        $db->insert('stage_service_pricing', [
            'stage_id'           => $stageId,
            'service_package_id' => $serviceId,
            'credit_cost'        => $creditCost,
            'is_visible'         => 1,
        ]);

        $this->withSuccess('Servis stage\'e eklendi.', '/admin/pricing');
    }

    public function removeServiceFromStage(Request $request, string $id): void
    {
        $db = Database::getInstance();
        $stageId = (int) $id;
        $serviceId = (int) $request->post('service_package_id');

        $db->delete('stage_service_pricing', 'stage_id = ? AND service_package_id = ?', [$stageId, $serviceId]);

        $this->withSuccess('Servis stage\'den kaldırıldı.', '/admin/pricing');
    }
}
