<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Database;

final class RateLimitMiddleware extends Middleware
{
    private string $action;
    private int $maxAttempts;
    private int $window;

    public function __construct(string $action = 'default', int $maxAttempts = 60, int $window = 60)
    {
        $this->action = $action;
        $this->maxAttempts = $maxAttempts;
        $this->window = $window;
    }

    public function handle(Request $request, callable $next): void
    {
        $identifier = $request->ip();
        $db = Database::getInstance();

        $db->query(
            "DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$this->window]
        );

        $record = $db->fetch(
            "SELECT * FROM rate_limits WHERE identifier = ? AND action = ?",
            [$identifier, $this->action]
        );

        if ($record) {
            if ($record['attempts'] >= $this->maxAttempts) {
                $response = new Response();
                if ($request->isAjax()) {
                    $response->json([
                        'success' => false,
                        'message' => 'Çok fazla istek gönderdiniz. Lütfen biraz bekleyin.',
                    ], 429);
                }
                http_response_code(429);
                echo 'Çok fazla istek. Lütfen daha sonra tekrar deneyin.';
                exit;
            }

            $db->query(
                "UPDATE rate_limits SET attempts = attempts + 1 WHERE identifier = ? AND action = ?",
                [$identifier, $this->action]
            );
        } else {
            $db->insert('rate_limits', [
                'identifier'   => $identifier,
                'action'       => $this->action,
                'attempts'     => 1,
                'window_start' => date('Y-m-d H:i:s'),
            ]);
        }

        $next();
    }
}
