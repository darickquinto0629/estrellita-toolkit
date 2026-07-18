<?php

add_action('wp_footer', 'silibas_shortcode_add_cart_script');

function silibas_shortcode_add_cart_script() {
	?>
	<script>
	
		function addToCart(p_id, qty) {
			jQuery.ajax({
				type: 'POST',
				url: '/?post_type=product&add-to-cart='+p_id,
				data: { 'product_id':  p_id, 'quantity': qty},
				success: function(response, textStatus, jqXHR){
	                    //location.reload(true);
	                    console.log('success');
				}
			}); 
	         
	     }


	     //displayVarient
	     function displayVarient(parent_id, var_id) {

	     	jQuery('.epid-'+parent_id+' .selectDate').hide();
	     	
	     	console.log('.epid-'+parent_id+' .selectDate');

	     	jQuery('#addCart-'+var_id).show();

	     	if(jQuery("#wrapCustomQuote-" + var_id).length != 0) {
		     	jQuery('#wrapCustomQuote-'+var_id).show();
		     	console.log('show quote button');
		     }

	     }

	</script>
	<?php
}



/* custom SCRM form add-on */

add_action('woocommerce_before_checkout_form', 'silibas_custom_checkout_field');

add_action('woocommerce_checkout_update_order_meta', 'silibas_form_submission_update_order_meta');



/* checkout options per product */

function silibas_custom_checkout_field( $checkout ) {
 
	global $woocommerce, $wpdb;

	$showRadio = false;
	$showTextFields = false;

	$items = $woocommerce->cart->get_cart();
	$itemQty = 0;

	$hasForm = false;
	$showForm = false;

	$formPostIDs = array();
	$formData = array();

	?><!-- checkout check --> <?php

	// Loop through ordered items
	foreach ($items as $item) {
		$product_name = $item['name'];
		$product_id = $item['product_id'];
		$product_qty = $item['quantity'];
		$product_variation_id = $item['variation_id'];

		// Check if product has variation.
		if ($product_variation_id) { 
			$product = new WC_Product_Variation($item['variation_id']);
			$productName = '';
		} else {
			$product = new WC_Product($item['product_id']);
		}
		
		// Get SKU
		$sku = $product->get_sku();
		$pro = $product->get_title();
		$proID = $product->get_id();

		$isNoForm = isNoFormProduct($proID);

		if ($sku > 54474 || $product_id != '12714') {
			?> <!-- check form product --> <?php

			echo '<!-- proid '.$proID.'-->';
				if ($sku > 5444474) {
					$isNoForm = true;
				}
				if ($isNoForm) {
				?>
				<!-- no form product -->
				<?php

				if (!$product->get_virtual()) {


				} else {

					if ($proID != 2184) {
					?> 				<!-- virtual product --> <?php
						$showForm = true;
						
						$formSet = array();

						$formSet['formid'] = '6361';
						$formSet['qty'] = $product_qty;
						$formSet['productID'] = $product_variation_id;
						$formSet['productName'] = $pro . ' '. get_post_meta($proID, 'attribute_date', true);

						$formData[]= $formSet;
						
					}
				}

			}

		}


	}



	if ($showForm) {

		//sku contains a training ID
		?>
		<div style="clear:both;"></div>

		<style>
		#content {position: relative; z-index: 100;}
		.woocommerce-info {display: none;}
		.checkout {display: none;}
		.form-group {clear:both;}
		#scrmCheckoutForm {font-size: 18px; padding:10px 40px;}
		#form_submission_field {display: none; width:100%;}
		</style>

		<?php 

		echo silibas_form_checkout($formData);			

	} else {
		?>
		<style>
			#form_submission_field {display: none; width:100%;}
		</style>
		<?php
	}
}


function isNoFormProduct($product_id) {

	$isNoFormProduct = array(7081, 5621, 5619, 5617, 5610, 2622, 2612, 2624, 2618, 2620, 600, 2593, 2184, 64375, 79351, 79361);


	if (in_array($product_id, $pdProducts)) {
		return true;
	} else {
		return false;
	}

}
//woocommerce_after_order_notes

add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );


