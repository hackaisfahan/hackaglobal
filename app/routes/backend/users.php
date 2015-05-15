<?php

// Defined route for admin login of users
$app->map('/login', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $app->render('users/login.twig');
        return;
    }

    // Search email and password in database
    $user = R::findOne('users', 'email = :email AND password = :password', [
        'email' => $app->request->post('email'),
        'password' => sha1($app->request->post('password')),
    ]);

    // Redirect to login page if user not found
    if ($user == false) {
        $app->flash('error', _('Username or password is wrong.'));
        $app->redirect($app->urlFor('admin/users/login'));
        return;
    }

    // Initialize auth session with user infractions
    $auth = [
        'email' => $user->email,
    ];
    $_SESSION['auth'] = $auth;

    // Redirect to the admin route
    $app->redirect($app->urlFor('admin/users/index'));

})->via('GET', 'POST')->name('admin/users/login');


// Defined route for logout from backend
$app->get('/logout', function () use ($app) {

    // Check if user authenticated and unset session
    if (isset($_SESSION['auth'])) {
        unset($_SESSION['auth']);
    }

    // Redirect to the backend login page
    $app->redirect($app->urlFor('admin/users/login'));

})->name('admin/users/logout');