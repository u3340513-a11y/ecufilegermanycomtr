<?php

declare(strict_types=1);

namespace Core;

final class ServiceContainer
{
    private static array $bindings = [];
    private static array $instances = [];

    public static function bind(string $abstract, callable $factory): void
    {
        self::$bindings[$abstract] = $factory;
    }

    public static function singleton(string $abstract, callable $factory): void
    {
        self::$bindings[$abstract] = function () use ($abstract, $factory) {
            if (!isset(self::$instances[$abstract])) {
                self::$instances[$abstract] = $factory();
            }
            return self::$instances[$abstract];
        };
    }

    public static function make(string $abstract): mixed
    {
        if (isset(self::$bindings[$abstract])) {
            return (self::$bindings[$abstract])();
        }

        if (class_exists($abstract)) {
            return new $abstract();
        }

        throw new \RuntimeException("Bağımlılık çözümlenemedi: {$abstract}");
    }

    public static function has(string $abstract): bool
    {
        return isset(self::$bindings[$abstract]);
    }

    public static function flush(): void
    {
        self::$bindings = [];
        self::$instances = [];
    }
}
