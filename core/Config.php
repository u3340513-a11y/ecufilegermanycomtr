<?php

declare(strict_types=1);

namespace Core;

final class Config
{
    private static array $config = [];

    public static function load(string $basePath): void
    {
        self::loadEnv($basePath . '/.env');

        $configPath = $basePath . '/config';
        $files = glob($configPath . '/*.php');
        $skip = ['routes', 'services'];

        foreach ($files as $file) {
            $key = basename($file, '.php');
            if (in_array($key, $skip, true)) {
                continue;
            }
            self::$config[$key] = require $file;
        }
    }

    private static function loadEnv(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);

            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                $value = substr($value, 1, -1);
            }

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("{$name}={$value}");
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $keys  = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public static function set(string $key, mixed $value): void
    {
        $keys   = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $config[$k] = $value;
            } else {
                if (!isset($config[$k]) || !is_array($config[$k])) {
                    $config[$k] = [];
                }
                $config = &$config[$k];
            }
        }
    }
}
