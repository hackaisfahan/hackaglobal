<?php


// Defined route for event of any sities
$app-> get('/:city/event', function ($city) use ($app) {
	
	//search cities Id from cities name 
	$city_id = R::find('cities', 'name >= :name', array(
	'name' => $city));
	
	if ($city_id == false){
		$app->flash('error', _("Don't set this cities please check your request"));
	}
	
	$time = new DateTime();
	
	$event = R::find('events', 'cities_id = :city_id AND start_date >= :time', array(
		'cities_id' => $city_id,
		"time" => $time -> format(implode(" ", array(FULL_DATE, FULL_TIME)))
	));
	
	if ($city_id == false){
		$app->flash('error', _("Don't set any in run event for $city"));
	}
	
	
	
	
	//send all paraneter to view for parsing
	$render_param = array(
		'city'  => $city,
		'event' => $event
	);
	
	
});