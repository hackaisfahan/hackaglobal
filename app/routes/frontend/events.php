<?php

//define route for events list  
$app -> get('/events/index', function() use($app){
	
	//fetch all events from now to future 
		$events = R::find('events', 'start_date >= :time', [
		'time' => time(),
	]) ;
	
	var_dump($events); exit;
	
	
	//if don't return any event create an error
	if ($events == false) {
        $app->flash('error', _("don't define any event form now to future"));
        // $app->redirect($app->urlFor('/events'));
        return;
    }
	
	//render list of events
	$app->render('frontend/events/list.twig', array($events)); 
	
	
	
});