function my_custom_checkout_field( $checkout ) {
	
	woocommerce_form_field( 
		'form_submission', 
			array(
				 'type'			=> 'textarea',
				 'class'		 => 'form_submitted',
				 'label'		 => __(''),
				 'required'	=> false,
			), 
		$checkout->get_value( 'form_submission' )
	);

}

function silibas_product_get_form_id($product_id) {
	
	$formData = maybe_unserialize(get_post_meta($product_id, 'forms', true));
	
	return $formData[0];

}


function silibas_product_get_form_ids($product_id) {
	
	$formData = get_post_meta($product_id, 'silibas_form_id', true);

	if ($formData != '') {
		return $formData;
	}

}

function silibas_form_submission_update_order_meta($order_id) {

	$trainingIDs = array();

	if ($_POST['form_submission']) {

		update_post_meta( $order_id, 'form_response', maybe_serialize($_POST['form_submission']));

		$formData = get_post_meta($order_id, 'form_response', true);


	}

}

function is_zoom_training($idcheck) {

	if (strlen($idcheck) > 11) {
		return false;
	} else {
		return true;
	}

}


function silibas_send_error($subject, $message = '') {

	$mailRecipients = array();
	$mailRecipients[] = 'joe@searlecreative.com';

	$headers = 'From: Estrellita <info@estrellita.com>' . "\r\n";
	$headers .= 'Content-type: text/html';

	@wp_mail( $mailRecipients, $subject, $message, $headers );

	return true;
}


function silibas_get_training_info($trainingID) {

	$gtt2 = new GTT2(array());

	$currentToken = $gtt2->getToken();

	$singleTraining = $gtt2->getTraining($currentToken, $trainingID);

	return $singleTraining;

}


function silibas_add_training_user($trainingID, $userInfo) {

	return false;

}



