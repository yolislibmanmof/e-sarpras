<?php

declare(strict_types=1);

define('ENTRY', 'public');

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $scriptDir);
define('ADMIN_URL', $scriptDir . '/admin');
define('ENTRY_DIR', $scriptDir);

require dirname(__DIR__) . '/bootstrap/app.php';
require dirname(__DIR__) . '/routes/middleware.php';

use App\Core\App;
use App\Core\Router;

$router = new Router();
require dirname(__DIR__) . '/routes/public.php';

(new App($router))->run();