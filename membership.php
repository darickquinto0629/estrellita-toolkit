<?php



function silibas_compile_reg_codes($order_id, $altText = false) {

	global $woocommerce;

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderText = '';
	$codesText = '';

	$order_status  = $order->get_status();

	if ($order_status == 'processing' || $order_status == 'completed' || $order_status == 'invoiced') {

		if ($order_status == 'processing') {
			$processingText = '<p>Your order has been received and is now being processed. Your order details are shown below for your reference.</p>';
		} else {
			$processingText = '<p>Your order details are shown below for your reference.</p>';
		}

		if (!$altText) {

			//$codesText .= '<p>You can access your Student Portal and La Vereda de Profe Teacher Portal using the registration code(s) below. All codes are program specific, please ensure they are distributed to the correct teacher. Each code will unlock a one-year license for both the Estrellita Student Portal and La Vereda de Profe.</p>';

		}

		if ($altText) {
			//$codesText .= '<p>These codes will be sent via email for your records.</p>';
		}

		if (!$altText) {
			//$codesText .= '<p>&nbsp;</p><p>Register for the Student Portal: <a href="http://maestro.estrellita.com/Account/Register">http://maestro.estrellita.com/Account/Register</a></p>';
			//$codesText .= '<p>Register for La Vereda de Profe: <a href="https://teachers.estrellita.com/">https://teachers.estrellita.com/</a></p><p>&nbsp;</p>';
		}


		$memberKeys = array();
		$items = $order->get_items();

		//$bundleIDs = array('64362', '64514');
		$bundleIDs = array('1');
		$membershipIDs = array('64344', '64342','64340','64338');
		$consumablesIDs = array('1892','1894');


		$hasBundle = false;
		$hasMembership = false;
		$hasConsumable = false;

		$itemtext = '';
		$xx = 1;

		foreach ( $items as $item ) {

	        $product_id = $item['product_id'];
	        

	        if (in_array($product_id, $bundleIDs)) {
	        	$hasBundle = true;
	        }
	        if (in_array($product_id, $membershipIDs)) {
	        	//$hasMembership = true;
	        }
	        if (in_array($product_id, $consumablesIDs)) {
	        	$hasConsumable = true;
	        }

	        if ($order_status == 'completed' || $order_status == 'invoiced') {

	        	/* steve said don't send user codes till completed or invoiced 09-2021 */

		        if (in_array($product_id, $membershipIDs) || in_array($product_id, $bundleIDs)) {


		        	$itemQty = $item['quantity'];

		        	if ($product_id == 64338) {
		        		if (!$altText) {
		        			//$codesText .= '<p><strong>Lunita Codes ('.$itemQty.'): </strong></p>';
		        		}
		        		$codeType = 'si-membercodes-lunita';
		        	} elseif ($product_id == 64340) {

		        		if (!$altText) {
			        		//$codesText .= '<p><strong>K-1 Codes ('.$itemQty.'): </strong></p>';
			        	}

		        		$codeType = 'si-membercodes-k1';
		       		// } elseif ($product_id == 64362) {
		        	// 	//package
		        	// 	if (!$altText) {
		        	// 		$codesText .= '<p><strong>K-1 Codes ('.$itemQty.'): </strong></p>';
		        	// 	}
		        	// 	$codeType = 'si-membercodes-k1';
		        	} elseif ($product_id == 64342) {
				        $itemtext .= '- '.$xx.' prek: '.$product_id;

		        		if (!$altText) {
		        			//$codesText .= '<p><strong>Pre-K Codes ('.$itemQty.'): </strong></p>';
		        		}
		        		$codeType = 'si-membercodes-prek';
		        	// } elseif ($product_id == 64514) {
		        	// 	//package 
		        	// 	if (!$altText) {
		        	// 		$codesText .= '<p><strong>Pre-K Codes ('.$itemQty.'): </strong></p>';
		        	// 	}
		        	// 	$codeType = 'si-membercodes-prek';
		        	} elseif ($product_id == 64344) {
		        		if (!$altText) {
		        			//$codesText .= '<p><strong>Escalera Codes ('.$itemQty.'): </strong></p>';
		        		}
		        		$codeType = 'si-membercodes-escalera';
		        	} 


		        	$saveCodes = array();
		        	
		        	if (get_post_meta($order_id, 'codeText', true) == '') {

						for ($i = 0; $i < $itemQty; $i++){

							$thisCode = silibas_get_new_member_code($codeType);

							$saveCodes[] = $thisCode;

							$codesText .= $thisCode.'<br class="break">';

							
						}

						$memberStr = implode(',', $saveCodes);	

						add_post_meta($order_id, 'membershipcodes', $memberStr, false);
					}
					

		        }

		    }
		    
		    $xx++;

		}

		if (!$hasBundle && !$hasMembership && !$hasConsumable) {
			//
		} else {
			update_post_meta($order_id, 'codeText', $codesText);
		}

	}



	if ($hasMembership) {
		$orderText = $processingText . $codesText . '';
		update_post_meta($order_id, 'test_msg2', 'membership');

	}

	if ($hasBundle) {
		$orderText = $processingText . $codesText .'';
		update_post_meta($order_id, 'test_msg2', 'bundle');

	}

	if ($hasConsumable) {
		$orderText = $processingText . '';
		update_post_meta($order_id, 'test_msg2', 'consumable' . $processingText);
	}

	if (get_post_meta($order_id, 'codeText', true) != '') {
		$orderText = $processingText . '<br>' . get_post_meta($order_id, 'codeText', true);

		if (get_post_meta($order_id, 'membershipcodes', true) != '') {
			$orderText = $orderText .= get_post_meta($order_id, 'membershipcodes', true).'<br>';

		}

	}

	if (!$hasBundle && !$hasMembership && !$hasConsumable) {
		return '';
	} else {
		if ($hasConsumable) {
			$orderText = $processingText . '';
		} else {
			update_post_meta($order_id, 'test_msg', time());

			return $orderText . '';
		}
	}
	

}



