<?php


// Defined route for admin create event
$app->map('/events/create', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $app->render('backend/events/create.twig');
        return;
    }
	
	
	// Retrieve data from post and put to the user bean
    $event = R::xdispense('events');
    $event->name = $app->request()->post('name');
    $event->desciption = $app->request()->post('description');

	print_r($event); exit;

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

        // Redirect to the admin route
    	$app->redirect($app->urlFor('admin/events/index'));

    } catch (\RedBeanPHP\RedException $e) {
        // Rollback and display error as flash message and stay
        R::rollback();
        $app->flash('error', _('Something went wrong. Please try again.'));
        $app->redirect($app->urlFor('users/index'));
    }
    

})->via('GET', 'POST')->name('admin/events/create');
