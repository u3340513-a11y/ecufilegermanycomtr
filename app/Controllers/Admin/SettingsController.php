<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Request;
use Core\Database;

final class SettingsController extends Controller
{
    public function index(Request $request): void
    {
        $db = Database::getInstance();
        $settings = $db->fetchAll('SELECT * FROM settings ORDER BY group_name ASC, key_name ASC');
        $grouped = [];
        foreach ($settings as $s) { $grouped[$s['group_name']][] = $s; }
        $this->view('admin/settings/index', ['pageTitle' => 'Sistem Ayarları', 'currentPage' => 'admin-settings', 'grouped' => $grouped], 'admin');
    }

    public function update(Request $request): void
    {
        $db = Database::getInstance();
        $settings = $request->post('settings');
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                $db->query('UPDATE settings SET value = ? WHERE key_name = ?', [$value, $key]);
            }
        }
        $this->withSuccess('Ayarlar güncellendi.', '/admin/settings');
    }

    public function uploadLogo(Request $request): void
    {
        if (!$request->hasFile('logo')) {
            $this->withError('Lütfen bir dosya seçin.', '/admin/settings');
        }

        $file    = $request->file('logo');
        $allowed = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp', 'image/gif'];

        if (!in_array($file['type'], $allowed, true)) {
            $this->withError('Geçersiz dosya türü. PNG, JPG, SVG veya WEBP yükleyin.', '/admin/settings');
        }

        $uploadDir = \Core\App::basePath() . '/storage/uploads/logo/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $db        = Database::getInstance();
        $oldLogoRow = $db->fetch("SELECT value FROM settings WHERE key_name = 'site_logo'");
        $oldLogo   = $oldLogoRow['value'] ?? '';

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . strtolower($ext);
        $dest     = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->withError('Dosya yüklenemedi.', '/admin/settings');
        }

        if ($oldLogo && file_exists($uploadDir . $oldLogo)) {
            @unlink($uploadDir . $oldLogo);
        }

        $db->query("UPDATE settings SET value = ? WHERE key_name = 'site_logo'", [$filename]);

        $this->withSuccess('Logo başarıyla güncellendi.', '/admin/settings');
    }

    public function deleteLogo(Request $request): void
    {
        $db       = Database::getInstance();
        $row      = $db->fetch("SELECT value FROM settings WHERE key_name = 'site_logo'");
        $oldLogo  = $row['value'] ?? '';

        if ($oldLogo) {
            $path = \Core\App::basePath() . '/storage/uploads/logo/' . $oldLogo;
            if (file_exists($path)) { @unlink($path); }
            $db->query("UPDATE settings SET value = '' WHERE key_name = 'site_logo'");
        }

        $this->withSuccess('Logo kaldırıldı.', '/admin/settings');
    }
}
