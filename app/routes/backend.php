<?php

// Added new hook to run before dispatch routes
$app->hook('slim.before.dispatch', function () use ($app) {

    // Check if route belong to the admin level and change some of configs
    $adminPattern = '/^\/admin\/.*/';
    $routePattern = $app->router->getCurrentRoute()->getPattern();
    if (preg_match($adminPattern, $routePattern)) {
        // Change global template path
        $app->config(array(
            'templates.path' => MAIN_PATH . '/views/backend',
        ));
        // Change twig engine template path
        $app->view->twigTemplateDirs = array(
            $app->config('templates.path'),
        );
    }

});

// Added new group container for admin routes
$app->group(
    '/admin',
    function () use ($app) {

        // Check route name if accessible without authenticate
        $routeName = $app->router->getCurrentRoute()->getName();
        $routeAllow = ['admin/users/login'];
        if (in_array($routeName, $routeAllow)) {
            return;
        }

        // Check if user not authenticated and redirect to login
        if (!isset($_SESSION['auth'])) {
            $app->flash('error', _('Login required'));
            $app->redirect($app->urlFor('admin/users/login'));
        }

    },
    function () use ($app) {

        // Define default route for admin root
        $app->get('/', function () use ($app) {
            // Redirect admin root to the sales index
            $app->redirect($app->urlFor('admin/users/index'));
            return;
        })->name('admin');

        // Include backend level routes
        require_once MAIN_PATH . '/routes/backend/users.php';
        require_once MAIN_PATH . '/routes/backend/events.php';

    }
);