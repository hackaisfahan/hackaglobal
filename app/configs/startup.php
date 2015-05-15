<?php

// Prepare database confection
use RedBeanPHP\OODB;
use RedBeanPHP\ToolBox;
use RedBeanPHP\UUIDWriterMySQL;
use Slim\Middleware\SessionCookie;
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

R::setup('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
R::setAutoResolve(true);

// Create an extension to by-pass security check in R::dispense
R::ext('xdispense', function($type){
    return R::getRedBean()->dispense($type);
});

// Enabling UUID support in MySQL
$oldToolBox = R::getToolBox();
$oldAdapter = $oldToolBox->getDatabaseAdapter();
$uuidWriter = new UUIDWriterMySQL( $oldAdapter );
$newRedBean = new OODB( $uuidWriter );
$newToolBox = new ToolBox( $newRedBean, $oldAdapter, $uuidWriter );
R::configureFacadeWithToolbox( $newToolBox );

// Prepare app
$app = new Slim(array(
    'debug' => DEBUG_MODE,
    'mode' => 'development',
    'cache' => ROOT_PATH . '/cache/twig/',
    'templates.path' => MAIN_PATH . '/views/frontend',
));

// Prepare view
$app->view(new Twig());
$app->view->parserOptions = [
    'charset' => 'utf-8',
    'cache' => realpath('cache/twig'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
];
$app->view->parserExtensions = array(
    new TwigExtension()
);

// Added session cookie manager middleware
$app->add(
    new SessionCookie([
        'expires' => '180 minutes',
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => false,
        'name' => 'slim_session',
        'secret' => 'CHANGE_ME',
        'cipher' => MCRYPT_RIJNDAEL_256,
        'cipher_mode' => MCRYPT_MODE_CBC
    ])
);
