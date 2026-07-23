<?php

declare(strict_types=1);

namespace App\Helpers;

final class Sanitizer
{
    public static function clean(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function cleanArray(array $data): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return self::cleanArray($value);
            }
            return is_string($value) ? self::clean($value) : $value;
        }, $data);
    }

    public static function email(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL) ?: '';
    }

    public static function int(mixed $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function float(mixed $value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function alphanumeric(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $value);
    }

    public static function filename(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9._-]/', '', $value);
    }
}
