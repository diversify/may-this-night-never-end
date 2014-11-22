<?php
Requests::register_autoloader();

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
	public $url = 'https://api.foursquare.com/v2/venues/search?ll={#latitude},{#longitude}&oauth_token={#oauthToken}&categoryId={#categoryId}&v=20141122';

	public $venueCategories = array(
		'rock' => '4bf58dd8d48988d1e9931735',
		'club' => '52e81612bcbc57f1066b7a35', //currently: Club House, nightlife => 4d4b7105d754a06376d81259
		'dinner' => '4d4b7105d754a06374d81259',
		'jazz' => '4bf58dd8d48988d1e7931735',
		'piano' => '4bf58dd8d48988d1e8931735'
	);
	public function getVenues()
	{
		$response = array('status' => 'failed', 'message' => '');
		if (!Input::has('latitude')) 
		{
			$response['message'] = 'Latitude is required.';
		}
		if(!Input::has('longitude'))
		{
			$response['message'] = 'Longitude is required';
		}
		else if(!Input::has('interest'))
		{
			$response['interest'] = 'One interest is required';
		}
		else
		{
			$this->url = str_replace("{#latitude}", Input::get('latitude'), $this->url);
			$this->url = str_replace("{#longitude}", Input::get('longitude'), $this->url);
			$this->url = str_replace("{#categoryId}", $venueCategories[Input::get('interest')], $this->url);
			$this->url = str_replace("{#oauthToken}", Config::get('other.access_token'), $this->url);
			$response = Requests::get('https://github.com/timeline.json');
			var_dump($response->body);
		}
		return Response::json($response);
	}
}
