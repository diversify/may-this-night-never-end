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
	public $url = 'https://api.foursquare.com/v2/venues/search?ll={#latitude},{#longitude}&oauth_token={#oauthToken}&categoryId={#categoryId}&v=20141122&radius={#radius}';

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
			$this->url = str_replace("{#categoryId}", $this->venueCategories[Input::get('interest')], $this->url);
			$this->url = str_replace("{#oauthToken}", Config::get('other.access_token'), $this->url);
			$this->url = str_replace("{#radius}", '250', $this->url);
			$responseFromFsq = Requests::get($this->url);
			if ($responseFromFsq->status_code == 200) 
			{
				$responseObj = json_decode($responseFromFsq->body);
				$response['data'] = $responseObj->response->venues;
				if (!sizeof(!$responseJson)) 
				{
					$this->url = str_replace("{#radius}", '500', $this->url);
					$responseFromFsq = Requests::get($this->url);
					if ($responseFromFsq->status_code == 200) 
					{
						$response['status'] = 'success';
						$response['message'] = 'Here you go!';
						$responseObj = json_decode($responseFromFsq->body);
						$response['data'] = $responseObj->response->venues;
					}
					else
					{
						$response['status'] = 'success';
						$response['message'] = 'No venues found at all :(';
					}
				}
				else
				{
					$response['status'] = 'success';
					$response['message'] = 'Here you go!';
				}
			}
			else
			{
				$response['message'] = 'External api error occured';
			}
		}
		return Response::json($response)->setCallback(Input::get('callback'));
	}
	public function getCategories()
	{
		return Response::json(array_keys($this->venueCategories))->setCallback(Input::get('callback'));	
	}
}
