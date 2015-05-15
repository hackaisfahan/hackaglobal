<?php

// Defined route for signup of users
$app->map('/signup', function () use ($app) {

    // Set page title for view
    $app->view()->setData('pageTitle', _('Users login'));

    // Check if request is get and render signup template
    if ($app->request()->isGet()) {
        $app->render('users/signup.twig');
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
    /** @noinspection PhpUndefinedMethodInspection */
    $user = R::xdispense('users');
    $user->email = $app->request()->post('email');
    $user->password = sha1($app->request()->post('password'));

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

// Defined route for profile of users
$app->map('/profile', function () use ($app) {

    // Get access to the user bean using current user email
    $user = R::findOne('users', 'email = :email', [
        'email' => $_SESSION['auth']['email']
    ]);

    // Check if request is get and render login template
    if ($app->request()->isGet()) {
        $app->render('users/profile.twig', compact('user'));
        return;
    }

    /** @noinspection PhpUndefinedMethodInspection */
    // Retrieve data from post and put to the user bean
    $user->firstName = $app->request()->post('firstName');
    $user->lastName = $app->request()->post('lastName');
    $user->cellPhone = $app->request()->post('cellPhone');

    // Check if password field is not empty update new password
    if (!empty($app->request()->post('password'))) {
        $user->password = sha1($app->request()->post('password'));
    }

    R::begin();
    try {
        // Store user to the table and redirect to the index
        R::store($user);
        R::commit();

        $app->redirect($app->urlFor('users/index'));
    } catch (\RedBeanPHP\RedException $e) {
        // Rollback and display error as flash message and stay
        R::rollback();
        $app->flash('error', _('Something went wrong. Please try again.'));
        $app->redirect($app->urlFor('users/index'));
    }

})->via('GET', 'POST')->name('users/profile');;


// Defined route for logout from backend
$app->get('/logout', function () use ($app) {

    // Check if user authenticated and unset session
    if (isset($_SESSION['auth'])) {
        unset($_SESSION['auth']);
    }

    // Redirect to the backend login page
    $app->redirect($app->urlFor('users/login'));

})->name('users/logout');