<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;
use Core\Database;

final class StageApiController extends Controller
{
    public function pricing(Request $request): void
    {
        $db = Database::getInstance();

        $stages = $db->fetchAll('SELECT * FROM stages WHERE is_active = 1 ORDER BY sort_order ASC');

        $pricing = $db->fetchAll(
            'SELECT ssp.stage_id, ssp.service_package_id, ssp.credit_cost, ssp.is_visible,
                    sp.name as service_name, sp.slug as service_slug
             FROM stage_service_pricing ssp
             JOIN service_packages sp ON ssp.service_package_id = sp.id
             WHERE sp.is_active = 1
             ORDER BY sp.sort_order ASC'
        );

        $matrix = [];
        foreach ($pricing as $row) {
            $stageId = (int) $row['stage_id'];
            if (!isset($matrix[$stageId])) {
                $matrix[$stageId] = [];
            }
            $matrix[$stageId][] = [
                'id'          => (int) $row['service_package_id'],
                'name'        => $row['service_name'],
                'slug'        => $row['service_slug'],
                'credit_cost' => (int) $row['credit_cost'],
                'is_visible'  => (int) $row['is_visible'],
            ];
        }

        $this->json([
            'success' => true,
            'stages'  => $stages,
            'pricing' => $matrix,
        ]);
    }
}
