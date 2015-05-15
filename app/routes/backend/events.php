<?php

// Defined route for admin events index
$app->get('/events', function () use ($app) {

    // Set page title for view
    $app->view()->setData('pageTitle', _('Events index'));

    // Get total events for now
    $events = R::find('events');

    // Render index using events data
    $app->render('events/index.twig', compact('events'));

})->name('admin/events/index');

// Defined route for admin create event
$app->map('/events/create', function () use ($app) {

    // Set page title for view
    $app->view()->setData('pageTitle', _('Events create'));

    // Check if request is get and render login template
    if ($app->request->isGet()) {
		$cities = R::find('cities');
		
    	$param = array(
			'time' => date(FULL_TIME),
			'date' => date(FULL_DATE),
			'cities' => $cities,
		); 	

        $app->render('events/create.twig', $param);

        return;
    }
	
	
	// Retrieve data from post and put to the events bean
	/**
	 * cityId have realation to cities bean tables
	 * map geo location don't added to form must be complate in future
	 */
	
    $event = R::xdispense('events');
    $event->name = (string)$app->request()->post('name');
    $event->desciption = (string)$app->request()->post('description');

	//get cities Id from form and create relation to cities	
	$event -> cities_id = $app -> request() -> post('cities_id');
	
	
	// to do list 
	// upload file must be complate in future 
	// var_dump($app -> request() -> post()); exit;	
	// $event -> file = $app -> request() -> post('file'); 
	
	
	//implode start and end date for create simple timeStamp in table
	$start_time = implode(" ", array($app -> request() -> post('start_date'), $app -> request() -> post('start_time')));
	$end_time = implode(" ", array($app -> request() -> post('end_date'), $app -> request() -> post('end_time')));
	$event -> start_date = new DateTime($start_time);
	$event -> end_date = new DateTime($end_time);
	
	
	//address of event 
	$event -> address = (string)$app -> request() -> post('address');
	
	//social media link 
	$event -> twitter = $app -> request() -> post('twitter'); 
	$event -> facebook = $app -> request() -> post('facebook');
	
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
        // $app->redirect($app->urlFor('admin/events/index'));
    }
    

})->via('GET', 'POST')->name('admin/events/create');

// Defined route for admin update event
$app->map('/events/update/:id', function ($id) use ($app) {

    // Set page title for view
    $app->view()->setData('pageTitle', _('Event update'));

    // Check if request is get and render login template
    if ($app->request->isGet()) {
        $cities = R::find('cities');
        $event = R::load('events', $id);
        $app->render('events/create.twig', compact('event', 'cities'));
        return;
    }

})->via('GET', 'POST')->name('admin/events/update');