function silibas_member_product_send_text($regCodeText, $isPage = false) {


	// $add_content .= 'Your order has been processed and your new Membership Codes are as follows:</p>';
				
	// $add_content .= $regCodeText;
	
	// if ($isPage) {
	// 	$add_content .= '<p style="color:#f26127;margin-bottom:20px;">This same code will also be sent to you in an email for your records.</p>';
	// }
	
	// $add_content .= '<h3 style="margin-bottom:40px;"><strong><a href="https://teachers.estrellita.com/" style="text-decoration:underline;color:#f26127;">Log into the teacher portal here</a></strong></h3>';

	// $add_content .= '<style>';
	// $add_content .= 'pre { font-size:16px;   display: block;    padding: 8px 10px 0 10px;    margin: 0 0 10px;    color: #333;    border: 1px solid #ccc;    border-radius: 4px;}';
	// $add_content .= '</style>';
	
	return '';


}

function silibas_has_member_product($order_id) {


	$hasMemberProduct = false;


	//$memberProducts = array('64344', '64342','64340','64338','64362','64514');
	$memberProducts = array('64344', '64342','64340','64338');
//	$memberProducts = array('64344', '64342','64340','64338', '1892', '1894', '64362', '64514');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];

        if (in_array($product_id, $memberProducts)) {

            $hasMemberProduct = true;

        }

	}

	return $hasMemberProduct;

}


function silibas_has_consumable_product($order_id) {


	$hasConsumable = false;

	$consumablesIDs = array('1892','1894',);

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];

        if (in_array($product_id, $consumablesIDs)) {

            $hasConsumable = true;

        }

	}

	return $hasConsumable;

}




function silibas_get_new_member_code($codeType) {

	global $options;

	$siteOptions = get_option('estrellita_options');

	$currentKeys = $siteOptions[$codeType];

	$regArray = explode(',', $currentKeys);

	$count_b4 = count($regArray);

	$regCode = $regArray[0];

	unset($regArray[0]);

	$count_after = count($regArray);

	$listOffset = $count_b4 - $count_after;

	if ($listOffset == 1) {

		$siteOptions[$codeType] = implode(',', $regArray);

		update_option( 'estrellita_options', $siteOptions );

	} else {
		
		$regCode = '';

	}

	return $regCode;

}
