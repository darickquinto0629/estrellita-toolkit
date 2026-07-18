<?php


function gtt_admin_notice__error() {

	$class = 'notice notice-error';

	$message = __( 'Your API token is out of date. Please re-issue a new token.', 'silibas' );

	printf( '<div class="%1$s"><p><a href="/wp-admin/tools.php?page=gtt-central">%2$s</a></p></div>', esc_attr( $class ), esc_html( $message ) ); 

	silibas_send_error('GTT API ERROR', 'Please re-issue an api token to reconnect GTT.');

}


add_action('admin_init', 'checkgtttoken');

function checkgtttoken() {


}


add_action('admin_menu', 'zoom_log_function');

function zoom_log_function() {
	add_management_page('Zoom Central', 'Zoom Central', 'edit_theme_options', 'zoom-central', 'zoom_log_page');
}

function zoom_log_page() {

	global $wpdb, $post, $options, $woocommerce;

	$showCurrentTrainings = FALSE;

	?>
	
	<div class="wrap">

		<h1>Zoom API Central</h1>
		<?php


		$zoom = new ZOOM(array(''));

		$token = $zoom->get_auth_token();

		//silbas_woocommerce_order_status_completed_gtw('88452');

		echo '<hr><hr><pre>'; print_r($token, true); echo '</pre>';
		//echo 'token<br>reg users<hr>';

		$today = date('Y-m-d', time());
		$zoom = new ZOOM(array(''));

		$web = $zoom->getWebinars();

		echo 'get webinars check<hr><pre>'; print_r($web); echo '</pre><hr>';


//meeting ID 81865457540



//echo '<h3>get: '.$zoom->get_auth_token().'</h3>';

// 		$userData = [];
// 		$userData['first_name'] = 'Joe';
// 		$userData['last_name'] = 'Tester 2';
// 		$userData['email'] = 'searletest1@mailinator.com';

// 		$authToken = $zoom->get_auth_token();
// 		echo '<h4>token</h4>';
// 		echo $authToken.'<br>';
// 		//$curlURL = 'https://api.zoom.us/v2/users/me/webinars?page_size=300s';
// 		$curlURL = "https://api.zoom.us/v2/webinars/83810271426/registrants";

// 		$header = array(
// 			"Authorization: Bearer {$authToken}",
// 			"Accept:application/json",
// 			"Content-Type:  application/json"
// 		);

// 		$curl = curl_init();

// 		curl_setopt_array($curl, array(
// 			CURLOPT_URL => $curlURL,
// 			CURLOPT_HTTPHEADER => $header,
// 			CURLOPT_SSL_VERIFYPEER => false,
// 			CURLOPT_RETURNTRANSFER => true,
// 			//CURLOPT_CUSTOMREQUEST => "GET",
// 			CURLOPT_POST => true,
// 			CURLOPT_POSTFIELDS => json_encode($userData)
// 		));
		
// 		$response = curl_exec($curl);
// 		curl_close($curl);

// 		if ($response === false) {
// 			echo "Failed";
// 			echo curl_error($curl);
// 			echo "Failed";
// 		} elseif (json_decode($response)->error) {
// 			echo "Error:<br />";
// 			echo $response;
// 		}

// 		$resp = json_decode($response);


// echo 'resp<hr><pre>'; print_r($resp); echo '</pre><hr>';

// 		$zoom = new ZOOM(array(''));

// echo 'new zoom<hr><pre>'; print_r($zoom); echo '</pre><hr>';


		// $regdata = $zoom->registerUser('83810271426', $userData);

// 		// echo 'register user<hr><pre>'; print_r($regdata, true); echo '</pre><hr>';
// 			$curl = curl_init();

// //cs06u0d0TiioNo12tooS1g

// 			curl_setopt_array($curl, array(
// 			  CURLOPT_URL => 'https://zoom.us/oauth/token?grant_type=account_credentials&account_id=Aa7KbDThS2Gg9fPQ2gWRuA',
// 			  CURLOPT_RETURNTRANSFER => true,
// 			  CURLOPT_ENCODING => '',
// 			  CURLOPT_MAXREDIRS => 10,
// 			  CURLOPT_TIMEOUT => 0,
// 			  CURLOPT_FOLLOWLOCATION => true,
// 			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 			  CURLOPT_CUSTOMREQUEST => 'POST',
// 			  CURLOPT_HTTPHEADER => array(
// 			    'Authorization: Basic MGp0bTBiVFZUUjIxb1p0c0JRemN3OmtlWkNITkxnUHhzTUx6M0JpWWxFMDNGRXlxaEY3YWFD',
// 			  ),
// 			));

// 			$response = curl_exec($curl);
// 			$jresp = json_decode($response);

// 			update_option('zoom_token', $jresp->access_token);
// 			update_option('zoom_token_expires', time() + $jresp->expires_in);

// 			echo 'jresp<br><pre>';

// 			print_r($jresp);
// 			echo '</pre>';

// 			echo '<h3>token '.$jresp->access_token.'<br>expires: '.$jresp->expires_in.'</h3>';


		echo '<h2>APIs</h2>';

//application/x-www-form-urlencoded



		$today = date('Y-m-d', time());
		$zoom = new ZOOM(array(''));

		//check creds

		$webinars = $zoom->getWebinars();

		if (empty($webinars)) {
			
			echo '<h2>API Error(s)</h2>';

			echo '<pre>';
			print_r($zoom);
			echo '</pre>';

		} else {

			echo '<h1 style="margin-bottom:40px;">Upcoming Webinars</h1>';

			?>
			<table class="wp-list-table widefat fixed striped table-view-list posts">
				<thead>
					<td>Name</td>
					<td>ID</td>
					<td>Link</td>
					<td>Date</td>
				</thead>
				<tbody id="zoom-list">
			<?php

			foreach($webinars->webinars as $webinar) {

				$type = '';
				$timeInfo = explode('T', $webinar->start_time);
				$startDate = $timeInfo[0];
				$startTime = strtotime($startDate);

				if ($startDate >= $today) {
				echo '<tr>';
					if (strpos($webinar->topic, 'National') !== false) {

						$trainingProductID = siliabs_get_product_id_by_variation_sku($webinar->id);


						echo '<td>'.$webinar->topic.'</td>';
						echo '<td>'.$webinar->id.'</td>';

						if (!$trainingProductID) {
							echo '<td style="color:#b20000;">x</td>';
						} else {
							echo '<td><a href="/wp-admin/post.php?post='.$trainingProductID.'&action=edit" target="_blank">Edit</a></td>';					
						}

						echo '<td>'.date('F d, Y', $startTime).'</td>';
						//echo '<td><pre>'.$timeInfo[0].'</pre></td>';

					}



					//echo '<h5>'.$webinar->topic.' - ID: '.$webinar->id.'</h5>';
					//echo '<p>'.$webinar->agenda.'</p>';


					echo '</tr>';

					//echo '<p>'.$startDate.'</p>';
					//echo '<hr>';
				}


			}


			?>
				</tbody>
			</table>
			<?php










		}
		//start_time


		echo '<hr>';


		echo '<hr><hr>';

		
		?>

		<script>

			//resent to GTT

			//sendGTT
			jQuery('body').on('click', '.sendGTT', function(e) {

				var resendID = jQuery(this).attr('data-orderid');

				var resendData = {
					'action': 'resend_to_gtt',
					'orderid': resendID
				};

				console.log(resendData);

			});

			jQuery('body').on('click', '.savegtwToken', function(e) {

				let gtwsavecode = jQuery('#gtwCode').val();

				console.log(gtwsavecode);

				if (gtwsavecode !== '') {

					var gtwData = {
						'action': 'gtw_save_token',
						'gtwsavecode': gtwsavecode
					};

					console.log(gtwData);

					jQuery.post("/wp-admin/admin-ajax.php", gtwData, function(gtatokenResponse) {

						console.log(gtatokenResponse);

						jQuery('#notice').html('<p><strong>GoToWebinar ('+gtatokenResponse+') token has been saved.</strong></p><p>You are free to refresh or leave this page.</p>');

					});

				} else {
					alert('Please fill in token in the text field.');
				}


			    e.stopPropagation();

			});

			jQuery('body').on('click', '.saveToken', function(e) {

				let responseCode = jQuery('#gttCode').val();

				if (responseCode !== '') {

					var tokenData = {
						'action': 'gtt_save_token',
						'responseCode': responseCode
					};

					console.log(tokenData);

					jQuery.post("/wp-admin/admin-ajax.php", tokenData, function(tokenResponse) {

						console.log(tokenResponse);

						jQuery('#notice').html('<p><strong>Token has been saved.</strong></p><p>You are free to refresh or leave this page.</p>');

					});


				} else {
					alert('Please fill in token in the text field.');
				}


			    e.stopPropagation();

			});

			jQuery('body').on('click', '.unsetToken', function(e) {

				var tokenData = {
					'action': 'gtt_unset_token',
				};

				jQuery.post("/wp-admin/admin-ajax.php", tokenData, function(tokenResponse) {

					console.log(tokenResponse);

					location.reload();
	
				});

			});


		</script>

	</div>

<?php


function get_product_by_sku( $sku ) {

	global $wpdb;

	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	if ( $product_id ) return new WC_Product( $product_id );

	    return null;
	
	}



}


