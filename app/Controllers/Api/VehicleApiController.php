<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;
use Core\Database;

final class VehicleApiController extends Controller
{
    public function models(Request $request, string $brand_id): void
    {
        $db = Database::getInstance();
        $models = $db->fetchAll('SELECT id, name FROM vehicle_models WHERE brand_id = ? AND is_active = 1 ORDER BY name ASC', [(int)$brand_id]);
        $this->json(['success' => true, 'data' => $models]);
    }

    public function generations(Request $request, string $model_id): void
    {
        $db = Database::getInstance();
        $generations = $db->fetchAll('SELECT id, name FROM generations WHERE model_id = ? AND is_active = 1 ORDER BY name ASC', [(int)$model_id]);
        $this->json(['success' => true, 'data' => $generations]);
    }

    public function engines(Request $request, string $generation_id): void
    {
        $db = Database::getInstance();
        $engines = $db->fetchAll('SELECT id, name, displacement, fuel_type, horsepower FROM engines WHERE generation_id = ? AND is_active = 1 ORDER BY name ASC', [(int)$generation_id]);
        $this->json(['success' => true, 'data' => $engines]);
    }

    public function ecus(Request $request): void
    {
        $db = Database::getInstance();
        $engineId = $request->get('engine_id');

        if ($engineId) {
            $ecus = $db->fetchAll(
                'SELECT e.id, e.name, e.brand
                 FROM ecus e
                 INNER JOIN engine_ecus ee ON e.id = ee.ecu_id
                 WHERE ee.engine_id = ? AND e.is_active = 1
                 ORDER BY e.name ASC',
                [(int)$engineId]
            );
        } else {
            $ecus = $db->fetchAll('SELECT id, name, brand FROM ecus WHERE is_active = 1 ORDER BY name ASC');
        }

        $this->json(['success' => true, 'data' => $ecus]);
    }

    public function readingMethods(Request $request): void
    {
        $db = Database::getInstance();
        $methods = $db->fetchAll('SELECT id, name FROM reading_methods WHERE is_active = 1 ORDER BY name ASC');
        $this->json(['success' => true, 'data' => $methods]);
    }
}
