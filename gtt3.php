<?php


define('SCRM_METHOD_POST', 1);
define('SCRM_METHOD_PUT', 2);
define('SCRM_METHOD_GET', 3);
define('SCRM_METHOD_DELETE', 4);
define('SCRM_RETURN_TYPE_JSON', 1);
define('SCRM_RETURN_TYPE_OBJECT', 2);
define('SCRM_RETURN_TYPE_ARRAY', 3);


/*
https://api.citrixonline.com/G2T/rest/organizers/5952479236952433670/trainings/6385495524262352129/registrants
https://api.citrixonline.com/G2T/rest/organizers/5952479236952433670/trainings/6385495524262352129/registrants

PUT   /organizers/{organizerKey}/trainings/{trainingKey}/registrationSettings  
*/

//$addUserResponse = $gtt->addRegistrant(array('email' => 'newtester0@example.com','givenName' => 'John3','surname' => 'Doe'));


//$trainingID = '1839562500570872066';


$tids = array(
	'5577306097545959937', 
	'3785099945247976450', 
	'1398118475575081986', 
	'3151180914919256578', 
	'5855147188946136578', 
	'8429550312942027777', 
	'6173023698769123329', 
	'1290006797909012993',
	'3340279323613954562',
	'5447414191886914305',
	'9157379729371666178',
	'8057622912660793601',
	'6895751383403993345'
	);

foreach($tids as $trainingID) {

	$authToken = '1oJZNopdXnsfFi6iOLQf79GLGBXY';

	$gtt = new G2T(array('authToken' => $authToken, 'trainingID' => $trainingID));

	echo '<h1>Update Training Settings</h1>';

	$updateResponse = $gtt->updateTraining(array('disableConfirmationEmail' => true, 'disableWebRegistration' => false));

	echo '<hr>update:<br><pre>'; print_r($updateResponse); echo '</pre>';

	$gtt = new G2T(array('authToken' => $authToken, 'trainingID' => $trainingID));

	$trainingInfo = $gtt->getTraining();

	echo '<hr>training info: <pre>'; print_r($trainingInfo); echo '</pre>';

}

	// $startTimeStamp = strtotime($trainingInfo->times[0]->startDate);
	// $endTimeStamp = strtotime($trainingInfo->times[0]->endDate);

	// echo 'start: '.$startTimeStamp.'<br>';
	// echo 'end: '.$endTimeStamp.'<br>';

	// echo 'first notice: '.strtotime('-7 day', $startTimeStamp);;

	// echo 'second notice: '.strtotime('-1 day', $startTimeStamp);

	// echo '<hr>-oo-<hr>';
	
	// if (!empty($addUserResponse)) {
	// 	echo '<h1>User Added</h1>';

	// 	$joinUrl = $addUserResponse->joinUrl;
	// 	$confirmationUrl = $addUserResponse->confirmationUrl;
	// 	$registrantKey = $addUserResponse->registrantKey;

	// 	echo $joinUrl.'<hr>';
	// 	echo $confirmationUrl.'<hr>';
	// 	echo $registrantKey.'<hr>';

	// }




class G2T {

	private $timeout = 10;
	private $debug = false;
	private $advDebug = false;
	private $gttApiVersion = '1.0';
	private $beta = true;

	private $response;
	public $responseCode;
	private $returnType = GTT_RETURN_TYPE_OBJECT;
	private $putData;

	private $endPointUrl;
	private $authToken = '';

	/**
	* Class constructor.
	*
	* @param array $options Array of options containing an auth token
	*
	*/

