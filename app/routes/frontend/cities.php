<?php


// Defined route for event of any sities
$app-> get('/:city/event', function ($city) use ($app) {
	
	
	
	//search cities Id from cities name 
	$city_id = R::findOne('cities', 'name >= :name', array(
	'name' => $city));
	
	
	
	if (empty($city_id)){
		$app->flash('error', _("Don't set this cities please check your request"));
		return; 
	}
		
	$time = new DateTime();
	
	$event = R::findOne('events', 'cities_id = :city_id AND start_date > :date', array(
		'city_id' => $city_id -> id,
		"date" => $time -> format(implode(" ", array(FULL_DATE, FULL_TIME)))
	));
	
	print_r($event); exit;
	
	if (empty($event)){
		$app->flash('error', _("Don't set any in run event for $city"));
		return;
	}
	
	
	print_r($event); exit;
	
	
	
	
	//send all paraneter to view for parsing
	$render_param = array(
		'city'  => $city,
		'event' => $event
	);
	
	

});