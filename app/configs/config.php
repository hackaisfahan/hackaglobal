<?php

// Set default time zone
date_default_timezone_set('Asia/Tehran');

// Set display error settings
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('short_open_tag', TRUE);

// Set database config constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'hackaglobal');

// Set language to Persian
putenv('LC_ALL=fa_IR');
setlocale(LC_ALL, 'fa_IR');

// Specify the location of the translation tables
bindtextdomain('app', 'locale');
bind_textdomain_codeset('app', 'UTF-8');

// Choose domain for gettext
textdomain('app');

// Set debug mode for app
define('DEBUG_MODE', true);