function silibas_add_training_notifications($trainingID, $userInfo, $joinUrl, $confirmationUrl, $registrantKey) {

	global $woocommerce, $options, $post;

	$siteOptions = get_option('estrellita_options');

	$sendUser = $userInfo['email'];
	$attendeeName = $userInfo['first_name'];

	//create notifications on success

	$currentTime = time();

	$trainingInfo = silibas_get_training_info($trainingID);
	$trainingInfo = json_decode(json_encode($trainingInfo), true);

	$trainingName = $trainingInfo['name'];

	$startTimeStamp = strtotime($trainingInfo['times'][0]['startDate']);
	$endTimeStamp = strtotime($trainingInfo['times'][0]['endDate']);
	
	$startTimeHuman =  date_i18n( 'l, F, jS, Y | g:i A', $startTimeStamp );
	$endTimeHuman = date_i18n( 'g:i A', $endTimeStamp );

	$tz = date_default_timezone_set(get_option('timezone_string'));
	$dstime = date('T', $startTimeStamp);

	$humanTime = get_option('gtt_key_'.$trainingID);

	$firstNotice = strtotime('-7 day', $startTimeStamp);
	$secondNotice = strtotime('-1 day', $startTimeStamp);

	$startsDiff = $startTimeStamp - $currentTime;
	$startsIn = floor($startsDiff / (60 * 60 * 24));

	$icsfile = '';

	$trainingReport = $humanTime.'<br>';
	$trainingReport .= $firstNotice.'<br>';
	$trainingReport .= $secondNotice.'<br>';


	//get the headlines
	$noticeHeadline = $siteOptions['notification-headline'];
	$registerHeadline = $siteOptions['registration-headline'];


	//put the content together
	$notificationContent = $siteOptions['notification-content'];
	$initialNotificationContent = $siteOptions['registration-content'];

	$searchFor = array(
			'[attendee]',
			'[trainingname]',
			'[joinlink]',
			'[callink]',
			'[datetime]'
		);
	$replaceWith = array(
			$attendeeName,
			$trainingName,
			$joinUrl,
			$icsfile,
			$humanTime
		);

	$notificationContent1 = str_replace($searchFor, $replaceWith, $notificationContent);
	$notificationContent1 = str_replace('[starttime]', '7 Days', $notificationContent1);

	$notificationContent2 = str_replace($searchFor, $replaceWith, $notificationContent);
	$notificationContent2 = str_replace('[starttime]', '1 Day', $notificationContent2);

	$notificationContent3 = str_replace($searchFor, $replaceWith, $initialNotificationContent);
	$notificationContent3 = str_replace('[starttime]', $startsIn .' Days', $notificationContent3);

	if ($trainingName == '') {
		$trainingName = 'Online Training';
	}

	// make the notification posts. 
	$noticeArgs1 = array(
			'post_title'	=> $trainingName,
			'post_name'	=> sanitize_title($trainingName),
			'post_content'	=> $notificationContent1,
			'post_status'	=> 'publish',
			'post_type'	=> 'gtt_notifications',
		);
	$noticeArgs2 = array(
			'post_title'	=> $trainingName,
			'post_name'	=> sanitize_title($trainingName),
			'post_content'	=> $notificationContent2,
			'post_status'	=> 'publish',
			'post_type'	=> 'gtt_notifications',
		);

	$noticeID1 = wp_insert_post($noticeArgs1);
	$noticeID2 = wp_insert_post($noticeArgs2);

	$noticeID3 = wp_insert_post(
		array(
			'post_title'	=> $trainingName,
			'post_name'	=> sanitize_title($trainingName),
			'post_content'	=> $notificationContent3,
			'post_status'	=> 'publish',
			'post_type'	=> 'gtt_notifications',
		)
	);

	//add the meta to our notification posts

	$noticeMeta = array('gtt_sent' => 'false', 'gtt_joinlink' => $joinUrl, 'gtt_trainingID' => $trainingID, 'gtt_confirmationkey' => $confirmationUrl, 'gtt_send_to' => $sendUser);

	foreach($noticeMeta as $key => $val) {
		update_post_meta($noticeID1, $key, $val);
		update_post_meta($noticeID2, $key, $val);
		update_post_meta($noticeID3, $key, $val);
	}
	
	//add send times
	update_post_meta($noticeID1, 'gtt_send_date', $firstNotice);
	update_post_meta($noticeID2, 'gtt_send_date', $secondNotice);

	//addheadlines

	update_post_meta($noticeID1, 'gtt_headline', $noticeHeadline);
	update_post_meta($noticeID2, 'gtt_headline', $noticeHeadline);
	update_post_meta($noticeID3, 'gtt_headline', $registerHeadline);


	//send initial email notice
	update_post_meta($noticeID3, 'gtt_send_date', '0');
	silibas_send_notification($noticeID3, 'true');

	update_post_meta($noticeID3, 'gtt_training_response', maybe_serialize($trainingInfo));

	return $trainingReport;

}

function silibas_set_notification_content($dynamicData) {

	global $options;

	$siteOptions = get_option('estrellita_options');

	$notificationContent = $siteOptions['notification-content'];

}


add_action( 'woocommerce_thankyou', 'action_woocommerce_thankyou', 10, 1 ); 

function action_woocommerce_thankyou( $order_id ) { 

	global $options, $post, $wpdb;

	$siteOptions = get_option('estrellita_options');

	$formData = get_post_meta($order_id, 'form_response', true);

	$trainingID = get_post_meta($order_id, 'trainingID', true);

	// if (get_post_meta($order_id, '_billing_options', true) != '') {
	// 	update_post_meta($order_id, 'apEmail', get_post_meta($order_id, '_billing_options', true));
	// }

	if ($formData != '') {

	?>

<header><h2>Attendee Details</h2></header>

<table class="shop_table customer_details">
	
	<thead>
		<tr>
			<th style="text-align:left;">First Name</th>
			<th style="text-align:left;">Last Name</th>
			<th style="text-align:left;">Email</th>
			<th style="text-align:left;">School</th>
			<th style="text-align:left;">District</th>
			<th style="text-align:left;">Training</th>
		</tr>
	</thead>
	<tbody>
		<?php

		echo silibas_format_attendees($order_id, 'table', 'page');
		

		?>
	</tbody>

</table>

	<?php
	}
}; 
		 

add_action( 'woocommerce_email_after_order_table', 'silibas_add_new_order_message', 10, 2 );

