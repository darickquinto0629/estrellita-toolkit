<?php

	define('ZOOM_METHOD_POST', 1);
	define('ZOOM_METHOD_PUT', 2);
	define('ZOOM_METHOD_GET', 3);
	define('ZOOM_RETURN_TYPE_JSON', 1);
	define('ZOOM_RETURN_TYPE_OBJECT', 2);
	define('ZOOM_RETURN_TYPE_ARRAY', 3);



class ZOOM {

	private $api_key = '467765648861224935';

	private $api_secret = 'DBZpnHFngvw3hXn1LrBXFYwqxrWQPQ0mwJXY';
	private $goodStatus = array(200, 201);
	private $jwt_token = 'eyJzdiI6IjAwMDAwMSIsImFsZyI6IkhTNTEyIiwidiI6IjIuMCIsImtpZCI6IjdlMDg5Y2M2LWQwNzUtNDU5OS1iNDNlLTFlNzFjYThmZGYxNyJ9.eyJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJoNGx2aXg5QVMtYWV5OFlfRno0NkpRIiwidmVyIjo5LCJhdWlkIjoiNDk0ZTE3ZDVlOWJjMmZmZjBlNzViOGRhOTc5Mjg4MDQiLCJuYmYiOjE3MTg2NjYyMDQsImNvZGUiOiI5OTRVeklQalJYbWQ3NVY1NzV4LUpRWU5IWU5DSlJ3ekoiLCJpc3MiOiJ6bTpjaWQ6MGp0bTBiVFZUUjIxb1p0c0JRemN3IiwiZ25vIjowLCJleHAiOjE3MTg2Njk4MDQsInR5cGUiOjMsImlhdCI6MTcxODY2NjIwNCwiYWlkIjoiQWE3S2JEVGhTMkdnOWZQUTJnV1J1QSJ9._JlVpfhj_qTgK3ihH28J1VgHl22ou15wF19zPgfKe_fj3KjPsZu0uo3xHvUO4tSywGJ2YKTjniBazfD02ynm7A';
	//private $jwt_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IngxOXRYanpDUm5DMk9HU1Y5bWtGZWciLCJleHAiOjE2Njk5MjQ4NjAsImlhdCI6MTY1OTU1MjU0NX0.x0iwWxdmQBJ3htIdIb_MycXT4F3mik_36qTi7DoLLkg';
	private $secret_token = 'ONX2QgPUQbKU_s34EDjUxg';
	private $verify_token = 'zEaaK9j-RAmmYtbbnsegvQ';
	//old admin@ private $account_id = '5041911718';
	//new christina
	private $account_id = 'Aa7KbDThS2Gg9fPQ2gWRuA';

	private $apiURL = 'https://api.zoom.us/v2/';

	private $timeout = 10;
	private $debug = false;
	private $advDebug = false;
	private $gttApiVersion = '1.0';
	private $beta = false;

	private $response;
	public $responseCode;
	private $returnType = GTT_RETURN_TYPE_OBJECT;
	private $putData;

 //old admin@estrellita creds 
	private $account_id_s2s = 'Aa7KbDThS2Gg9fPQ2gWRuA';
	private $client_id_s2s = '0jtm0bTVTR21oZtsBQzcw';
	private $client_secret_s2s = 'keZCHNLgPxsMLz3BiYlE03FEyqhF7aaC';
	private $secret_token_s2s = 'cs06u0d0TiioNo12tooS1g';
	private $verification_token_s2s = '8fhMVa0DTGeem4jmxKMWWg';
/*
// new christina@ creds
	private $account_id_s2s = 'Aa7KbDThS2Gg9fPQ2gWRuA';
	private $client_id_s2s = 'MrJIONDwSbmtfz7VMZTr3A';
	private $client_secret_s2s = '6cJ24CQf4zlOljaHsy2X5hLuj9kr0OlW';
	private $secret_token_s2s = '07P51uYCQneeqcMnFBRnvg';
	private $verification_token_s2s = 'fyPXVjf5TaOAIWVyPQB_VA';
*/
	private $endPointUrl;


	/**
	* Class constructor.
	*
	* @param array $options Array of options containing an auth token
	*
	*/

	public function __construct($options)
	{
	   
		if (is_array($options) ) {
		
		}

	}

	public function get_auth_token() {

		if (get_option('zoom_token_expires') < time()) {
			//get new auth code, set new & push expire time
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://zoom.us/oauth/token?grant_type=account_credentials&account_id=Aa7KbDThS2Gg9fPQ2gWRuA',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Basic MGp0bTBiVFZUUjIxb1p0c0JRemN3OmtlWkNITkxnUHhzTUx6M0JpWWxFMDNGRXlxaEY3YWFD',
			  ),
			));

			$response = curl_exec($curl);
			$jresp = json_decode($response);

			update_option('zoom_token', $jresp->access_token);
			update_option('zoom_token_expires', time() + $jresp->expires_in);

			return $jresp->access_token;

		} else {

			return get_option('zoom_token');

		}

	}

	public function getWebinars() {

		return $this->askZoom($this->apiURL.'users/me/webinars?page_size=300');

	}

	public function registerUser($webinarID, $userData) {

		$userReg = $this->user_is_registered($webinarID, $userData['email']);

		if (empty($userReg)) {
			return $this->register_user($webinarID, $userData);
		} else {
			return $userReg;
		}
		
	}

	public function getWebinar($webinarID)
	{
		
		return $this->askZoom($this->apiURL.'webinars/'.$webinarID.'/registrants');

	}

	public function register_user($webinarID, $userData) {
	
		$curlURL = "https://api.zoom.us/v2/webinars/".$webinarID."/registrants";

		$regToken = $this->get_auth_token();

		$header = array(
			"Authorization: Bearer {$regToken}",
			"Accept:application/json",
			"Content-Type:  application/json"
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlURL,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($userData)
		));

		$response = curl_exec($curl);
		curl_close($curl);

		if ($response === false) {
			echo "Failed";
			echo curl_error($curl);
			echo "Failed";
		} elseif (json_decode($response)->error) {
			echo "Error:<br />";
			echo $response;
		}

		$resp = json_decode($response);

		return $resp;


		
	}


	private function askZoom($url, $data = null, $method = ZOOM_METHOD_GET)
	{

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		if (!empty($this->jwt_token)) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			if (is_string($data) || empty($data['file'])) {
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer '.$this->get_auth_token().'',
					'Content-Type: application/json',
					'Accept: application/json'
				));
			}
			if ($data != null) {
				$method == ZOOM_METHOD_POST;
			}

		}

		if ($method == ZOOM_METHOD_POST) {
			curl_setopt($curl, CURLOPT_POST, true);
		} elseif ($method == ZOOM_METHOD_PUT) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		}
		if (!is_null($data) && ($method == ZOOM_METHOD_POST || $method == ZOOM_METHOD_PUT)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		}

		try {
			$this->response = curl_exec($curl);
			$this->responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($this->debug || $this->advDebug) {
				$info = curl_getinfo($curl);
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

	public function user_is_registered($webinarID, $email) {

		$regInfo = [];

		$attendees = $this->getWebinar($webinarID);

		foreach($attendees->registrants as $registrant) {
			if ($registrant->email == $email) {
				$regInfo['webinar'] = $webinarID;
				$regInfo['user'] = $registrant;
			}
		}

		if (count($regInfo) < 1) {
			return $regInfo;
		} else {
			return $regInfo;
		}


	}






}