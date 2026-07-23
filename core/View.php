<?php

declare(strict_types=1);

namespace Core;

final class View
{
    private static string $basePath = '';
    private static array $shared = [];

    public static function init(string $basePath): void
    {
        self::$basePath = rtrim($basePath, '/') . '/resources/views';
    }

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        $data = array_merge(self::$shared, $data);
        extract($data);

        $viewFile = self::$basePath . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View dosyası bulunamadı: {$view}");
        }

        if ($layout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            $layoutFile = self::$basePath . '/layouts/' . $layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new \RuntimeException("Layout dosyası bulunamadı: {$layout}");
            }
            require $layoutFile;
        } else {
            require $viewFile;
        }
    }

    public static function partial(string $name, array $data = []): void
    {
        $data = array_merge(self::$shared, $data);
        extract($data);

        $file = self::$basePath . '/partials/' . $name . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }

    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function csrf(): string
    {
        $token = Session::getCsrfToken();
        return '<input type="hidden" name="_csrf_token" value="' . self::escape($token) . '">';
    }

    public static function old(string $key, string $default = ''): string
    {
        $old = Session::getFlash('old_input', []);
        return self::escape($old[$key] ?? $default);
    }

    public static function error(string $key): string
    {
        $errors = Session::getFlash('errors', []);
        if (isset($errors[$key])) {
            return '<div class="invalid-feedback d-block">' . self::escape($errors[$key]) . '</div>';
        }
        return '';
    }

    public static function hasError(string $key): bool
    {
        $errors = Session::getFlash('errors', []);
        return isset($errors[$key]);
    }

    public static function errors(): array
    {
        return Session::getFlash('errors', []);
    }

    public static function alert(): string
    {
        $html = '';

        if ($success = Session::getFlash('success')) {
            $html .= '<div class="alert alert-success alert-dismissible fade show">'
                . self::escape($success)
                . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }

        if ($errorMsg = Session::getFlash('error')) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show">'
                . self::escape($errorMsg)
                . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }

        if ($warning = Session::getFlash('warning')) {
            $html .= '<div class="alert alert-warning alert-dismissible fade show">'
                . self::escape($warning)
                . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }

        return $html;
    }
}
