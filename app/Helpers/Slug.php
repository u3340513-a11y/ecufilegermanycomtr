<?php

declare(strict_types=1);

namespace App\Helpers;

final class Slug
{
    public static function generate(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');

        $replacements = [
            'ı' => 'i', 'ğ' => 'g', 'ü' => 'u', 'ş' => 's', 'ö' => 'o', 'ç' => 'c',
            'İ' => 'i', 'Ğ' => 'g', 'Ü' => 'u', 'Ş' => 's', 'Ö' => 'o', 'Ç' => 'c',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ô' => 'o', 'û' => 'u',
            'à' => 'a', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ù' => 'u',
        ];

        $text = strtr($text, $replacements);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');

        return $text;
    }

    public static function unique(string $text, string $table, string $column, ?int $exceptId = null): string
    {
        $slug = self::generate($text);
        $db = \Core\Database::getInstance();

        $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = ?";
        $params = [$slug];

        if ($exceptId !== null) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $db->fetch($sql, $params);

        if (($result['total'] ?? 0) > 0) {
            $counter = 1;
            do {
                $newSlug = $slug . '-' . $counter;
                $params[0] = $newSlug;
                $result = $db->fetch($sql, $params);
                $counter++;
            } while (($result['total'] ?? 0) > 0);
            return $newSlug;
        }

        return $slug;
    }
}
