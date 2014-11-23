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
	public $urlForFsq = 'https://api.foursquare.com/v2/venues/search?ll={#latitude},{#longitude}&oauth_token={#oauthToken}&categoryId={#categoryId}&radius={#radius}&v=20141122';
	public $urlForSK = 'http://api.songkick.com/api/3.0/events.json?apikey={#apiKeySK}&location=geo:{#latitude},{#longitude}';


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
		else if(!Input::has('longitude'))
		{
			$response['message'] = 'Longitude is required';
		}
		else if(!Input::has('interest'))
		{
			$response['message'] = 'One interest is required';
		}
		else
		{
			$this->urlForFsq = str_replace("{#latitude}", Input::get('latitude'), $this->urlForFsq);
			$this->urlForFsq = str_replace("{#longitude}", Input::get('longitude'), $this->urlForFsq);
			$types = explode(',', Input::get('interest'));
			$categoryIds = '';
			foreach ($types as $key => $value) 
			{
				$categoryIds.=','.$this->venueCategories[$value];
			}
			$this->urlForFsq = str_replace("{#categoryId}", $categoryIds, $this->urlForFsq);
			$this->urlForFsq = str_replace("{#oauthToken}", Config::get('other.access_token'), $this->urlForFsq);
			$this->urlForFsq = str_replace("{#radius}", '5000', $this->urlForFsq);
			$responseFromFsq = Requests::get($this->urlForFsq);
			if ($responseFromFsq->status_code == 200) 
			{
				$responseObj = json_decode($responseFromFsq->body);
				$response['data'] = $responseObj->response->venues;
				if (!sizeof($response['data'])) 
				{
					$this->urlForFsq = str_replace("{#radius}", '7500', $this->urlForFsq);
					$responseFromFsqSecTry = Requests::get($this->urlForFsq);
					if ($responseFromFsqSecTry->status_code == 200) 
					{
						$response['status'] = 'success';
						$response['message'] = 'Here you go!';
						$responseObjSecTry = json_decode($responseFromFsqSecTry->body);
						$response['data'] = $responseObjSecTry->response->venues;
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
	public function getConcerts()
	{

		$response = array('status' => 'failed', 'message' => '');
		if (!Input::has('latitude')) 
		{
			$response['message'] = 'Latitude is required.';
		}
		else if(!Input::has('longitude'))
		{
			$response['message'] = 'Longitude is required';
		}
		else
		{
			$this->urlForSK = str_replace("{#latitude}", Input::get('latitude'), $this->urlForSK);
			$this->urlForSK = str_replace("{#longitude}", Input::get('longitude'), $this->urlForSK);
			$this->urlForSK = str_replace("{#apiKeySK}", Config::get('other.api_key_sk'), $this->urlForSK);
			$reponseFromSK = Requests::get($this->urlForSK);

			if ($reponseFromSK->status_code == 200) 
			{
				$responseObj = json_decode($reponseFromSK->body);
				$response['data'] = $responseObj->resultsPage->results;
				$response['status'] = 'success';
				$response['message'] = 'Here you go!';
			}
		}
		return Response::json($response)->setCallback(Input::get('callback'));
	}
}
