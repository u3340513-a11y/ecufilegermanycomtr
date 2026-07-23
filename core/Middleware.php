<?php

declare(strict_types=1);

namespace Core;

abstract class Middleware
{
    abstract public function handle(Request $request, callable $next): void;
}
