<?php

// Defined route for admin login of users
$app->get('/', function () use ($app) {
	
	$app -> render('layouts/home.twig');

	 
});