	public function __construct($options)
	{
	   
		if (is_string($options)) {
			$this->authToken = $options;
		} elseif (is_array($options) && !empty($options['authToken'])) {
			$this->authToken = $options['authToken'];
		} else {
			throw new Exception('You need to specify an Authorization Token');
		}

		if (is_array($options) && !empty($options['returnType'])) {
			$this->setReturnType($options['returnType']);
		}

		$this->endPointUrl = 'https://api.citrixonline.com/G2T/rest/organizers/5952479236952433670/';

		$this->trainingsURL = $this->endPointUrl . 'trainings/';
		$this->trainingURL = $this->endPointUrl . 'trainings/'.$options['trainingID'];
		$this->registrantsURL = $this->endPointUrl . 'trainings/'. $options['trainingID'] . '/registrants';
		$this->updateTrainingURL = $this->endPointUrl . 'trainings/'. $options['trainingID'] . '/registrationSettings';

	}


	/**
	* Returns the api data
	*
	*/

	public function getTrainings()
	{
		return $this->askGtt($this->trainingsURL);
	}

	public function getTraining()
	{
		return $this->askGtt($this->trainingURL);
	}

  

	/* put the data */

	public function addRegistrant($data)
	{
		$data = json_encode($data);

		return $this->askGtt($this->registrantsURL, $data, GTT_METHOD_POST);
	}

	public function updateTraining($data)
	{
		$data = json_encode($data);

		return $this->askGtt($this->updateTrainingURL, $data, GTT_METHOD_PUT);
	}

	/**
	* This function communicates with REST API.
	*
	* @param string $url
	* @param string $data Must be a json string
	* @return string JSON or null
	*/
	private function askGtt($url, $data = null, $method = GTT_METHOD_GET)
	{

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		if (!empty($this->authToken)) {
			// Send with auth token.
			curl_setopt($curl, CURLOPT_USERPWD, $this->authToken);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			if (is_string($data) || empty($data['file'])) {
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Accept: application/json',
					'Authorization: '.$this->authToken
				));
			}
		}

		if ($this->advDebug) {
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_VERBOSE, true);
		}

		if ($method == GTT_METHOD_POST) {
			curl_setopt($curl, CURLOPT_POST, true);
		} elseif ($method == GTT_METHOD_PUT) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		} elseif ($method == GTT_METHOD_DELETE) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		if (!is_null($data) && ($method == GTT_METHOD_POST || $method == GTT_METHOD_PUT)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		try {
			$this->response = curl_exec($curl);
			$this->responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($this->debug || $this->advDebug) {
				$info = curl_getinfo($curl);
				echo '<pre>';
				print_r($info);
				echo '</pre>';
				if ($info['http_code'] == 0) {
					echo '<br>cURL error num: ' . curl_errno($curl);
					echo '<br>cURL error: ' . curl_error($curl);
				}
			}
		} catch (Exception $ex) {
			if ($this->debug || $this->advDebug) {
			echo '<br>cURL error num: ' . curl_errno($curl);
			echo '<br>cURL error: ' . curl_error($curl);
			}
			echo 'Error on cURL';
			$this->response = null;
		}

		curl_close($curl);

		return json_decode($this->response);

	}

	/**
	* Set the return type.
	*
	* @param int $type Return type defined in the constants.
	* @return Gtt
	*/
	public function setReturnType($type)
	{
		$this->returnType = $type;

		return $this;
	}

	/**
	* Checks for errors in the response.
	*
	* @return boolean
	*/
	public function hasError()
	{
		return !in_array($this->responseCode, array(200, 201)) || is_null($this->response);
	}

	/**
	* Decodes the response and returns as an object, array.
	*
	* @return object, array, string or null
	*/
	public function getData()
	{
		if (!$this->hasError()) {
			$array  = $this->returnType == GTT_RETURN_TYPE_ARRAY;
			$return = json_decode($this->response, $array);

			if ($array && isset($return['data'])){
			return $return['data'];
			} elseif ($this->returnType == GTT_RETURN_TYPE_OBJECT && isset($return->data)){
			return $return->data;
			} elseif ($this->returnType == GTT_RETURN_TYPE_JSON){
			return $this->response;
			}

		}

		return null;

	}

}

