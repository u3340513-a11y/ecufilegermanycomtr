<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

final class AdminMiddleware extends Middleware
{
    public function handle(Request $request, callable $next): void
    {
        if (!Session::isAdmin()) {
            $response = new Response();
            $response->forbidden();
        }

        $next();
    }
}
