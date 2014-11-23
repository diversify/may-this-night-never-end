<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/v1/getVenues', 'VenueController@getVenues');
Route::get('/v1/getCategories', 'VenueController@getCategories');
App::missing(function($exception)
{
	return Response::json(array('status' => 'failed', 'message' => 'Endpoint not found!'));
});
