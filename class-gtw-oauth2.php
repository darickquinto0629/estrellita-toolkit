<?php

class GTW {

	//private $orgKey = '5952479236952433670';
	private $orgKey = '467765648861224935';
	private $client_id = 'c9f52b3c-add5-4890-8178-d1d9e6e49888';
	//d0d15c86-1f60-40be-871c-c6edf118ff04
	private $client_secret = '3t3pUZdjY0vAsKwU5Dd4Dp7h';
	private $goodStatus = array(200, 201);
	private $tokenExpire = '';

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


	public function getToken() {

		$accessToken = get_option('gtw_access_token');
		$refreshToken = get_option('gtw_refresh_token');

		if ($accessToken == '') {
			return false;
		}

		$status = $this->gtw_get_status($accessToken);

		if (!in_array($status, $this->goodStatus)) {

			$refreshToken = $this->getRefreshToken();

			$liveToken = $this->gtw_get_live_token($refreshToken);

			if ($liveToken != '') {

				update_option('gtw_access_token', $liveToken);
				update_option('gtw_token_expiration', time());

				return $liveToken;

			} else {

				return false;

			}

		}


		return $accessToken;

	}

	public function getRefreshToken() {

		return get_option('gtw_refresh_token');

	}

	public function getRegLink() {
		return 'https://api.getgo.com/oauth/v2/authorize?client_id='.$this->client_id.'&response_type=code';
	}





	public function gtw_register_user($trainingID, $userData) {

		$liveToken = $this->getToken();
		
		if (!$liveToken) {
			return 'error';
		} else {

			$curlURL = 'https://api.getgo.com/G2W/rest/organizers/'.$this->orgKey.'/webinars/'.$trainingID. '/registrants';

		  	$authorization = base64_encode("$this->client_id:$this->client_secret");

			$header = array(
				"Authorization: {$liveToken}",
				"Accept:application/json",
				"Content-Type: application/json"
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

	}


	public function gtw_get_token($authorization_code) {
		
		$authorization = base64_encode("$this->client_id:$this->client_secret");

		$header = array(
			"Authorization: Basic {$authorization}",
			"Accept:application/json",
			"Content-Type: application/x-www-form-urlencoded"
			);
		$content = "grant_type=authorization_code&code=$authorization_code";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.getgo.com/oauth/v2/token',
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $content
		));
		$response = curl_exec($curl);
		curl_close($curl);

		if ($response === false) {
			echo "Failed";
			echo curl_error($curl);
			echo "Failed";
		} elseif (json_decode($response)->error) {
			echo "Error:<br />";
			echo $authorization_code;
			echo $response;
		}

		return json_decode($response);

	}



	public function gtw_get_status($token) {

		//$liveToken = $this->getToken();


		$curl = curl_init();


		$curlURL = 'https://api.getgo.com/G2W/rest/v2/organizers/'.$this->orgKey.'/webinars';

	  	$authorization = base64_encode("$this->client_id:$this->client_secret");

		$header = array(
			"Authorization: {$token}",
			"Accept:application/json",
			"Content-Type: application/json"
			);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlURL,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return $response;

	}

	public function gtw_get_live_token($refresh_token) {

		$curlURL = 'https://api.getgo.com/oauth/v2/token';

	  	$authorization = base64_encode("$this->client_id:$this->client_secret");

		$header = array(
			"Authorization: Basic {$authorization}",
			"Accept:application/json",
			"Content-Type: application/x-www-form-urlencoded"
			);


		$content = 'grant_type=refresh_token&refresh_token='.$refresh_token;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlURL,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $content
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

		return $resp->access_token;

	}


	public function gtw_get_token_info($refresh_token) {

		$curlURL = 'https://api.getgo.com/oauth/v2/token';

	  	$authorization = base64_encode("$this->client_id:$this->client_secret");

		$header = array(
			"Authorization: Basic {$authorization}",
			"Accept:application/json",
			"Content-Type: application/x-www-form-urlencoded"
			);

		$content = 'grant_type=refresh_token&refresh_token='.$refresh_token;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlURL,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $content
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

	public function getRegistrants($webinarID) {

		$accessToken = get_option('gtw_access_token');
		$refreshToken = get_option('gtw_refresh_token');

		$liveToken = $accessToken;

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.getgo.com/G2W/rest/organizers/".$this->orgKey."/webinars/".$webinarID.'/registrants',
		  //https://api.getgo.com/G2W/rest/v2/accounts/{accountKey}/webinars
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Accept-Encoding: gzip, deflate",
		    "Authorization: Bearer ".$liveToken."",
		    "Cache-Control: no-cache",
		    "Connection: keep-alive",
		    "Host: api.getgo.com",
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$resp = json_decode($response);

		return $resp;
	}

	public function getWebinars($webinarID) {

		$accessToken = get_option('gtw_access_token');
		$refreshToken = get_option('gtw_refresh_token');

		$liveToken = $accessToken;

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.getgo.com/G2W/rest/organizers/".$this->orgKey."/webinars/".$webinarID,
		  //https://api.getgo.com/G2W/rest/v2/accounts/{accountKey}/webinars
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Accept-Encoding: gzip, deflate",
		    "Authorization: Bearer ".$liveToken."",
		    "Cache-Control: no-cache",
		    "Connection: keep-alive",
		    "Host: api.getgo.com",
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$resp = json_decode($response);

		return $resp;

	}

	public function getTraining($liveToken, $trainingID) {

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.getgo.com/G2W/rest/organizers/5952479236952433670/webinars/".$trainingID,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Accept-Encoding: gzip, deflate",
		    "Authorization: Bearer ".$liveToken."",
		    "Cache-Control: no-cache",
		    "Connection: keep-alive",
		    "Host: api.getgo.com",
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$resp = json_decode($response);

		return $resp;

	}

}