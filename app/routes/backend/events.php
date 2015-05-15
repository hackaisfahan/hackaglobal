<?php


// Defined route for admin create event
$app->map('/events/create', function () use ($app) {

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $app->render('backend/events/create.twig');
        return;
    }
	
	
	// Retrieve data from post and put to the events bean
	/**
	 * cityId have realation to cities bean tables
	 */
    $event = R::xdispense('events');
    $event->name = $app->request()->post('name');
    $event->desciption = $app->request()->post('description');

	$event -> city_id = $app -> request() -> post('city_id');
	$event -> startData = $app -> request() -> post('start_data');
	$event -> endData = $app -> request() -> post('end_data');
	$event -> startTime = $app -> request() -> post('start_time');
	$event -> endTime = $app -> request() -> post('end_time');
	
	$event -> address = $app -> request() -> post('address');
	

    R::begin();
    try {
        // Store user to the table and redirect to the index
        R::store($event);
        R::commit();

        
        // Redirect to the admin route
    	$app->redirect($app->urlFor('admin/events/index'));

    } catch (\RedBeanPHP\RedException $e) {
        // Rollback and display error as flash message and stay
        R::rollback();
        $app->flash('error', _('Something went wrong. Please try again.'));
        $app->redirect($app->urlFor('users/index'));
    }
    

})->via('GET', 'POST')->name('admin/events/create');
