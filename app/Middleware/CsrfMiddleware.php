<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

final class CsrfMiddleware extends Middleware
{
    public function handle(Request $request, callable $next): void
    {
        if ($request->isPost()) {
            $token = $request->post('_csrf_token', '');

            if (!Session::verifyCsrfToken($token)) {
                if ($request->isAjax()) {
                    $response = new Response();
                    $response->json(['success' => false, 'message' => 'Geçersiz güvenlik tokeni.'], 403);
                }

                Session::flash('error', 'Güvenlik tokeni geçersiz. Lütfen tekrar deneyin.');
                $response = new Response();
                $response->back();
            }
        }

        $next();
    }
}
