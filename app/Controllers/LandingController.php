<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use Core\Request;

class LandingController extends Controller
{
    public function index(Request $request): void
    {
        $lp = $this->loadLandingContent();
        $this->view('landing', ['lp' => $lp], 'landing');
    }

    private function loadLandingContent(): array
    {
        try {
            $db   = Database::getInstance()->getConnection();
            $rows = $db->query("SELECT section, key_name, value FROM landing_content ORDER BY section, sort_order")
                       ->fetchAll(\PDO::FETCH_ASSOC);

            $data = [];
            foreach ($rows as $row) {
                $data[$row['section']][$row['key_name']] = $row['value'];
            }
            return $data;
        } catch (\Throwable) {
            return [];
        }
    }
}
