<?php

// Defined route for signup of users
$app->map('/signup', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request()->isGet()) {
        $app->render('frontend/users/signup.twig');
        return;
    }

    // Search email in database to prevent duplicate
    $user = R::findOne('users', 'email = :email', [
        'email' => $app->request()->post('email'),
    ]);

    // Redirect to signup page with error if user exist
    if ($user == false) {
        $app->flash('error', _('This email already has been used.'));
        $app->redirect($app->urlFor('users/login'));
        return;
    }

    // Retrieve data from post and put to the user bean
    $user = R::xdispense('users');
    $user->email = $app->request()->get('email');
    $user->password = sha1($app->request()->get('password'));

    R::begin();
    try {
        // Store user to the table and redirect to the index
        R::store($user);
        R::commit();

        // Initialize auth session with user infractions
        $auth = [
            'email' => $user->email,
        ];
        $_SESSION['auth'] = $auth;

        $app->redirect($app->urlFor('users/index'));

    } catch (\RedBeanPHP\RedException $e) {
        // Rollback and display error as flash message and stay
        R::rollback();
        $app->flash('error', _('Something went wrong. Please try again.'));
        $app->redirect($app->urlFor('users/index'));
    }

})->via('GET', 'POST')->name('users/signup');;

// Defined route for login of users
$app->map('/login', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $app->render('frontend/users/login.twig');
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
        $app->redirect($app->urlFor('users/login'));
        return;
    }

    // Initialize auth session with user infractions
    $auth = [
        'email' => $user->email,
    ];
    $_SESSION['auth'] = $auth;

    // Redirect to the admin route
    $app->redirect($app->urlFor('users/index'));

})->via('GET', 'POST')->name('users/login');