function silibas_add_new_order_message($order) {

	global $woocommerce, $options, $post, $wpdb;

	$order_id = $order->id;

	$orderID = get_the_ID();

	$formData = get_post_meta($order_id, 'form_response', true);
	
	$emailMessage = '';

	if ($formData != '') {

		$emailMessage .= '<h2 style="color: #f26127; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 26px 0 8px; text-align: left;">';
		$emailMessage .= 'Attendees:</h2>';

		$emailMessage .=  silibas_format_attendees($order_id, 'emailformat', 'email');
		
	}

	echo $emailMessage;
	
}


function silibas_format_attendees($order_id, $formatType, $outputType) {

	global $woocommerce, $post;

	$formData = get_post_meta($order_id, 'form_response', true);

	$formData = json_decode($formData, true);


	if ($formData != '') {
	
		$attendeeData = array_chunk($formData, 6, true);


		if ($formatType == 'table') {

			$attendeeHtml .= '<tr>';
			foreach($attendeeData as $attendee) {
				$attendeeHtml .=  '<tr>';
				foreach ($attendee as $att => $val) {

					if ($outputType == 'page') {
						if ($att == 'product_id') { 
							$attendeeHtml .=  '<td>'.get_post_meta($val, 'attribute_date', true).'</td>';
						} else {
							$attendeeHtml .=  '<td>'.$val.'</td>';
						}
					} else {
						if ($att == 'product_id') { 
							$attendeeHtml .=  '<td class="td" style="text-align: left; vertical-align: middle; font-family: "Helvetica Neue", Helvetica,
	Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.get_post_meta($val, 'attribute_date', true).'</td>';
						} else {
							$attendeeHtml .=  '<td class="td" style="text-align: left; vertical-align: middle;font-family: "Helvetica Neue", Helvetica,
	Roboto, Arial, sans-serif; word-wrap: break-word; color: #737373; padding: 12px;">'.$val.'</td>';
						}
					}

				}
				$attendeeHtml .=  '</tr>';
			}
			$attendeeHtml .= '</tr>';
		} elseif ($formatType == 'emailformat') {
			$a = 1;
			foreach($attendeeData as $attendee) {
				$attendeeHtml .=  '<p style="margin-bottom:15px;">';
				$formattedUser = '';

				foreach ($attendee as $att => $value) {

					if ($att == 'First Name') {
						$formattedUser .= '<strong>'.$value . ' ';
					} elseif ($att == 'Last Name') {
						$formattedUser .= ' '.$value.'</strong>';
					} elseif ($att == 'Email') {
						$formattedUser .= '<br>'.$value;
					} elseif ($att == 'school-name') {
						$formattedUser .= '<br>'.$value;
					} elseif ($att == 'school-district') {
						$formattedUser .= ' - '.$value;
					} elseif ($att == 'product_id') {
						$formattedUser .= '<br><strong>Training Date:</strong> '. get_post_meta($value, 'attribute_date', true);						
					}

				}
				$attendeeHtml .= $formattedUser;
				$attendeeHtml .=  '</p>';
				$a++;
			}



		} elseif ($formatType == 'list') {

			if (count($attendeeData) > 1) {
				foreach($attendeeData as $attendee) {
					$attendeeHtml .=  '<ul>';
					foreach ($attendee as $att => $value) {
						$attendeeHtml .=  '<li>'.$value.'</li>';
					}
					$attendeeHtml .=  '</ul>';
				}
			} else {
				$attendeeHtml .=  '<ul>';
				$attendeeHtml .=  '<li>'.$value.'</li>';
				$attendeeHtml .=  '</ul>';
			}

		} else {
			//what to do without formatting?

		}

	}

	return $attendeeHtml;

}


function silibas_product_has_form($productID) {

	global $post;

	$fID = get_post_meta($productID, 'silibas_form_id', true);

	if ($fID != '') {
		return true;
	} else {
		return false;
	}

}

function silibas_load_validator() {

	wp_register_script('validator', plugins_url('/estrellita-toolkit/js/validator.min.js'), array('jquery'), '1.1', true);
	wp_enqueue_script('validator');

}

