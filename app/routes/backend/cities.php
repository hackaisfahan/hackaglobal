<?php


// Defined route for admin create event
$app->map ('/cities/create', function () use ($app) {
	

    // Check if request is get create grid list of cities 
    if ($app->request->isGet()) {	
	$cities = R::find('cities');
		
	$params = array(
		'cities' => $cities
	);
	
	$app -> render ("cities/lists.twig", $params);
	
	return; 
    }
	
	
	$city = R::xdispense('cities');
    $city->name = (string)$app->request()->post('name');
	
	
	
	R::begin();
    try {
        // Store user to the table and redirect to the index
        R::store($city);
        R::commit();

        
        // Redirect to the admin route
    	 $app->redirect($app->urlFor('admin/events/index'));

    } catch (\RedBeanPHP\RedException $e) {
        // Rollback and display error as flash message and stay
        R::rollback();
        $app->flash('error', _('Something went wrong. Please try again.'));
        // $app->redirect($app->urlFor('admin/events/index'));
    }
	
	
	// $city -> organizer = $_SESSION['auth']
    
	
	// to do list 
	// upload file must be complate in future 
	// var_dump($app -> request() -> post()); exit;	
	// $event -> file = $app -> request() -> post('file');
	
	
})->via('GET', 'POST')->name('admin/cities/index');
