<?php

// Defined route for login to the backend
$app->map('/login', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $app->render('users/login.twig');
        return;
    }

    // Search username and password in database
    $user = R::findOne('admin_user', 'username = :username AND password = :password', [
        'username' => $app->request->post('username'),
        'password' => sha1($app->request->post('password')),
    ]);

    // Redirect to the backend login page if user not found
    if ($user == false) {
        $app->flash('error', _('Username or password is wrong.'));
        $app->redirect($app->urlFor('admin/users/login'));
        return;
    }

    // Initialize auth session with user infractions
    $auth = array(
        'username' => $user->userName,
    );
    $_SESSION['auth'] = $auth;

    // Redirect to the admin route
    $app->redirect($app->urlFor('admin'));

})->via('GET', 'POST')->name('admin/users/login');