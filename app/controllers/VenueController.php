<?php

class VenueController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getVenues()
	{
		
		if(Input::has('latitude') && Input::has('longitude') && Input::has('interests'))
		{
			
		}
		else
		{

		}
	}
}