add_action ('wp_enqueue_scripts', 'silibas_load_validator');


function silibas_form_checkout($formData) {

	global $post;



	$postContent = '';

	$postContent .= '<div id="scrmRequired" class="requireform">';
	$postContent .= '<form id="scrm-form-required" action="" method="post" data-toggle="validator" class="scrmform_checkout">';
	$postContent .= '<h3>Your purchase requires the following attendees to be completed before checkout.</h3>';

	foreach($formData as $formInfo) {

		$formID = $formInfo['formid'];

		$qty = $formInfo['qty'];
			
		$productID = $formInfo['productID'];
		$productName = $formInfo['productName'];
		
		$post = get_post($formID);
		$fID = get_post_meta($formID, 'silibas_form_id', true);

		$itemQ = 1;

		while ($itemQ <= $qty) {
			
			$postContent .= '<h4 class="signup-header">Attendee #'.$itemQ.' for '.$productName.'</h4>';
			$postContent .= $post->post_content;
			$postContent .= '<input id="product_'.$itemQ.'" class="form-control" name="product_id" type="text" value="'.$productID.'" style="display:none" required />';
			
			$itemQ++;

		}

	}

	$postContent .= '<div class="form-group">';
		$postContent .= '<div>';
		$postContent .= '<p><em>Please fill out all fields above before proceeding.</em></p>';
		 $postContent .= '<button id="scrmCheckoutForm" class="btn btn-primary " name="submit" type="submit" />';
			$postContent .= 'Next';
		 $postContent .= '</button>';
		$postContent .= '</div>';
	$postContent .= '</div>';

	$postContent .= '</form>';
	$postContent .= '</div>';


		$postContent .= "\n";

		$postContent .= "<script>"."\n";

		$postContent .= "jQuery('#scrm-form-required').on('submit', function (e) {"."\n";
			
			$postContent .= "e.preventDefault();"."\n";
		

			$postContent .= "if (jQuery(this).hasClass('disabled')) {"."\n";
			//$postContent .= "alert('forms test');"."\n";




			

		$postContent .= "				jQuery('html, body').animate({"."\n";
		$postContent .= "					scrollTop: jQuery('.fl-page-content').offset().top"."\n";
		$postContent .= "				}, 2000);"."\n";
		$postContent .= "				"."\n";

		$postContent .= "				var serialdata = jQuery( '#scrm-form-required').serializeArray();"."\n";
		$postContent .= "				var holdData = JSON.stringify(serialdata);"."\n";
		$postContent .= "				"."\n";

			$postContent .= "			console.log(holdData);"."\n";

		$postContent .= "				jQuery('#form_submission').val(holdData)"."\n";

		$postContent .= "				jQuery('#scrm-form-required').slideUp();"."\n";
		$postContent .= "				jQuery('.checkout').fadeIn();"."\n";
		$postContent .= "				return false; "."\n";
		$postContent .= "				e.stopPropagation();"."\n";
		$postContent .= "				e.preventDefault();"."\n";

		$postContent .= "		} else {"."\n";
			

		$postContent .= "		};"."\n";




		
		//$postContent .= "		};"."\n";
		$postContent .= "		});"."\n";

		$postContent .= "</script>"."\n";

		$postContent .= "<style>"."\n";
		$postContent .= ".signup-header {margin:30px 0 10px 0;color:#f26127;}"."\n";
		$postContent .= "</style>"."\n";

	return $postContent;

}


function silibas_has_product($productID) {

	global $woocommerce;

	$hasProduct = false;

	foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {

		$_product = $values['data'];

		if( $_product->id == $productID ) {
			$hasProduct = true;
		}

	 }

	 return $hasProduct;

}

/**
cron notifications 
**/


add_action('silibas_cron_notifications','silibas_cron_notifications_action');

