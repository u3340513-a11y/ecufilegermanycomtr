<?php

declare(strict_types=1);

namespace App\Helpers;

use Core\Config;

final class FileUploader
{
    private string $uploadPath;
    private array $allowedExtensions;
    private int $maxSize;

    public function __construct(?string $subDir = null)
    {
        $basePath = Config::get('storage.uploads_path', BASE_PATH . '/storage/uploads');
        $this->uploadPath = $subDir ? $basePath . '/' . $subDir : $basePath;
        $this->allowedExtensions = Config::get('app.upload.allowed_types', []);
        $this->maxSize = Config::get('app.upload.max_size', 10485760);

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function upload(array $file, ?string $customName = null): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Dosya yükleme hatası: ' . $this->getErrorMessage($file['error']));
        }

        if ($file['size'] > $this->maxSize) {
            throw new \RuntimeException('Dosya boyutu çok büyük.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!empty($this->allowedExtensions) && !in_array($ext, $this->allowedExtensions, true)) {
            throw new \RuntimeException('Desteklenmeyen dosya türü: ' . $ext);
        }

        $filename = $customName ?: $this->generateFilename($ext);
        $destination = $this->uploadPath . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Dosya taşınamadı.');
        }

        return [
            'filename'      => $filename,
            'original_name' => $file['name'],
            'path'          => $destination,
            'size'          => $file['size'],
            'mime_type'     => $file['type'],
            'extension'     => $ext,
        ];
    }

    public function uploadMultiple(array $files): array
    {
        $results = [];

        if (isset($files['name']) && is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $file = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];

                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $results[] = $this->upload($file);
            }
        }

        return $results;
    }

    public function delete(string $filepath): bool
    {
        if (file_exists($filepath) && is_file($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    public function setAllowedExtensions(array $extensions): self
    {
        $this->allowedExtensions = $extensions;
        return $this;
    }

    public function setMaxSize(int $bytes): self
    {
        $this->maxSize = $bytes;
        return $this;
    }

    private function generateFilename(string $ext): string
    {
        return date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    }

    private function getErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE   => 'Dosya sunucu limitini aşıyor.',
            UPLOAD_ERR_FORM_SIZE => 'Dosya form limitini aşıyor.',
            UPLOAD_ERR_PARTIAL   => 'Dosya kısmen yüklendi.',
            UPLOAD_ERR_NO_FILE   => 'Dosya seçilmedi.',
            UPLOAD_ERR_NO_TMP_DIR => 'Geçici dizin bulunamadı.',
            UPLOAD_ERR_CANT_WRITE => 'Dosya diske yazılamadı.',
            default              => 'Bilinmeyen yükleme hatası.',
        };
    }
}
