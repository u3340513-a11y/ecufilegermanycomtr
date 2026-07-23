<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;
use Core\Request;

final class XssMiddleware extends Middleware
{
    public function handle(Request $request, callable $next): void
    {
        $_POST = $this->clean($_POST);
        $_GET = $this->clean($_GET);
        $next();
    }

    private function clean(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleaned[$key] = $this->clean($value);
            } else {
                $cleaned[$key] = strip_tags((string) $value);
            }
        }
        return $cleaned;
    }
}