function silibas_cron_notifications_action() {

	global $post, $woocommerce;

	$currentTime = time();

	$queryTime = strtotime('-1 day', $currentTime);
	
	$currentDateF = date('Ymd', $currentTime);

	$cleanupCutoff = date('-30 days', $currentTime);

	$idsToSend = array();

	$notificationQuery = new WP_Query( 
		array(
			'post_type' => 'gtt_notifications', 
			'orderby' => 'meta_value',   
			'meta_key' => 'gtt_send_date', 
			'order' => 'ASC',	
			'posts_per_page' => '-1',	
			'meta_query' => array( 
				array(
					'key' => 'gtt_send_date', 
					'value' => date("Y-m-d"), 
					'compare' => '>=',
					'type' => 'DATE'
					)
				)
			)
		);


	if ( $notificationQuery->have_posts() ) {
	
		while ( $notificationQuery->have_posts() ) : $notificationQuery->the_post(); 
			
			$postID = $notificationQuery->post->ID;



		endwhile;
		
		wp_reset_postdata();
	
	}


}


function silibas_create_ics($location, $startTime, $endTime, $description, $eventName) {

	//datetime format: 20170804T223000

	$startTime = str_replace(array('-', ':', 'Z'), array('', '', ''), $startTime);
	$endTime = str_replace(array('-', ':', 'Z'), array('', '', ''), $endTime);

	$icsData .= 'BEGIN:VCALENDAR'."\n";
	$icsData .= 'VERSION:1.0'."\n";
	$icsData .= 'BEGIN:VEVENT'."\n";
	$icsData .= 'DTSTART:'.$startTime.''."\n";
	$icsData .= 'DTEND:'.$endTime.''."\n";
	$icsData .= 'LOCATION:'.$location.''."\n";
	$icsData .= 'DESCRIPTION:'.$description.''."\n";
	$icsData .= 'SUMMARY:'.$eventName."\n";
	$icsData .= 'PRIORITY:3'."\n";
	$icsData .= 'END:VEVENT'."\n";
	$icsData .= 'END:VCALENDAR'."\n";


}



add_action('wp_footer', 'checkout_next_confirm');


function checkout_next_confirm() {

	if (is_page(254)) {
		?>
		<script>
jQuery( document ).ready(function() {

jQuery('#order_comments_field').hide();
jQuery('#scrmCheckoutForm').on('click', function (e) {
				console.log('test');
				e.preventDefault();

				var passCheck = true;

			jQuery('.req').each(function(){

			console.log(jQuery(this).val() + 'field check ');
			if (jQuery(this).val() == '') {
			
			passCheck = false;
			}
			});

			if (passCheck) {


				jQuery('html, body').animate({
					scrollTop: jQuery('.fl-page-content').offset().top
				}, 2000);
				
				var serialdata = jQuery( '#scrm-form-required').serializeArray();
				//var holdData = JSON.stringify(serialdata);
				var holdData = buildRequestStringData(jQuery('#scrm-form-required'));

				console.log(holdData);
				jQuery('#form_submission').val(holdData);
				jQuery('#order_comments').val(holdData);
				jQuery('#scrm-form-required').slideUp();
				jQuery('.checkout').fadeIn();
				jQuery('#quote_comments_field').hide();

				return false; 
				e.stopPropagation();
				e.preventDefault();
			} else {
				alert('Please fill out all attendee fields before proceeding');
			}
		});
		});




function buildRequestStringData(form) {
    var select = form.find('select'),
        input = form.find('input'),
        requestString = '{';
    for (var i = 0; i < select.length; i++) {
        requestString += '"' + jQuery(select[i]).attr('name') + '": "' +jQuery(select[i]).val() + '",';
    }
    if (select.length > 0) {
        requestString = requestString.substring(0, requestString.length - 1);
    }
    for (var i = 0; i < input.length; i++) {
        if (jQuery(input[i]).attr('type') !== 'checkbox') {
            requestString += '"' + jQuery(input[i]).attr('name') + '":"' + jQuery(input[i]).val() + '",';
        } else {
            if (jQuery(input[i]).attr('checked')) {
                requestString += '"' + jQuery(input[i]).attr('name') +'":"' + jQuery(input[i]).val() +'",';
            }
        }
    }
    if (input.length > 0) {
        requestString = requestString.substring(0, requestString.length - 1);
    }
    requestString += '}';
    return requestString;
}

		</script>

		<?php
	}
}
