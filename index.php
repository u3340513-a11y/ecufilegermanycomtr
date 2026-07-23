<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

require BASE_PATH . '/core/helpers.php';
require BASE_PATH . '/vendor/autoload.php';

$app = new Core\App(BASE_PATH);
$app->run();
