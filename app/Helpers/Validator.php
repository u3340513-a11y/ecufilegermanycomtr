<?php

declare(strict_types=1);

namespace App\Helpers;

final class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim((string) $this->data[$field]) === '') {
            $this->errors[$field] = "{$label} alanı zorunludur.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Geçerli bir e-posta adresi giriniz.";
        }
        return $this;
    }

    public function min(string $field, int $length, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && mb_strlen((string) $this->data[$field]) < $length) {
            $this->errors[$field] = "{$label} en az {$length} karakter olmalıdır.";
        }
        return $this;
    }

    public function max(string $field, int $length, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && mb_strlen((string) $this->data[$field]) > $length) {
            $this->errors[$field] = "{$label} en fazla {$length} karakter olmalıdır.";
        }
        return $this;
    }

    public function confirmed(string $field, string $confirmField, string $label = ''): self
    {
        $label = $label ?: $field;
        if (($this->data[$field] ?? '') !== ($this->data[$confirmField] ?? '')) {
            $this->errors[$field] = "{$label} alanları eşleşmiyor.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "{$label} sayısal bir değer olmalıdır.";
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed, true)) {
            $this->errors[$field] = "{$label} geçersiz bir değer içeriyor.";
        }
        return $this;
    }

    public function unique(string $field, string $table, string $column, ?int $exceptId = null, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            return $this;
        }

        $db = \Core\Database::getInstance();
        $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = ?";
        $params = [$this->data[$field]];

        if ($exceptId !== null) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $db->fetch($sql, $params);
        if (($result['total'] ?? 0) > 0) {
            $this->errors[$field] = "{$label} zaten kullanılıyor.";
        }
        return $this;
    }

    public function file(string $field, array $allowedTypes = [], int $maxSize = 10485760, string $label = ''): self
    {
        $label = $label ?: $field;

        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return $this;
        }

        $file = $_FILES[$field];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[$field] = "{$label} yüklenirken bir hata oluştu.";
            return $this;
        }

        if ($file['size'] > $maxSize) {
            $sizeMB = round($maxSize / 1048576, 1);
            $this->errors[$field] = "{$label} dosya boyutu en fazla {$sizeMB}MB olabilir.";
            return $this;
        }

        if (!empty($allowedTypes)) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedTypes, true)) {
                $this->errors[$field] = "{$label} dosya türü desteklenmiyor.";
            }
        }

        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors ? reset($this->errors) : null;
    }
}
