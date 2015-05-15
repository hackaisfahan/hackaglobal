<?php

// Define directory constants
define('ROOT_PATH', realpath(__DIR__));
define('MAIN_PATH', ROOT_PATH . '/app');
define('TEMP_PATH', ROOT_PATH . '/temps');
define('PUBS_PATH', ROOT_PATH . '/public');
define('VEND_PATH', ROOT_PATH . '/vendor');
define('UPLD_PATH', ROOT_PATH . '/upload');

// Include composer autoload
require_once VEND_PATH . '/autoload.php';

// Include configs and startup
require_once MAIN_PATH . '/configs/config.php';
require_once MAIN_PATH . '/configs/global.php';

// Include frontend and backend end routes
require_once MAIN_PATH . '/routes/backend.php';
require_once MAIN_PATH . '/routes/frontend.php';

// Run app using slim router
$app->run();