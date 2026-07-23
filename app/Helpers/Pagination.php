<?php

declare(strict_types=1);

namespace App\Helpers;

final class Pagination
{
    public static function render(int $currentPage, int $totalPages, string $baseUrl, array $queryParams = []): string
    {
        if ($totalPages <= 1) {
            return '';
        }

        $html = '<nav><ul class="pagination justify-content-center">';

        $prevDisabled = $currentPage <= 1 ? 'disabled' : '';
        $prevUrl = self::buildUrl($baseUrl, $currentPage - 1, $queryParams);
        $html .= '<li class="page-item ' . $prevDisabled . '"><a class="page-link" href="' . $prevUrl . '">‹</a></li>';

        $range = self::getRange($currentPage, $totalPages);

        if ($range[0] > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . self::buildUrl($baseUrl, 1, $queryParams) . '">1</a></li>';
            if ($range[0] > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
        }

        for ($i = $range[0]; $i <= $range[1]; $i++) {
            $active = $i === $currentPage ? 'active' : '';
            $url = self::buildUrl($baseUrl, $i, $queryParams);
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '">' . $i . '</a></li>';
        }

        if ($range[1] < $totalPages) {
            if ($range[1] < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . self::buildUrl($baseUrl, $totalPages, $queryParams) . '">' . $totalPages . '</a></li>';
        }

        $nextDisabled = $currentPage >= $totalPages ? 'disabled' : '';
        $nextUrl = self::buildUrl($baseUrl, $currentPage + 1, $queryParams);
        $html .= '<li class="page-item ' . $nextDisabled . '"><a class="page-link" href="' . $nextUrl . '">›</a></li>';

        $html .= '</ul></nav>';
        return $html;
    }

    private static function getRange(int $current, int $total): array
    {
        $delta = 2;
        $start = max(1, $current - $delta);
        $end = min($total, $current + $delta);
        return [$start, $end];
    }

    private static function buildUrl(string $baseUrl, int $page, array $queryParams): string
    {
        $queryParams['page'] = $page;
        return $baseUrl . '?' . http_build_query($queryParams);
    }
}
