<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Request;

class LandingContentController extends Controller
{
    private \PDO $db;

    private array $sectionLabels = [
        'hero'         => 'Hero Section',
        'notice'       => 'Service Notice',
        'how_it_works' => 'How It Works',
        'showcase'     => 'Car Showcase (Images)',
        'about'        => 'About Us',
        'branches'     => 'Our Branches',
        'cta'          => 'Call To Action',
        'footer'       => 'Footer',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index(): void
    {
        $rows = $this->db
            ->query("SELECT * FROM landing_content ORDER BY section, sort_order")
            ->fetchAll(\PDO::FETCH_ASSOC);

        $sections = [];
        foreach ($rows as $row) {
            $sections[$row['section']][] = $row;
        }

        $this->view('admin/landing/index', [
            'pageTitle'     => 'Landing Page Editor',
            'currentPage'   => 'admin-landing',
            'sections'      => $sections,
            'sectionLabels' => $this->sectionLabels,
        ], 'admin');
    }

    public function save(): void
    {
        $fields = $_POST['fields'] ?? [];
        if (empty($fields) || !is_array($fields)) {
            $_SESSION['flash_error'] = 'No data received.';
            header('Location: /admin/landing');
            exit;
        }

        $stmt = $this->db->prepare(
            "UPDATE landing_content SET value = ? WHERE section = ? AND key_name = ?"
        );

        foreach ($fields as $sectionKey => $keys) {
            foreach ($keys as $keyName => $value) {
                $stmt->execute([trim((string)$value), $sectionKey, $keyName]);
            }
        }

        $_SESSION['flash_success'] = 'Landing page content updated successfully.';
        header('Location: /admin/landing');
        exit;
    }

    public function uploadImage(): void
    {
        header('Content-Type: application/json');

        $section = $_POST['section'] ?? '';
        $keyName = $_POST['key_name'] ?? '';

        if (empty($section) || empty($keyName)) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
            exit;
        }

        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Upload error.']);
            exit;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime    = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($mime, $allowed, true)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, WebP, GIF allowed.']);
            exit;
        }

        $ext    = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname  = 'landing_' . $section . '_' . $keyName . '_' . time() . '.' . strtolower($ext);
        $dir    = BASE_PATH . '/storage/uploads/landing/';

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $dir . $fname)) {
            echo json_encode(['success' => false, 'message' => 'Failed to save file.']);
            exit;
        }

        $url = '/storage/uploads/landing/' . $fname;

        $stmt = $this->db->prepare(
            "UPDATE landing_content SET value = ? WHERE section = ? AND key_name = ?"
        );
        $stmt->execute([$url, $section, $keyName]);

        echo json_encode(['success' => true, 'url' => $url]);
        exit;
    }
}
