<?php

declare(strict_types=1);

namespace Core;

final class App
{
    private static string $basePath;
    private Router $router;
    private Request $request;

    public function __construct(string $basePath)
    {
        self::$basePath = $basePath;
        $this->bootstrap();
    }

    private function bootstrap(): void
    {
        $this->setErrorHandling();
        Config::load(self::$basePath);
        Session::start();
        View::init(self::$basePath);

        View::share('csrf_token', Session::getCsrfToken());
        View::share('auth_user', $this->getAuthUser());
        View::share('base_url', Config::get('app.url', ''));

        try {
            $db = Database::getInstance();
            $logoRow = $db->fetch("SELECT value FROM settings WHERE key_name = 'site_logo'");
            View::share('site_logo', $logoRow['value'] ?? '');
        } catch (\Throwable $e) {
            View::share('site_logo', '');
        }

        $this->request = new Request();
        $this->router  = new Router();
        $this->loadRoutes();
    }

    private function setErrorHandling(): void
    {
        $isDebug = Config::get('app.debug', false);
        error_reporting(E_ALL);
        ini_set('display_errors', $isDebug ? '1' : '0');
        ini_set('log_errors', '1');
        ini_set('error_log', self::$basePath . '/storage/logs/error.log');

        set_exception_handler(function (\Throwable $e) {
            $isDebug = Config::get('app.debug', false);
            error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            if (isset($this->request) && $this->request->isAjax()) {
                $response = new Response();
                $response->json([
                    'success' => false,
                    'message' => $isDebug ? $e->getMessage() : 'Sunucu hatası oluştu.',
                ], 500);
            }

            http_response_code(500);
            if ($isDebug) {
                echo '<h1>Hata</h1>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            } else {
                $errorView = self::$basePath . '/resources/views/errors/500.php';
                if (file_exists($errorView)) {
                    require $errorView;
                } else {
                    echo '<h1>Sunucu Hatası</h1><p>Bir hata oluştu, lütfen daha sonra tekrar deneyin.</p>';
                }
            }
            exit;
        });
    }

    private function getAuthUser(): ?array
    {
        if (!Session::isLoggedIn()) {
            return null;
        }

        try {
            $db = Database::getInstance();
            return $db->fetch(
                'SELECT id, name, email, phone, company, avatar, credit_balance, role, is_active FROM users WHERE id = ?',
                [Session::userId()]
            );
        } catch (\Throwable) {
            return null;
        }
    }

    private function loadRoutes(): void
    {
        $routeFile = self::$basePath . '/config/routes.php';
        if (file_exists($routeFile)) {
            $router = $this->router;
            require $routeFile;
        }
    }

    public function run(): void
    {
        $matched = $this->router->resolve($this->request);

        if ($matched === null) {
            $response = new Response();
            $response->notFound();
        }

        $this->executeMiddleware($matched['middleware'], function () use ($matched) {
            $this->dispatch($matched['action'], $matched['params']);
        });
    }

    private function executeMiddleware(array $middleware, callable $final): void
    {
        $middlewareStack = array_reverse($middleware);
        $next = $final;

        foreach ($middlewareStack as $mw) {
            $currentNext = $next;
            $next = function () use ($mw, $currentNext) {
                $instance = new $mw();
                $instance->handle($this->request, $currentNext);
            };
        }

        $next();
    }

    private function dispatch(string $action, array $params): void
    {
        [$controllerClass, $method] = explode('@', $action);

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller bulunamadı: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Method bulunamadı: {$controllerClass}@{$method}");
        }

        $controller->$method($this->request, ...array_values($params));
    }

    public static function basePath(string $path = ''): string
    {
        return self::$basePath . ($path ? '/' . ltrim($path, '/') : '');
    }

    public static function storagePath(string $path = ''): string
    {
        return self::basePath('storage' . ($path ? '/' . ltrim($path, '/') : ''));
    }

    public static function publicPath(string $path = ''): string
    {
        return self::basePath('public' . ($path ? '/' . ltrim($path, '/') : ''));
    }

    public static function url(string $path = ''): string
    {
        $baseUrl = Config::get('app.url', '');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    public static function asset(string $path): string
    {
        $filePath = self::$basePath . '/assets/' . ltrim($path, '/');
        $version  = file_exists($filePath) ? filemtime($filePath) : time();
        return self::url('assets/' . ltrim($path, '/')) . '?v=' . $version;
    }
}