add_action('wp_ajax_gtt_save_token', 'gtt_save_token_callback');

function gtt_save_token_callback() {

	$authorization_code = $_POST['responseCode'];

	$gtt2 = new GTT2(array());

	$tokenResponse = $gtt2->gtt_get_token($authorization_code);

	$accessToken = $tokenResponse->access_token;
	$refreshToken = $tokenResponse->refresh_token;

	update_option('gtt_access_token', $accessToken);
	update_option('gtt_refresh_token', $refreshToken);
	update_option('gtt_token_info', maybe_serialize( $tokenResponse ));
	update_option('gtt_token_generated', time());

	echo $tokenResponse->access_token;

	die();

}


add_action('wp_ajax_gtt_unset_token', 'gtt_unset_token_callback');

function gtt_unset_token_callback() {

	update_option('gtt_access_token', '');
	update_option('gtt_refresh_token', '');

	die();

}




add_action('wp_ajax_gtw_save_token', 'gtw_save_token_callback');

function gtw_save_token_callback() {

	$gtwauthorization_code = $_POST['gtwsavecode'];

	$gtw = new GTW(array());

	$tokenResponse = $gtw->gtw_get_token($gtwauthorization_code);

	$accessToken = $tokenResponse->access_token;
	$refreshToken = $tokenResponse->refresh_token;

	update_option('gtw_access_token', $accessToken);
	update_option('gtw_refresh_token', $refreshToken);
	update_option('gtw_token_info', maybe_serialize( $tokenResponse ));
	update_option('gtw_token_generated', time());

	echo $tokenResponse->access_token;

	die();

}


add_action('wp_ajax_gtw_unset_token', 'gtw_unset_token_callback');

function gtw_unset_token_callback() {

	update_option('gtw_access_token', '');
	update_option('gtw_refresh_token', '');

	die();

}


