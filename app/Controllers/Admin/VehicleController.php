<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;
use App\Helpers\Slug;

final class VehicleController extends Controller
{
    private Database $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
    }

    public function brands(Request $request): void
    {
        $brands = $this->db->fetchAll('SELECT * FROM brands ORDER BY sort_order ASC, name ASC');
        $this->view('admin/vehicles/brands', ['pageTitle' => 'Markalar', 'currentPage' => 'admin-brands', 'brands' => $brands], 'admin');
    }

    public function storeBrand(Request $request): void
    {
        $this->db->insert('brands', ['name' => $request->post('name'), 'slug' => Slug::unique($request->post('name'), 'brands', 'slug'), 'sort_order' => (int) $request->post('sort_order', '0')]);
        $this->withSuccess('Marka eklendi.', '/admin/vehicles/brands');
    }

    public function updateBrand(Request $request, string $id): void
    {
        $this->db->update('brands', ['name' => $request->post('name'), 'slug' => Slug::unique($request->post('name'), 'brands', 'slug', (int)$id), 'is_active' => (int) $request->post('is_active', '1'), 'sort_order' => (int) $request->post('sort_order', '0')], 'id = ?', [(int)$id]);
        $this->withSuccess('Marka güncellendi.', '/admin/vehicles/brands');
    }

    public function deleteBrand(Request $request, string $id): void
    {
        $this->db->delete('brands', 'id = ?', [(int) $id]);
        $this->withSuccess('Marka silindi.', '/admin/vehicles/brands');
    }

    public function models(Request $request): void
    {
        $models = $this->db->fetchAll('SELECT vm.*, b.name as brand_name FROM vehicle_models vm LEFT JOIN brands b ON vm.brand_id = b.id ORDER BY b.name ASC, vm.name ASC');
        $brands = $this->db->fetchAll('SELECT * FROM brands WHERE is_active = 1 ORDER BY name ASC');
        $this->view('admin/vehicles/models', ['pageTitle' => 'Modeller', 'currentPage' => 'admin-models', 'models' => $models, 'brands' => $brands], 'admin');
    }

    public function storeModel(Request $request): void
    {
        $this->db->insert('vehicle_models', ['brand_id' => (int) $request->post('brand_id'), 'name' => $request->post('name'), 'slug' => Slug::unique($request->post('name'), 'vehicle_models', 'slug')]);
        $this->withSuccess('Model eklendi.', '/admin/vehicles/models');
    }

    public function updateModel(Request $request, string $id): void
    {
        $this->db->update('vehicle_models', ['brand_id' => (int) $request->post('brand_id'), 'name' => $request->post('name'), 'is_active' => (int) $request->post('is_active', '1')], 'id = ?', [(int)$id]);
        $this->withSuccess('Model güncellendi.', '/admin/vehicles/models');
    }

    public function deleteModel(Request $request, string $id): void
    {
        $this->db->delete('vehicle_models', 'id = ?', [(int) $id]);
        $this->withSuccess('Model silindi.', '/admin/vehicles/models');
    }

    public function generations(Request $request): void
    {
        $generations = $this->db->fetchAll('SELECT g.*, vm.name as model_name, b.name as brand_name FROM generations g LEFT JOIN vehicle_models vm ON g.model_id = vm.id LEFT JOIN brands b ON vm.brand_id = b.id ORDER BY b.name ASC, vm.name ASC, g.name ASC');
        $models = $this->db->fetchAll('SELECT vm.id, vm.name, b.name as brand_name FROM vehicle_models vm LEFT JOIN brands b ON vm.brand_id = b.id WHERE vm.is_active = 1 ORDER BY b.name ASC, vm.name ASC');
        $this->view('admin/vehicles/generations', ['pageTitle' => 'Jenerasyonlar', 'currentPage' => 'admin-generations', 'generations' => $generations, 'models' => $models], 'admin');
    }

    public function storeGeneration(Request $request): void
    {
        $this->db->insert('generations', ['model_id' => (int) $request->post('model_id'), 'name' => $request->post('name')]);
        $this->withSuccess('Jenerasyon eklendi.', '/admin/vehicles/generations');
    }

    public function updateGeneration(Request $request, string $id): void
    {
        $this->db->update('generations', ['model_id' => (int) $request->post('model_id'), 'name' => $request->post('name'), 'is_active' => (int) $request->post('is_active', '1')], 'id = ?', [(int)$id]);
        $this->withSuccess('Jenerasyon güncellendi.', '/admin/vehicles/generations');
    }

    public function deleteGeneration(Request $request, string $id): void
    {
        $this->db->delete('generations', 'id = ?', [(int)$id]);
        $this->withSuccess('Jenerasyon silindi.', '/admin/vehicles/generations');
    }

    public function engines(Request $request): void
    {
        $engines = $this->db->fetchAll('SELECT e.*, g.name as generation_name, vm.name as model_name, b.name as brand_name FROM engines e LEFT JOIN generations g ON e.generation_id = g.id LEFT JOIN vehicle_models vm ON g.model_id = vm.id LEFT JOIN brands b ON vm.brand_id = b.id ORDER BY b.name ASC');
        $gens = $this->db->fetchAll('SELECT g.id, g.name, vm.name as model_name FROM generations g LEFT JOIN vehicle_models vm ON g.model_id = vm.id WHERE g.is_active = 1 ORDER BY vm.name ASC, g.name ASC');
        $this->view('admin/vehicles/engines', ['pageTitle' => 'Motorlar', 'currentPage' => 'admin-engines', 'engines' => $engines, 'generations' => $gens], 'admin');
    }

    public function storeEngine(Request $request): void
    {
        $this->db->insert('engines', ['generation_id' => (int) $request->post('generation_id'), 'name' => $request->post('name'), 'displacement' => $request->post('displacement'), 'fuel_type' => $request->post('fuel_type'), 'horsepower' => $request->post('horsepower') ? (int) $request->post('horsepower') : null]);
        $this->withSuccess('Motor eklendi.', '/admin/vehicles/engines');
    }

    public function updateEngine(Request $request, string $id): void
    {
        $this->db->update('engines', ['generation_id' => (int) $request->post('generation_id'), 'name' => $request->post('name'), 'displacement' => $request->post('displacement'), 'fuel_type' => $request->post('fuel_type'), 'horsepower' => $request->post('horsepower') ? (int) $request->post('horsepower') : null, 'is_active' => (int) $request->post('is_active', '1')], 'id = ?', [(int)$id]);
        $this->withSuccess('Motor güncellendi.', '/admin/vehicles/engines');
    }

    public function deleteEngine(Request $request, string $id): void
    {
        $this->db->delete('engines', 'id = ?', [(int)$id]);
        $this->withSuccess('Motor silindi.', '/admin/vehicles/engines');
    }

    public function ecus(Request $request): void
    {
        $ecus = $this->db->fetchAll('SELECT * FROM ecus ORDER BY name ASC');
        $this->view('admin/vehicles/ecus', ['pageTitle' => 'ECU\'lar', 'currentPage' => 'admin-ecus', 'ecus' => $ecus], 'admin');
    }

    public function storeEcu(Request $request): void
    {
        $this->db->insert('ecus', ['name' => $request->post('name'), 'brand' => $request->post('brand')]);
        $this->withSuccess('ECU eklendi.', '/admin/vehicles/ecus');
    }

    public function updateEcu(Request $request, string $id): void
    {
        $this->db->update('ecus', ['name' => $request->post('name'), 'brand' => $request->post('brand'), 'is_active' => (int) $request->post('is_active', '1')], 'id = ?', [(int)$id]);
        $this->withSuccess('ECU güncellendi.', '/admin/vehicles/ecus');
    }

    public function deleteEcu(Request $request, string $id): void
    {
        $this->db->delete('ecus', 'id = ?', [(int)$id]);
        $this->withSuccess('ECU silindi.', '/admin/vehicles/ecus');
    }

    public function readingMethods(Request $request): void
    {
        $methods = $this->db->fetchAll('SELECT * FROM reading_methods ORDER BY name ASC');
        $this->view('admin/vehicles/reading-methods', ['pageTitle' => 'Okuma Yöntemleri', 'currentPage' => 'admin-reading-methods', 'methods' => $methods], 'admin');
    }

    public function storeReadingMethod(Request $request): void
    {
        $this->db->insert('reading_methods', ['name' => $request->post('name')]);
        $this->withSuccess('Okuma yöntemi eklendi.', '/admin/vehicles/reading-methods');
    }

    public function updateReadingMethod(Request $request, string $id): void
    {
        $this->db->update('reading_methods', ['name' => $request->post('name'), 'is_active' => (int) $request->post('is_active', '1')], 'id = ?', [(int)$id]);
        $this->withSuccess('Okuma yöntemi güncellendi.', '/admin/vehicles/reading-methods');
    }

    public function deleteReadingMethod(Request $request, string $id): void
    {
        $this->db->delete('reading_methods', 'id = ?', [(int)$id]);
        $this->withSuccess('Okuma yöntemi silindi.', '/admin/vehicles/reading-methods');
    }
}
