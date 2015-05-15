<?php


// Defined route for admin create event
$app->map ('/cities/index', function () use ($app) {
	

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
    
	
	// to do list 
	// upload file must be complate in future 
	// var_dump($app -> request() -> post()); exit;	
	// $event -> file = $app -> request() -> post('file');
	
	
})->via('GET', 'POST')->name('admin/cities/index');
