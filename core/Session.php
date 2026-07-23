<?php

declare(strict_types=1);

namespace Core;

final class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            $config = Config::get('app.session', []);

            session_set_cookie_params([
                'lifetime' => $config['lifetime'] ?? 7200,
                'path'     => '/',
                'domain'   => $config['domain'] ?? '',
                'secure'   => $config['secure'] ?? false,
                'httponly'  => true,
                'samesite' => 'Strict',
            ]);

            session_name($config['name'] ?? 'ECU_SESSION');
            session_start();
            self::$started = true;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$started = false;
    }

    public static function userId(): ?int
    {
        $id = self::get('user_id');
        return $id !== null ? (int) $id : null;
    }

    public static function isLoggedIn(): bool
    {
        return self::has('user_id');
    }

    public static function isAdmin(): bool
    {
        return self::get('user_role') === 'admin';
    }

    public static function setCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }

    public static function getCsrfToken(): string
    {
        if (!self::has('csrf_token')) {
            return self::setCsrfToken();
        }
        return self::get('csrf_token');
    }

    public static function verifyCsrfToken(string $token): bool
    {
        return hash_equals(self::get('csrf_token', ''), $token);
    }
}
