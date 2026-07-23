<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

final class AuthMiddleware extends Middleware
{
    public function handle(Request $request, callable $next): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Bu sayfaya erişmek için giriş yapmalısınız.');
            $response = new Response();
            $response->redirect('/login');
        }

        $next();
    }
}
