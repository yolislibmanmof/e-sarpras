<?php

declare(strict_types=1);

define('ENTRY', 'admin');

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('ADMIN_URL', $scriptDir);
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($scriptDir)), '/'));
define('ENTRY_DIR', $scriptDir);

require dirname(__DIR__, 2) . '/bootstrap/app.php';
require dirname(__DIR__, 2) . '/routes/middleware.php';

use App\Core\App;
use App\Core\Router;

$router = new Router();
require dirname(__DIR__, 2) . '/routes/admin.php';

(new App($router))->run();