<?php

//get next reg code & reset options

function silibas_get_new_reg_code() {

	global $options;

	$siteOptions = get_option('estrellita_options');

	$currentKeys = $siteOptions['si-regcodes'];

	$regArray = explode(',', $currentKeys);

	$count_b4 = count($regArray);

	$regCode = $regArray[0];

	unset($regArray[0]);

	$count_after = count($regArray);

	$listOffset = $count_b4 - $count_after;

	if ($listOffset == 1) {

		$siteOptions['si-regcodes'] = implode(',', $regArray);

		update_option( 'estrellita_options', $siteOptions );

	} else {
		
		$regCode = '';

	}

	return $regCode;

}

//attach registration code to order


function silibas_tax_collection() {
	// global $woocommerce, $post, $wpdb;

	// //collect items from current Quote
	// $current_quote = $woocommerce->session->quote_contents;

	// $orderInfo = silibas_package_quote_items($current_quote, true);

	// $country = 'US';
	// $city = 'Los Angeles';
	// $postcode = '90016';
	// $state = 'CA';
	
	// $customerData = array();

	// $customer = new WC_Customer();
	// $customer->set_shipping_postcode($postcode);
	// $customer->set_shipping_city($city);
	// $customer->set_shipping_state($state);
	// $customer->set_shipping_country($country);

	// //[avatax_totals]
	// foreach($woocommerce->session->avatax_totals as $tax_line) {
	// 	$returnData.= $tax_line['total'];
	// }

	// return $returnData;

}

function remove_product_from_cart( $product_id ) {
 
    $prod_unique_id = WC()->cart->generate_cart_id( $product_id );

    unset( WC()->cart->cart_contents[$prod_unique_id] );

}


function silibas_clear_cart() {

	foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
		remove_product_from_cart( $_product->id );
	}

}

function silibas_load_cart($orderInfo) {

	//populate cart + qty of quote items
	foreach($orderInfo as $orderSet) {
		silibas_add_product_to_cart($orderSet['id'], $orderSet['qty']);
	}
}

function silibas_package_quote_items($order, $isQuote) {

	global $woocommerce, $wpdb;

	$orderInfo = array();

	if ($isQuote) {
		foreach ($order as $quote_item) {
			$quoteSet = array();

			$quoteSet['id'] = $quote_item['product_id'];
			$quoteSet['qty'] = $quote_item['quantity'];

			$orderInfo[] = $quoteSet;

		}
	} else {
		$order_item = $order->get_items();

        foreach( $order_item as $product ) {

        	$quoteSet = array();

            $quoteSet['id'] = $product['product_id']; 
            $quoteSet['qty'] = $product['qty'];

            $orderInfo[] = $quoteSet;
        }		

	}

	return $orderInfo;
	
}


add_filter( 'query_vars', 'silibas_quote_query_var', 10, 1 );

function silibas_quote_query_var($vars) {
	$vars[] = 'quote';
	return $vars;
}

add_filter( 'woocommerce_email_actions', 'silibas_email_filter_actions' );
function silibas_email_filter_actions( $actions ){
    $actions[] = "woocommerce_order_status_request_to_quote-sent";
    return $actions;
}

// function silibas_expedited_order_woocommerce_email( $email_classes ) {
 
//     require( 'includes/class-wc-quote-order-email.php' );
 
//     $email_classes['WC_Quote_Order_Email'] = new WC_Quote_Order_Email();
 
//     return $email_classes;
 

// }
//add_filter( 'woocommerce_email_classes', 'silibas_expedited_order_woocommerce_email' );


add_action('the_post', 'silibas_quote_cart_report');

function silibas_quote_cart_report() {
	global $post;

	if (is_page('5684')) {

		$quoteID = $_GET['quote'];


		if ($quoteID != '') {

			add_action('the_content', 'silibas_echo_cart');

		} else {

			return $content;

		}
	}

}



function silibas_echo_cart() {

	global $post, $woocommerce;

	$quoteID = $_GET['quote'];

	$quoteTable = '<style>header .fl-post-title, .uil-default-css {display:none;}</style>';
	$order = new WC_Order($quoteID);
	$quoteTable .= silibas_get_quote_table($quoteID, $order);

	return $quoteTable;

}

add_action( 'woocommerce_payment_complete', 'silibas_quote_checkout_complete' );

add_action('wp_footer', 'silibas_quote_checkout_complete');

function silibas_quote_checkout_complete() {

	global $wpdb, $post, $woocommerce;

	$userInfo = wp_get_current_user();
	$userID = $userInfo->ID;

	if (is_page('5170')) {
		//quote list page

		if ($userID == 0) {

			// $country = 'US';
			// $city = 'Los Angeles';
			// $postcode = '90016';
			// $state = 'CA';
			
			// $customerData = array();

			// $customer = new WC_Customer();
			// $customer->set_shipping_postcode($postcode);
			// $customer->set_shipping_city($city);
			// $customer->set_shipping_state($state);
			// $customer->set_shipping_country($country);

		}


	} elseif (is_page('5684')) {
		/*
		quote sent page template
		*/

		$pending = new WP_Query(
		    array(
		        'post_type' => 'shop_order',
		        'posts_per_page' => 1,
		        'order' => 'DESC',
		        'post_status' => 'wc-proposal-sent'
		    )
		);

		$quoteID = $_GET['quote'];

		$taxStates = array('CA', 'WA', 'AZ');	

		if ($pending->post->ID != '') {

			$order_id = $pending->post->ID;

			$shippingInfo = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_type = 'shipping'", 'ARRAY_A'));
			
			$shippingID = $shippingInfo[0]->order_item_id;
			
			$taxRate = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "woocommerce_order_itemmeta WHERE order_item_id = '".$shippingID."' AND meta_key = '_wc_avatax_rate'", 'ARRAY_A'));

			if ($taxRate == '') {
				
				$order = new WC_Order($order_id);
				$city = $order->shipping_city;
				$state = $order->shipping_state;
				$country = $order->shipping_country;
				$postcode = $order->shipping_postcode;

				echo '<style>header .fl-post-title {text-align:center;}</style>'."\r\n";
			
				if ($userID == '0') {

					estrillita_shipping_total_hack($order_id);

					//$testrates = WC_AvaTax_Order_Handler::calculate_order_tax($order);
					$jCode .= '<script>'."\r\n";
					$jCode .= 'console.log("load quote");'."\r\n";
					$jCode .= 'jQuery( document ).ready(function() {'."\r\n";
						if (get_post_meta($order_id, 'quote_created', true) != 'true' || !get_post_meta($order_id, 'quote_created', true)) {
					$jCode .= 'window.history.pushState("obj", "Quote Sent", "?quote='.$order_id.'");'."\r\n";
					$jCode .= 'console.log("load url");'."\r\n";
					$jCode .= 'location.reload();'."\r\n";
						}
					$jCode .= '});'."\r\n";
					$jCode .= '</script>'."\r\n";

					echo $jCode;
					
				} else {

					$orderTotal = $order->get_total();

					$orderItems = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_type = 'line_item'", 'ARRAY_A'));

					$ajaxData = '';
					$i = 1;

					foreach($orderItems as $line_item) {
						if ($i == 1) {
							$ajaxData .= 'order_item_id[]='.$line_item->order_item_id;
						} else {
							$ajaxData .= '&order_item_id[]='.$line_item->order_item_id;
						}
						
						$ajaxData .= '&order_item_tax_class['.$line_item->order_item_id.']=';

						$itemQty = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "woocommerce_order_itemmeta WHERE order_item_id = '".$line_item->order_item_id."' AND meta_key = '_qty'", 'ARRAY_A'));
			    			$itemSubtotal = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "woocommerce_order_itemmeta WHERE order_item_id = '".$line_item->order_item_id."' AND meta_key = '_line_subtotal'", 'ARRAY_A'));
						
						$ajaxData .= '&order_item_qty['.$line_item->order_item_id.']='.$itemQty;
						
						$ajaxData .= '&refund_order_item_qty['.$line_item->order_item_id.']=';
						$ajaxData .= '&line_subtotal['.$line_item->order_item_id.']='.$itemSubtotal;
						$ajaxData .= '&line_total['.$line_item->order_item_id.']='.$itemSubtotal;
						$ajaxData .= '&refund_line_total['.$line_item->order_item_id.']=';
						$i++;
					}

					//include shipping data

					$shippingInfo = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_type = 'shipping'", 'ARRAY_A'));
					
					$shipping_items = $order->get_items( 'shipping' );

					$shippingID = $shippingInfo[0]->order_item_id;
					$shippingName = $shippingInfo[0]->order_item_name;
					
					$shipCost = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "woocommerce_order_itemmeta WHERE order_item_id = '".$shippingID."' AND meta_key = 'cost'", 'ARRAY_A'));
					$shippingType = $wpdb->get_var($wpdb->prepare("SELECT meta_value  FROM " . $wpdb->prefix . "woocommerce_order_itemmeta WHERE order_item_id = '".$shippingID."' AND meta_key = 'method_id'", 'ARRAY_A'));

					$ajaxData .= '&shipping_method_id[]='.$shippingID;
					$ajaxData .= '&shipping_method_title['.$shippingID.']='.str_replace(' ', '+', $shippingName);
					$ajaxData .= '&shipping_method['.$shippingID.']='.str_replace(':', '%3A', $shippingType);
					$ajaxData .= '&shipping_cost['.$shippingID.']='.$shipCost;
					$ajaxData .= '&refund_line_total['.$shippingID.']=';

					$ajaxData .= '&_order_total='.$orderTotal;

					$ajaxData = str_replace('[', '%5B', $ajaxData);
			     	$ajaxData = str_replace(']', '%5D', $ajaxData);

					estrillita_shipping_total_hack($order_id);

					$jCode .= '<script>'."\r\n";
					$jCode .= 'console.log("load quote");'."\r\n";
					$jCode .= 'jQuery( document ).ready(function() {'."\r\n";
						if (get_post_meta($order_id, 'quote_created', true) != 'true' || !get_post_meta($order_id, 'quote_created', true)) {
					$jCode .= 'window.history.pushState("obj", "Quote Sent", "?quote='.$order_id.'");'."\r\n";
					$jCode .= 'console.log("load url");'."\r\n";
					$jCode .= 'location.reload();'."\r\n";
						}
					$jCode .= '});'."\r\n";
					$jCode .= '</script>'."\r\n";

					echo $jCode;

				}

				echo '<script>console.log("post update");</script>'."\r\n";

			} else {

				echo '<style>header .fl-post-title {text-align:center;}</style>';
				
			}

			
		}
		update_post_meta($order_id, 'demo-1', 'pre run');

		//trip over and send the email
		silibas_quote_run($order_id);

		//$order->update_status('quote-sent', 'before translate: ');

		update_post_meta($order_id, 'demo-2', 'end');

		//$order->update_status('quote-sent', 'after translate: ');



		// if (get_post_status($order_id) == 'wc-quote-sent') {
		// 	$order->update_status('proposal-sent', 'quote to proposal ');
		// }
		
	}
	//add custom counter

	if (is_page()) {

		?>
<script>

</script>


		<?php
	}

	
}


function sc_get_the_excerpt($post_id) {
  global $post;  
  $save_post = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt();
  $post = $save_post;
  return $output;
}

function silibas_set_gtt($order_id) {

	$trainingIDs = array();

	$formData = wpb_protected_excerpt($order_id);

	$formData = json_decode($formData);

	update_post_meta($order_id, 'form_response', maybe_serialize($formData));

	return $formData;
}

add_action( 'woocommerce_email_before_order_table', 'silibas_add_content_specific_email', 20, 4 );
  
function silibas_add_content_specific_email( $order, $sent_to_admin, $plain_text ) {
	//pd messages for completed emails
	$siteOptions = get_option('estrellita_options');

   if ( $email->id == 'customer_completed_order' ) {

	   	$msg = '';
	   	$pdMsg = false;

		$pdNational = silibas_national_pd_products();
		$pdRemote = silibas_remote_pd_products();
		$pdOnsite = silibas_onsite_pd_products();
		$pdInteractive = silibas_interactive_pd_products();

		//$customerItems = [];
		$items = $order->get_items();

		foreach ( $items as $item ) {
	        // $customerItems[] = $item['product_id'];

			if (in_array($item['product_id'], $pdNational)) {
   				$hasPD = true;
			}
			if (in_array($item['product_id'], $pdRemote)) {
   				$hasRemote = true;
			}
			if (in_array($item['product_id'], $pdOnsite)) {
   				$hasOnsite = true;
			}
			if (in_array($item['product_id'], $pdInteractive)) {
   				$hasInteractive = true;
			}

		}

		if ($hasPD) {
			$msg .= $siteOptions['national-content'].'<br>';
		}
		if ($hasRemote) {
			$msg .= $siteOptions['remote-content'].'<br>';
		}
		if ($hasOnsite) {
			$msg .= $siteOptions['onsite-content'].'<br>';
		}
		if ($hasInteractive) {
			$msg .= $siteOptions['district-content'].'<br>';
		}

   		if ($hasPD || $hasRemote || $hasOnsite || $hasInteractive) {
    		echo $msg;
   		}

   }

}



add_action('woocommerce_payment_complete', 'estrillita_shipping_total_hack', 10, 1);

function estrillita_shipping_total_hack($order_id) {

	$order = new WC_Order( $order_id );

	$orderTotal = $order->get_total();

	if ($order->get_total_shipping() == 0.00) {

		$shippingTotal = 0;

		foreach ($order->get_items('shipping') as $item_id => $item_data) {

			$shippingMethod = $item_data->get_method_title();

			$shippingTotal = $item_data->get_total();
			
			if (strpos($shippingMethod, 'FedEx') !== false) {

				update_post_meta($order_id, '_order_shipping', round($shippingTotal, 2));
				update_post_meta($order_id, '_order_total', round($shippingTotal, 2) + $orderTotal);

			}
		
		}

	}

}



function silibas_add_product_to_cart($product_id, $product_qty) {
	
	global $woocommerce;

	WC()->cart->add_to_cart( $product_id, $product_qty);
	
}

//add_action('pre_get_posts', 'silibas_empty_cart_check');

function silibas_empty_cart_check() {
	global $woocommerce;

	if ( ! is_admin() ) {
		if (!is_page('254')) {
			//if ( WC()->cart->get_cart_contents_count() == 0 ) {
			//	WC()->cart->add_to_cart( '5788' );
			//}
		}
	}
}



//add_action( 'pre_get_posts', 'silibas_remove_product_from_cart' );

function silibas_remove_product_from_cart() {
    // Run only in the Cart or Checkout Page
	global $post, $woocommerce;

	if( is_checkout()) {

        $prod_to_remove = 5788;

        foreach( WC()->cart->cart_contents as $prod_in_cart ) {

            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];

            if( $prod_to_remove == $prod_id ) {
                $prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
                unset( WC()->cart->cart_contents[$prod_unique_id] );
            }
        }
    }
}


add_action('wp_ajax_nopriv_woocommerce_calc_line_taxes', 'woocommerce_calc_line_taxes');

add_action('wp_ajax_quote_notifications', 'silibas_quote_notifications_callback');
add_action('wp_ajax_nopriv_quote_notifications', 'silibas_quote_notifications_callback');

function silibas_quote_notifications_callback() {

	global $woocommerce, $wpdb;

	$order_id = $_POST['orderID'];

	if (get_post_meta($order_id, 'quote_run', true) != 'true') {

		$order = new WC_Order($order_id);

		$order->update_status('request', 'quote notifications callback: ');

		//sleep(1);

		$order->update_status('quote-sent', 'callback 2: ');

		$wpdb->query("DELETE FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_name = 'Quote Request'");

		update_post_meta($order_id, 'quote_run', 'true');

	}

	die();

}

function silibas_quote_run($order_id) {

	global $woocommerce, $wpdb;

	if (get_post_meta($order_id, 'quote_run', true) != 'true') {

		$order = new WC_Order($order_id);

		$order->update_status('request', 'quote run: ', true);

		sleep(1);

		$wpdb->query("DELETE FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_name = 'Quote Request'");

		update_post_meta($order_id, 'quote_run', 'true');
		
		$order->update_status('quote-sent', 'quote run 2: ', true);


	    if (get_post_meta($order_id, 'quote_sent', true) != 'true') {
			// replace variables in the subject/headings
   			
		    $customerEmail = get_post_meta($order_id, '_billing_email', true);

			update_post_meta($order_id, 'quote_sent', 'true');

			update_post_meta($order_id, 'delivery_test', 'true');
			update_post_meta($order_id, 'email to:',  $customerEmail);
		    
		    $mailer	= WC()->mailer();

			WC()->mailer()->emails['Cust_Woo_Email']->trigger( $order_id );

			update_post_meta($order_id, 'quote_sented', 'alsotrue');

	    }
	    

	}

	return true;

	//die();
}

function silibas_create_quote_post($order_id) {

	global $wpdb;

	$quotePostID = $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_name = 'q".$order_id."' AND post_type = 'customerquote'", 'ARRAY_A'));
	
	if ($quotePostID == '') {

		$quoteArgs = array(
			'post_title' => 'q'.$order_id,
			'post_name' => 'q'.$order_id,
			'post_type' => 'customerquote',
			'post_status' => 'publish'
			);
		$quotePostID = wp_insert_post($quoteArgs);

		update_post_meta($quotePostID, 'quoteid', $order_id);

		return $quotePostID;
	} else {
		return $quotePostID;
	}

}

function wpb_protected_excerpt( $post_id ) {
$post = get_post($post_id);
$excerpt=$post->post_excerpt;
return $excerpt;
}
add_filter( 'the_excerpt', 'wpb_protected_excerpt' );


function silibas_get_quote_table($order_id, $order) {
	global $woocommerce, $post, $wpdb;

	//remove empty quote request product from order
	$order = new WC_Order($order_id);

	if (get_post_meta($order_id, '_billing_options', true) != '' && get_post_meta($order_id, 'apEmail', true) == '') {
		update_post_meta($order_id, 'apEmail', get_post_meta($order_id, '_billing_options', true));
	}

	$pdfLink = 'https://estrellita.com/wp-content/uploads/2017/07/K1_Student_Portal_Instruction_Guide_Web.pdf';

	$quoteTable = '<div class="quoteMessage">';

	$quoteTable .= '<h1 class="fl-post-title">Quote: '.get_post_meta($order_id, '_order_number_formatted', true).'</h1>';
	$quoteTable .= '<p>Thank you! A copy of this quote has been sent to you via email. ';


	$formData = wpb_protected_excerpt($order_id);
	update_post_meta($order_id, 'form_response', $formData);

	//$quoteTable .= $formData;


	$quoteTable .= 'To place your order online you can click the Complete Purchase in the email that was sent to you. ';
	$quoteTable .= 'If you prefer, you can send it to <a href="mailto:info@estrellita.com">info@estrellita.com</a>. ';
	$quoteTable .= 'If you have any questions regarding your estimate or our products, please call (303) 779-2610 or email us. Thank you for your interest in Estrellita! ';
	$quoteTable .= 'We look forward to working with you.</p>';
	
	$quoteTable .= '<p><a href="https://estrellita.com/customerquote/q'.$order_id.'/pdf/" download class="pdfbutton">Download Quote PDF</a></p>';

	$quoteTable .= '</div>';
	$quoteTable .= '<table class="shop_table shop_table_responsive cart" cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">';
	
	$quoteTable .= '<thead>';
	$quoteTable .= '<tr>';
	$quoteTable .= '<th scope="col" style="text-align:left; border: 1px solid #eee;">Product</th>';
	$quoteTable .= '<th scope="col" style="text-align:left; border: 1px solid #eee;">Quantity</th>';
	$quoteTable .= '<th scope="col" style="text-align:left; border: 1px solid #eee;">Price</th>';
	$quoteTable .= '</tr>';
	$quoteTable .= '</thead>';
	$quoteTable .= '<tbody>';

	$omitItems = array();

	if ($order_id != '5879993') {
		$items = $order->get_items();
		foreach ($items as $item) {

			//check and load bundles to omit if applicable
			$itemMeta = $item->get_meta('_stamp');

			// foreach($itemMeta as $item_meta) {
			// 	if (!in_array($item_meta['product_id'], $omitItems)) {
			// 		$omitItems[] = $item_meta['product_id'];
			// 	}
			// }

			if ($item['subtotal'] != '0' ) {

				if (!in_array($item['product_id'], $omitItems)) {
					$quoteTable .= '<tr>';

					$quoteTable .= '<td class="col">'.$item['name'].'</td>';
					$quoteTable .= '<td class="col">'.$item['qty'].'</td>';
					$quoteTable .= '<td class="col">$'.number_format($item['line_total'], 2,'.', ',').'</td>';

					$quoteTable .= '</tr>';		
				}
			}
		}
	} else {
		 // $quoteTable .= $order->email_order_items_table( array( 'show_sku'    => false, 	'show_image'  => false, 	'$image_size' => array( 32, 32 ), 	'plain_text'  => false ) );
	}

	
	$quoteTable .= '</tbody>';
	$quoteTable .= '<tfoot>';

	if ( $totals = $order->get_order_item_totals() ) {

		$i = 0;
		foreach ( $totals as $total ) {
			$i++;
					
			$quoteTable .= '<tr>';
			if ($i == 1) {
				$quoteTable .= '<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;border-top-width: 4px;">'.$total["label"].'</th>';
				$quoteTable .= '<td style="text-align:left; border: 1px solid #eee; border-top-width: 4px;">'.$total["value"].'</td>';
			} else {
				$quoteTable .= '<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;">'.$total["label"].'</th>';
				$quoteTable .= '<td style="text-align:left; border: 1px solid #eee; ">'.$total["value"].'</td>';
			}

			$quoteTable .= '</tr>';
		}
	}
	$quoteTable .= '</tfoot>';
	$quoteTable .= '</table>';

	$quoteTable .= '<style>table th, table td {padding:10px;} table {margin:20px;auto;}</style>';

	if (get_post_meta($order_id, 'quote_created', true) != 'true') {
		update_post_meta($order_id, 'quote_created', 'true');
	}

	$quotePostID = silibas_create_quote_post($order_id);

	return $quoteTable;

}

function register_archive_order_status() {
    register_post_status( 'wc-archive-order', array(
        'label'                     => 'Archive',
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => false,
        'show_in_admin_status_list' => false,
        'label_count'               => _n_noop( 'Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_archive_order_status' );


function register_quote_sent_order_status() {
    register_post_status( 'wc-quote-sent', array(
        'label'                     => 'Quote Sent',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Quote Sent <span class="count">(%s)</span>', 'Quote Sent <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_quote_sent_order_status' );


// Add to list of WC Order statuses
function add_quote_sent_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-quote-sent'] = 'Quote Sent';
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_quote_sent_to_order_statuses' );

function is_silibas() {
	$userIP = $_SERVER['REMOTE_ADDR'];

	if ($userIP == '69.75.79.214' || $userIP == '45.48.70.212') {
		return true;
	} else {
		return false;
	}
}

function silibas_bundle_quote_row($cart_item, $cart_item_key) {

	global $woocommerce, $post, $product;

	$bundleRow = '';

	$qty = $cart_item['quantity'];

	$bundlePrice = 0;


	if (wc_pb_is_bundle_container_cart_item($cart_item)) {
		$cartIDs[] = $cart_item['product_id'];
		foreach($cart_item['stamp'] as $product_bundled) {
			$price = get_post_meta($product_bundled['product_id'], '_price', true);
			$bundlePrice = $bundlePrice + $price;
			$cartIDs[] = $product_bundled['product_id'];
		}
	} else {
		$bundlePrice = get_post_meta($cart_item['product_id'], '_price', true);
		$cartIDs[] = $cart_item['product_id'];
	}

	$bundlePrice = $bundlePrice * $qty;

	if (wc_pb_is_bundle_container_cart_item($cart_item)) {

		$bundleRow .= '<tr class="adq_list cart_item bundle_table_item">';

		$bundleRow .= '<td class="product-remove">';

		$cartKeys = array();
		$cartKeys[] = $cart_item_key;

		foreach($cart_item['bundled_items'] as $bundleKey) {
			$cartKeys[] = $bundleKey;
		}

		$bundleRow .= '<a href="#" class="remove remove_quote_items" data-cart_item_keys="'.implode('-',$cartKeys).'" data-product_ids="'.implode('-',$cartIDs).'" title="Remove this item">×</a>';
	
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-thumbnail">';
		$bundleRow .= '&nbsp;';
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-name">';
		$bundleRow .= get_the_title($cart_item['product_id']);

		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-price">';
		$bundleRow .= '$'.number_format($bundlePrice, 2, '.', ',');
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-quantity">';
		$bundleRow .= $qty;
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-subtotal">';
		$bundleRow .= '$'.number_format($bundlePrice, 2, '.', ',');
		$bundleRow .= '</td>';

		$bundleRow .= '</tr>';

	} else {

		$bundleRow .= '<tr class="adq_list cart_item bundle_table_item">';

		$bundleRow .= '<td class="product-remove bundle_item">';
		$bundleRow .= '<a style="display:none" href="#" class="remove remove_quote_item" data-cart_item_key="'.$cart_item_key.'" data-product_id="'.$cart_item['product_id'].'" title="Remove this item">×</a>';
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-thumbnail bundle_item">';
		$bundleRow .= '&nbsp;';
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-name bundle_item">';
			$bundleRow .= '<div class="bundled_table_item_indent">';
			$bundleRow .= '<span class="subproduct">'.get_the_title($cart_item['product_id']).'</span>';
			$bundleRow .= '</div>';

		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-price bundle_item">';
		$bundleRow .= '<span class="subproduct">$'.number_format($bundlePrice, 2, '.', ',').'</span>';
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-quantity bundle_item">';
		$bundleRow .= '&nbsp;';
		$bundleRow .= '</td>';

		$bundleRow .= '<td class="product-subtotal bundle_item">';
		$bundleRow .= '<span class="subproduct subprice">Subtotal: $'.number_format($bundlePrice, 2, '.', ',').'</span>';
		$bundleRow .= '</td>';

		$bundleRow .= '</tr>';
	}

	$bundleRow .= '<style>.subproduct {margin-left:10px;font-size:85%;}.bundle_item {border:0!important;}.subprice {margin-left:0!important;}</style>';
	
	add_action('wp_footer', 'remove_bundled_products_script');

	return $bundleRow;

}


add_filter( 'default_checkout_state', 'change_default_checkout_state' , 10, 2);
function change_default_checkout_state($state, $status) {
  return 'CA'; 
}



function update_quote_sent_log($logMsg) {

	$quotesentLogs = get_option('quote_log');

	update_option('quote_log', $quotesentLogs . '|'.$logMsg);

}


function remove_bundled_products_script() {
?>
<!-- remove bundled products -->

<script>
	jQuery('body').on('click', '.remove_quote_items', function(e) {

		var cart_item_keys = jQuery(this).attr('data-cart_item_keys');
		var product_ids = jQuery(this).attr('data-product_ids');

		var data = {
			'action': 'remove_bundle_from_list',
			'cart_item_keys': cart_item_keys,
			'product_ids': product_ids
		};
				
		console.log(data);

		//jQuery('#goldresults_date').html('<p>Geting Custom Gold Member Data...</p>');
				
		jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
		 	var this_page = window.location.toString();                
               window.location = this_page;
               location.reload();
               return;
		});

		//

	});

   jQuery(document).on('click', '.remove_quote_items09090', function(){

     removeItems ( jQuery(this), true );
            
     return false;
   });
	function removeItems (item, redirects) {
		console.log('removal'); 


            jQuery.post(
                adqAjax.ajaxurl, 
                {
                    'action': 'remove_bundle_from_list',
                    'cart_item_keys' : item.data('cart_item_keys'),
                    'product_ids' : item.data('product_ids')
                }, 
                function(response){
                	console.log('response');
                    if(redirects) {
                        var this_page = window.location.toString();                
                        console.log('redirect');
                        window.location = this_page;
                        return;
                    }
                },
                'json'
            );


            console.log('removal end');  
    }
</script>
<?php

}




add_action('wp_ajax_remove_bundle_from_list', 'remove_bundle_from_list_callback');
add_action('wp_ajax_nopriv_remove_bundle_from_list', 'remove_bundle_from_list_callback' );

function remove_bundle_from_list_callback() {
	global $post, $woocommerce, $product;

	$cart_item_keys = sanitize_key( $_POST['cart_item_keys'] );        
     $product_ids = sanitize_key( $_POST['product_ids'] ); 


     $cart_item_keys = explode('-', $cart_item_keys);
     $product_ids = explode('-', $product_ids);

     $parent_id = $product_ids[0];

     $i = 0;
     while ($i < count($cart_item_keys)) {
     	$response = WC_Adq()->quote->remove_quote_item( $cart_item_keys[$i] );
     	$i++;
     }

     //deliver messaging   

     if( $response ) {
          $message = sprintf( __( '&quot;%s&quot; was successfully removed from list.', 'woocommerce-quotation' ), get_the_title( $parent_id ) );
          adq_add_notice( $message );
     } else {
          $message = sprintf( __( '&quot;%s&quot; cannot be removed from list.', 'woocommerce-quotation' ), get_the_title( $parent_id ) );
          adq_add_notice( $message, 'error' ); 
     }

     echo json_encode(array("result" => (int)$response));

}




function silibas_has_portal($order_id) {

	global $woocommerce;

	$hasRegProduct = false;

	//$regisration_product = 2184;
	$bundleProducts = array('64514', '64362');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if (in_array($product_id, $bundleProducts)) {
            $hasRegProduct = true;
        }
	}

	return $hasRegProduct;

}


function silibas_has_student_portal($order_id) {

	$hasRegProduct = false;

	$memberProducts = array('64344', '64342','64340','64338', '64362', '64514');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if (in_array($product_id, $memberProducts)) {
            $hasRegProduct = true;
        }
	}

	return $hasRegProduct;

}


function silibas_has_portal_membership($order_id) {



	$hasRegProduct = false;

	//$regisration_product = '2184';
	$regisration_product = array('2184', '64514', '64362');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if (in_array($product_id, $regisration_product)) {
            $hasRegProduct = true;
        }
	}

	return $hasRegProduct;


}


function silibas_get_portal_membership_reg_qty($order_id) {

	$k1Package = 64362;
	$prekPackage = 64514;

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$prekQ = 0;
	$k1Q = 0;
	$lunitaQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if ($regisration_product == $k1Package) {
            $k1Q = $k1Q + $item['qty'];
        } elseif ($regisration_product == $prekPackage) {
            $prekQ = $prekQ + $item['qty'];

        }
	}

	return array('prek' => $prekQ, 'k1' => $k1Q);

}



function silibas_get_reg_qty($order_id) {

	//$regisration_product = '2184';
	$regisration_product = array('2184', '64514', '64362');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if (in_array($product_id, $regisration_product)) {
            $orderQ = $orderQ + $item['qty'];
        }
	}

	return $orderQ;

}

function silibas_get_reg_codes($orderQ) {

    	$regCodes = array();

    	$i = 0;
    	while ($i < $orderQ) {
    		
    		$regCode = silibas_get_new_reg_code();
		
    		$regCodes[] = $regCode;

    		$i++;
    	
	}

	return $regCodes;
}

function silibas_process_sp($order_id) {

	$pdfLink = 'https://estrellita.com/wp-content/uploads/2017/07/K1_Student_Portal_Instruction_Guide_Web.pdf';

	$add_content = '';

	$hasRegProduct = silibas_has_student_portal($order_id);

	$orderQ = silibas_get_reg_qty($order_id);

		if ($hasRegProduct) {

		    	$regCodes = silibas_get_reg_codes($orderQ);
			    	
		    	if (get_post_meta($order_id, 'regcode', true) == '' ) {

				$orderedRegCodes = implode(',', $regCodes);

			    	update_post_meta($order_id, 'regcode', $orderedRegCodes);

			}

			$regCodes = get_post_meta($order_id, 'regcode', true);

			if ($regCodes != '') {
			
				$add_content .= '<p style="color:#f26127;font-weight:bolder;">';

				if ($isPage) {
					$add_content .= 'Thank you. ';
				}

				$add_content .= 'Your order has been processed and your new Student Portal Code are as follows:</p>';
				
				$regArray = explode(',', $regCodes);
				$z = 1;

				$add_content .= '<pre>';

				foreach ( $regArray as $regCode ) : 

					if ($z > 1) {
						$add_content .= '<p>Code '.$z.': <strong>'.$regCode.'</strong></p>';
					} else {
						$add_content .= '<p>Code: <strong>'.$regCode.'</strong></p>';
					}
					
					$z++;

				endforeach;

				$add_content .= '</pre>';
				
				if ($isPage) {
					$add_content .= '<p style="color:#f26127;margin-bottom:20px;">This same code will also be sent to you in an email for your records after all items in your order have been processed.</p>';
				}
				
				$add_content .= '<h3 style="margin-bottom:40px;"><strong><a href="'.$pdfLink.'" style="text-decoration:underline;color:#f26127;">Download Student Portal Instruction Guide</a></strong></h3>';

				$add_content .= '<style>';
				$add_content .= 'pre { font-size:16px;   display: block;    padding: 8px 10px 0 10px;    margin: 0 0 10px;    color: #333;    border: 1px solid #ccc;    border-radius: 4px;}';
				$add_content .= '</style>';
				
			}

		}

    return $add_content;


}



function silibas_add_to_order($order_id, $isPage = false) {

	global $woocommerce, $wpdb, $post;

	$pdfLink = 'https://estrellita.com/wp-content/uploads/2017/07/K1_Student_Portal_Instruction_Guide_Web.pdf';

	$add_content = '';

	$hasRegProduct = silibas_has_student_portal($order_id);
	

	if ($hasRegProduct) {
	
		$orderQ = silibas_get_reg_qty($order_id);

	    $regCodes = silibas_get_reg_codes($orderQ);
		    	
	    if (get_post_meta($order_id, 'regcode', true) == '') {

			$orderedRegCodes = implode(',', $regCodes);

		    update_post_meta($order_id, 'regcode', $orderedRegCodes);

		}

		$regCodes = get_post_meta($order_id, 'regcode', true);

		if ($regCodes != '') {
		
			// $add_content .= '<p style="color:#f26127;font-weight:bolder;">';

			if ($isPage) {
				//$add_content .= 'Thank you. <br><p>Your order has been received and is now being processed. Your order details are shown below for your reference.</p><p>Your codes will emailed to you once your order has been completed.</p>';
				$add_content .= 'Thank you.';


			}

			$add_content .= silibas_compile_reg_codes($order_id, true);
		
		}

	}

	// $hasMemberProduct = silibas_has_member_product($order_id);

	// if ($hasMemberProduct) {
	// 	//if the user has a teacher portal membeship product, gather product IDs and matching codes to send.

	// 	$regCodeText = silibas_compile_reg_codes($order_id, true);

	// 	$add_content .= silibas_member_product_send_text($regCodeText, false);

	// 	update_post_meta($order_id, 'portal_text', $regCodeText);

	// 	update_post_meta($order_id, 'test', time());


	// }
	update_post_meta($order_id, 'portal_text', $add_content);
	
	return $add_content;

}






function register_approved_shipping_order_status() {
    register_post_status( 'wc-approved-shipping', array(
        'label'                     => 'Approved to Ship',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting shipment <span class="count">(%s)</span>', 'Awaiting shipment <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_approved_shipping_order_status' );

// Add to list of WC Order statuses
function add_approved_shipping_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-approved-shipping'] = 'Approved to Ship';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_approved_shipping_to_order_statuses' );



add_filter( 'wc_shipment_tracking_get_providers', 'custom_shipment_tracking' );

function custom_shipment_tracking( $providers ) {

    unset($providers['Australia']);
    unset($providers['Austria']);
    unset($providers['Brazil']);
    unset($providers['Belgium']);
    unset($providers['Canada']);
    unset($providers['Czech Republic']);
    unset($providers['Finland']);
    unset($providers['France']);
    unset($providers['Germany']);
    unset($providers['Ireland']);
    unset($providers['Italy']);
    unset($providers['India']);
    unset($providers['Netherlands']);
    unset($providers['Romania']);
    unset($providers['South African']);
    unset($providers['Sweden']);
    unset($providers['New Zealand']);
    unset($providers['United Kingdom']);
    unset($providers['United States']['OnTrac']);
    unset($providers['United States']['DHL US']);

    return $providers;
}







function silibas_reg_codes_above_woo_order_info( $order ) {

	global $post;

	$order_id = $order->id;

	$regCode = get_post_meta($order_id, 'regcode', true);

	if ($regCode != '') {

		//echo '<p>Your order has been processed and your new Student Portal Code is as follows:</p>';
		//echo '<p>Code(s): <pre>'.$regCode.'</pre></p>';

	}

	//add condition for membership portal codes


	// $hasRegProduct = silibas_has_student_portal($order_id);

	// if ($hasRegProduct) {
		
	// 	$spText = silibas_compile_reg_codes($order_id, true);

	// 	echo $spText;

	// }

	// $hasMemberProduct = silibas_has_member_product($order_id);

	// if ($hasMemberProduct) {
	// 	//if the user has a teacher portal membeship product, gather product IDs and matching codes to send.
	// 	$regCodeText = silibas_compile_reg_codes($order_id, false);

	// 	echo $regCodeText;

	// 	//echo silibas_member_product_send_text($regCodeText, false);

	// 	update_post_meta($order_id, 'portal_text', $spText);

	// 	update_post_meta($order_id, 'test', time());

	// }

}

add_action( 'woocommerce_email_before_order_table', 'silibas_reg_codes_above_woo_order_info', 10, 2 );


add_action('admin_footer', 'silibas_admin_styling');

function silibas_admin_styling() {
	?>
	<style type="text/css">
		.subsubsub .current {
			padding: 1px 12px 3px;
		    border: 2px solid #0096dd!important;
		    border-radius: 12px;
		    margin-left: 10px;
		    margin-right: 10px;
		}

	</style>
	<?php
}

function silibas_approved_shipping_status( $order_id ) {

    update_post_meta($order_id, 'approved_date_change', time());

}

add_action( 'woocommerce_order_status_approved-shipping', 'silibas_approved_shipping_status', 10, 1);



function silibas_completed_status( $order_id ) {

    update_post_meta($order_id, 'completed_date_change', time());

	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();

	if ( ! empty( $mails ) ) {
	    foreach ( $mails as $mail ) {
	        if ( $mail->id == 'customer_completed_order' ) {

			$trackingInfo = get_post_meta($order_id, 'ups_shipment_ids', true);

			if (get_post_meta($orderNumber, 'regcode', true) == '' && $trackingInfo == '') {
			} else {
				//$mail->trigger( $order_id );
			}

	        }

	     }
	}

	//send regional email notifications 

	$hasMaterialProduct = silibas_has_material_product($order_id);

	$hasMemberProduct = silibas_has_member_product($order_id);

	if ($hasMemberProduct) {
		//if the user has a teacher portal membeship product, gather product IDs and matching codes to send.

		$regCodeText = silibas_compile_reg_codes($order_id, false);

		//$spText = silibas_member_product_send_text($regCodeText, false);

		update_post_meta($order_id, 'portal_text', $regCodeText);
		update_post_meta($order_id, 'portal_text_alt', $regCodeText);

		update_post_meta($order_id, 'test', time());


	}

	if (estrellita_has_pd_product($order_id)) {

		$formResp = get_post_meta($order_id, 'form_response', true);

		if (get_post_meta($order_id, 'attendee_info_1', true) == '') {

			silbas_woocommerce_order_status_completed_gtw($order_id);

		}

	}

/*
	

*/


}

function silibas_has_material_product($order_id) {

	global $options;

	$hasMaterials = false;

	$materialProducts = array('64344', '64342','64340','64338');

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	$orderQ = 0;

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];

        if (in_array($product_id, $materialProducts)) {

            $hasMaterials = true;

        }

	}

	return $hasMaterials;


}

function is_rep_notify_product($order_id) {

	$hasRepProduct = false;

	$alertProducts = maybe_unserialize(get_option('regional_products'));

	$order = new WC_Order( $order_id );
	$items = $order->get_items();

	foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if (in_array($product_id, $alertProducts)) {
            $hasRepProduct = true;
        }
	}

	return $hasRepProduct;

}

function is_meta_rep_notify_product($post_id) {

	$hasRepProduct = false;

	$alertProducts = maybe_unserialize(get_option('regional_products'));

    if (in_array($post_id, $alertProducts)) {
        $hasRepProduct = true;
    }

	return $hasRepProduct;

}


function notify_rep_email($order_id) {

	$alertUsers = maybe_unserialize(get_option('regional_users'));

	$order = new WC_Order($order_id);

	if ($order->get_shipping_state() == '') {
		$stateMap = $order->get_billing_state();
	} else {
		$stateMap = $order->get_shipping_state();
	}

	if ($alertUsers[$stateMap] != '') {
		return $alertUsers[$stateMap];
	} else {
		return 'christina.ulrich@mailinator.com';
	}

}

function rep_central() {

	global $options, $wpdb;

	$alertProducts = maybe_unserialize(get_option('regional_products'));
	$alertUsers = maybe_unserialize(get_option('regional_users'));

	?>
	<div class="wrap">
		<h4>Regional Notifications</h4>
		<hr>
		<div id="saveResults"></div>
<div id="monthlyReports" style="float:left; width:50%;">
<?php 
	echo '2025 monthly reports: <hr>';

    $results = $wpdb->get_results("
        SELECT * FROM `wp_estrellita2017`.`wp_comments` WHERE (CONVERT(`comment_content` USING utf8) LIKE '%Email sent to rep%') AND `comment_date` > '2025-04-01' AND `comment_date` < '2025-04-31'
    ", ARRAY_A);

    $reported = array();

    foreach($results as $result) {



    	 //.echo '<p>'.$result['comment_date'].'</p>';

    	//if (date("Y-m-d", strtotime($result['comment_date'])) != '2023-07-28') {

    		//if (strtotime($result['comment_date']) < 1690895333) {
    		$comment = $result['comment_content'];
    		$commentparts = explode('rep: ', $comment);

    		echo 'Report sent: '.$commentparts[1] . ' date: ' . $result['comment_date'];
		    	echo '<pre>';
		    //print_r($result);
		    	echo '</pre><hr>';
		   // }

    	//}//

    }


?>
</div>
<div style="clear:both"></div>
		<form id="rep_track_products">


		<h4>Users</h4>

		<div class="userList">

		<?php $regions = get_regional_listing();

		foreach($regions as $region => $abbr) {

			$userEmail = $alertUsers[$abbr];

			echo '<p><input type="text" class="region" value="'.$userEmail.'" data-region="'.$abbr.'" style="min-width:400px;"/> '.$region.' ('.$abbr.')</p>';

		}

		?>
		</div>

				<p><strong>Products to track</strong></p>

			<div class="productList">
		<?php
		$args = array(
		        'post_type'      => 'product',
		        'posts_per_page' => -1,
		    );

		    $loop = new WP_Query( $args );

		    while ( $loop->have_posts() ) : $loop->the_post();

		    	if (in_array($loop->post->ID, $alertProducts)) {
		    		$isChecked = 'checked';
		    	} else {
		    		$isChecked = '';
		    	}

		        echo ' <input type="checkbox" id="product-'.$loop->post->ID.'" name="product" class="rep_product" data-productid="'.$loop->post->ID.'" '.$isChecked.'><a href="'.get_permalink().'">' .get_the_title().'</a><br>';
		    endwhile;

		    wp_reset_query();
		?>
		</div>

		<hr>
		<a href="#saveResults" class="saveRegionSettings button button-primary button-large">Save Settings</a>
		</form>

		<script>
			jQuery('body').on('click', '.saveRegionSettings', function(e) {

				let productTracking = [];
				let usersTracking = [];
				let umap = [];

				jQuery('.rep_product').each(function(i, obj) {

				   if (jQuery(this).prop('checked')) {
				   		productTracking.push(jQuery(this).attr('data-productid'));
				    }

				});

				jQuery('.region').each(function(i, obj) {

					if (isEmail(jQuery(this).val())) {
						usersTracking.push(jQuery(this).attr('data-region') + '-' + jQuery(this).val());
					} else {
						alert('check that all email addresses are valid');
					}
				    
				});
				
				umap.push(usersTracking);

				var repData = {
					'action': 'silibas_regional_save',
					'regional_products': productTracking,
					'regional_users': umap
				};

				console.log(repData);

				jQuery.post("/wp-admin/admin-ajax.php", repData, function(repResponse) {

					jQuery('#saveResults').html('<p><strong>Settings ('+repResponse+') have been saved.</strong></p><p>You are free to refresh or leave this page.</p>');

				});

			    e.stopPropagation();

			});

			function isEmail(email) {
			  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			  return regex.test(email);
			}
		</script>

	</div>

	<?php

}

add_action('wp_ajax_silibas_regional_save', 'silibas_regional_save_callback');

function silibas_regional_save_callback() {

	global $options;

	$products = $_POST['regional_products'];
	$users = $_POST['regional_users'];

	$userMap = array();

	foreach($users[0] as $user) {
			$userStr = explode('-', $user);
			$userMap[$userStr[0]] = $userStr[1];
	}

	update_option('regional_products', maybe_serialize($products));
	update_option('regional_users', maybe_serialize($userMap));

	echo '2 option tables';

	die();

}

function silibas_regional_user_check($order_id) {



}

function get_regional_listing() {

	$regions = array(
		'Alabama' => 'AL',
		'Alaska' => 'AK',
		'American Samoa' => 'AS',
		'Arizona' => 'AZ',
		'Arkansas' => 'AR',
		'California' => 'CA',
		'Colorado' => 'CO',
		'Connecticut' => 'CT',
		'Delaware' => 'DE',
		'District of Columbia' => 'DC',
		'Florida' => 'FL',
		'Georgia' => 'GA',
		'Guam' => 'GU',
		'Hawaii' => 'HI',
		'Idaho' => 'ID',
		'Illinois' => 'IL',
		'Indiana' => 'IN',
		'Iowa' => 'IA',
		'Kansas' => 'KS',
		'Kentucky' => 'KY',
		'Louisiana' => 'LA',
		'Maine' => 'ME',
		'Maryland' => 'MD',
		'Massachusetts' => 'MA',
		'Michigan' => 'MI',
		'Minnesota' => 'MN',
		'Mississippi' => 'MS',
		'Missouri' => 'MO',
		'Montana' => 'MT',
		'Nebraska' => 'NE',
		'Nevada' => 'NV',
		'New Hampshire' => 'NH',
		'New Jersey' => 'NJ',
		'New Mexico' => 'NM',
		'New York' => 'NY',
		'North Carolina' => 'NC',
		'North Dakota' => 'ND',
		'Northern Mariana Islands' => 'MP',
		'Ohio' => 'OH',
		'Oklahoma' => 'OK',
		'Oregon' => 'OR',
		'Pennsylvania' => 'PA',
		'Puerto Rico' => 'PR',
		'Rhode Island' => 'RI',
		'South Carolina' => 'SC',
		'South Dakota' => 'SD',
		'Tennessee' => 'TN',
		'Texas' => 'TX',
		'U.S. Virgin Islands' => 'VI',
		'Utah' => 'UT',
		'Vermont' => 'VT',
		'Virginia' => 'VA',
		'Virgin Islands' => 'VI',
		'Washington' => 'WA',
		'West Virginia' => 'WV',
		'Wisconsin' => 'WI',
		'Wyoming' => 'WY',
	);

	return $regions;

}




/* break */

add_action( 'woocommerce_order_status_completed', 'silibas_completed_status', 10, 1);

function silbas_woocommerce_order_status_completed_gtt( $order_id ) {
    
	global $woocommerce;

	if (get_post_meta($order_id, 'attendee_info_1', true) != '') {

		for ($i = 1; $i < 15; $i++){

			$attendeeInfo = get_post_meta($order_id, 'attendee_info_'.$i, true);

			if (json_decode($attendeeInfo) != '' && strlen($attendeeInfo) > 2) {

				$aInfo = json_decode($attendeeInfo, true);

				$_product = new WC_Product_Variation($aInfo['product_id']);
				$trainingID = $_product->get_sku();

				$userInfo = array();

				$userInfo['first_name'] = $aInfo['first_name'];
				$userInfo['last_name'] = $aInfo['last_name'];
				$userInfo['email'] = $aInfo['email'];

				$zoom = new ZOOM(array());

				$trainingResponse = $gtt2->registerUser($trainingID, $userInfo);
				$trainingResponseLog = json_decode(json_encode($trainingResponse), true);

				update_post_meta( $order_id, 'zoom_response'.$i, maybe_serialize( $trainingResponseLog ));			

			}

		}
		
	}

}


//add_action( 'woocommerce_order_status_completed', 'silbas_woocommerce_order_status_completed_gtt', 10, 1 );

function wc_new_order_column( $columns ) {
   
	$columns['modified_column'] = 'Last Modified';
	$columns['awaiting_column'] = 'Order Completed';
	$columns['school_district'] = 'District';
	$columns['school_column'] = 'School Name';
   
	return $columns;

}

add_filter( 'manage_edit-shop_order_columns', 'wc_new_order_column' );



add_action('pre_get_posts', 'custom_orderdate_orderby');
function custom_orderdate_orderby($query)
{
    if (!is_admin()) return;
    
    $orderby = $query->get('orderby');
    if ('modified_column' == $orderby) {
        //$query->set('meta_key', '_shipping_postcode');
        $query->set('orderby', 'modified');
    }
}

function filter_manage_edit_shop_order_sortable_columns( $sortable_columns ) {  

    return wp_parse_args( array( 'modified_column' => 'modified_column' ), $sortable_columns );

}

add_filter( 'manage_edit-shop_order_sortable_columns', 'filter_manage_edit_shop_order_sortable_columns', 10, 1 );





/**
 * Adds 'Profit' column header to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $columns
 * @return string[] $new_columns
 */
function sv_wc_cogs_add_order_profit_column_header( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'awaiting_date' === $column_name ) {
            $new_columns['awaiting_date'] = __( 'Awaiting Date', 'silibas' );
        } elseif ( 'school_district' === $column_name ) {
            $new_columns['school_district'] = __( 'District', 'silibas' );
        } elseif ( 'school_column' === $column_name ) {
            $new_columns['school_column'] = __( 'School', 'silibas' );
        }
    }

    return $new_columns;
}
add_filter( 'manage_edit-shop_order_columns', 'sv_wc_cogs_add_order_profit_column_header', 20 );


function silibas_helper_get_order_meta( $order, $key = '', $single = true, $context = 'edit' ) {

        // WooCommerce > 3.0
        if ( defined( 'WC_VERSION' ) && WC_VERSION && version_compare( WC_VERSION, '3.0', '>=' ) ) {

            $value = $order->get_meta( $key, $single, $context );

        } else {

            $order_id = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;
            $value    = get_post_meta( $order_id, $key, $single );

        }

        return $value;

}


function silibas_add_status_change_date_column_content( $column ) {

    global $post;

	$order = wc_get_order( $post->ID );

    if ( 'awaiting_column' === $column ) {
        
        //echo get_post_modified_time( 'M j, Y', false );
    	if (get_post_meta($post->ID, '_last_completed', true) != '') {
    		echo date('M j, Y', strtotime(get_post_meta($post->ID, '_last_completed', true)));
    	} else {
    		//if status completed, set meta to last modified
    		if (get_post_status($post->ID) == 'wc-completed') {
    			update_post_meta($post->ID, '_last_completed', get_post_modified_time( 'M j, Y', false ));
    			echo date('M j, Y', strtotime(get_post_meta($post->ID, '_last_completed', true)));
    		}
    	}

    } elseif ('modified_column' == $column) {
    	echo get_post_modified_time( 'M j, Y', false );
    } elseif ('school_district' == $column) {
    	$district_name = get_post_meta($post->ID, 'billing_school_district_name_id', true);

    	//echo get_post_meta($post->ID, '_billing_school_district', true).'<br>';
    	if ($district_name == '0' || $district_name == '' ) {
    		echo '<span class="name_error">'.get_post_meta($post->ID, 'billing_school_district_name', true) . '</span>';
    	} else {
    		echo '<span class="name_success">'.get_post_meta($post->ID, 'billing_school_district_name', true).'</span><br>';
    	}


    } elseif ('school_column' == $column) {
    	$school_name = get_post_meta($post->ID, 'billing_school_name_id', true);

    	if ($school_name == '0' || $school_name == '' ) {
    		echo '<span class="name_error">'.get_post_meta($post->ID, 'billing_school_name', true) . '</span>';
    	} else {
			echo '<span class="name_success">'.get_post_meta($post->ID, 'billing_school_name', true).'</span><br>';
    	}

    }

}

add_action( 'manage_shop_order_posts_custom_column', 'silibas_add_status_change_date_column_content' );


add_action('admin_footer', 'unhidefix');

function unhidefix() {

	?>
<script>
jQuery( document ).ready(function() {
   
   jQuery('body').on('click', '#woocommerce-product-data .wc-tabs li a ', function(e) {

   	var thisFocus = jQuery(this).attr('href');

   	jQuery(thisFocus).removeClass('hidden');
   	console.log('unhide');
	});
});
</script>

	<?php
}

//School District/School

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields)
 {
 $fields['billing']['billing_company']['placeholder'] = 'School District/School';
 $fields['billing']['billing_company']['label'] = 'School District/School';

 return $fields;
 }

add_filter( 'woocommerce_billing_fields', 'wc_unrequire_wc_phone_field');
function wc_unrequire_wc_phone_field( $fields ) {
$fields['billing_company']['required'] = false;
return $fields;
}

//19628


function tp_get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed' ) ){

    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

function tp_get_pd_orders( $product_id, $order_status = array( 'wc-completed' ) ){
	
    global $wpdb;

    $yearAgo = date('Y-m-d h:i:s', strtotime("-1 years"));

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_date_gmt > '".$yearAgo."'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

function tpss_custom_get_order_notes( $order_id ) {
    remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
    $comments = get_comments( array(
        'post_id' => $order_id,
        'orderby' => 'comment_ID',
        'order'   => 'DESC',
        'approve' => 'approve',
        'type'    => 'order_note',
    ) );
    $notes = wp_list_pluck( $comments, 'comment_content' );
    add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
    return $notes;
}

function get_order_by_num($order_number, $output = OBJECT) {
    global $wpdb;

	$post = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_value = '".$order_number."' AND meta_key = '_order_number_formatted'", 'ARRAY_A'));

        if ( $post )
            return $post;
//SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key` = '_order_number_formatted' AND `meta_value` = 'R23188'
    return null;
}


add_action('admin_menu', 'searle_query_page_temp');

function searle_query_page_temp() {
	add_management_page('Searle Development', 'Searle Dev Page', 'edit_theme_options', 'searle-development-temp', 'sc_dev_page_t');
}

function sc_dev_page_t() {
	global $wpdb;
?>
<div class="wrap">
<?php

	echo '<h1>Shipping vals</h1>';

	$orderArray = array("R31598", "R31566", "R31537", "R31537", "R31537", "R31537", "R31534", "R31524", "R31523", "R31454", "R31454", "R31454", "R31454", "R31454", "R31454", "R31413", "R31413", "R31358", "R31358", "R31325", "R31325", "R31325", "R31322", "R31276", "R31276", "R31276", "R31227", "R31227", "R31227", "R31227", "R31227", "R31196", "R31155");

	foreach($orderArray as $quoteNum) {

		$oid = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_value = '".$quoteNum."'", 'ARRAY_A'));

		// echo $oid.'- '.get_post_meta($oid, '_order_shipping', true).'<br>';
		$oidnum = $oid[0]->post_id;

		echo $quoteNum.'- '.$oidnum.'- '.get_post_meta($oidnum, '_order_shipping', true).'<br>';

		// echo '<pre>';
		// print_r($oid);
		// echo '</pre>';

	}
	

	echo '<h1>Query Run</h1>';

	echo '<p>start...</p>';

	// $args = array('status' => array('wc-completed'), 'limit' => 800, 'type' => 'shop_order');
	// $orders = wc_get_orders($args);

	// foreach ($orders as $order) {
	// 	// Access order details here
	// 	$order_id = $order->get_id();

	// 	if (get_post_meta($order_id, '_last_completed', true) == '') {


	// 		$date_fmt = 'Y-m-d';
	// 		$modified_time = get_post_modified_time( $date_fmt, null, $order_id );

	// 		echo '<p>Order: '.$order_id.' - '.$modified_time.'</p><hr>';

	// 		update_post_meta($order_id, '_last_completed', $modified_time);

	// 	}


	// }
	echo '<hr>end';


?>
</div>
<?php
}



add_action( 'woocommerce_email_after_order_table', 'silibas_email_after_order_table', 10, 4 );

function silibas_email_after_order_table( $order, $sent_to_admin, $plain_text ) { 

      //if has regcode

	$portalText = '';

	$order_id  = $order->get_id();

	$hasRegProduct = silibas_has_student_portal($order_id);

	if ($hasRegProduct) {

		$portalText = get_post_meta($order_id, 'portal_text', true);

		
	}

	//echo $portalText;

}

add_action( 'woocommerce_order_status_completed', 'silbas_woocommerce_order_status_completed_gtw', 10, 1 );


function silbas_woocommerce_order_status_completed_gtw( $order_id ) {
    
	global $woocommerce;

	if (get_post_meta($order_id, 'form_response', true) != '') {

		update_post_meta($order_id, 'zoom', time());

		$formResp = get_post_meta($order_id, 'form_response', true);

		if (get_post_meta($order_id, 'attendee_info_1', true) == '') {
			$formResp = str_replace(array('"{', '}"', '{', '}'), array('', '', '', ''), $formResp);
			$farr = explode(',', $formResp);
			$farr = array_chunk($farr, 6);
			$attendeeInfo = array();
			foreach($farr as $ff) {
				$pArr = array();
				foreach($ff as $fset) {
					$tmpArr = array();
					$fbits = explode(':', $fset);
					$tmpArr['name'] = str_replace('"', '', $fbits[0]);;
					$tmpArr['value'] = str_replace('"', '', $fbits[1]);;
					$pArr[] = $tmpArr;
				}
				$attendeeInfo[] = $pArr;
			}
			$i = 1;
			foreach($attendeeInfo as $ainfo) {
				$infoStr = array();
				foreach ($ainfo as $vBlock) {
					if ($vBlock['name'] == 'First Name') {
						$infoStr['first_name'] = $vBlock['value'];
					} elseif ($vBlock['name'] == 'Last Name') {
						$infoStr['last_name'] = $vBlock['value'];
					} elseif ($vBlock['name'] == 'Email') {
						$infoStr['email'] = $vBlock['value'];
					} elseif ($vBlock['name'] == 'product_id') {
						$infoStr['product_id'] = $vBlock['value'];
					} elseif ($vBlock['name'] == 'school-name') {
						$infoStr['schoolname'] = $vBlock['value'];
					}
				}

				$infoStr = json_encode($infoStr);
				update_post_meta($order_id, 'attendee_info_'.$i, $infoStr);
				$i++;

			}

		}

	}
	
	if (get_post_meta($order_id, 'attendee_info_1', true) != '') {

		for ($i = 1; $i < 25; $i++){

			$attendeeInfo = get_post_meta($order_id, 'attendee_info_'.$i, true);

			if (json_decode($attendeeInfo) != '' && strlen($attendeeInfo) > 2) {

				$aInfo = json_decode($attendeeInfo, true);

				$_product = new WC_Product_Variation($aInfo['product_id']);
				$trainingID = $_product->get_sku();

				update_post_meta($order_id, 'reg_id_'.$i, $trainingID);

				$userInfo = array();

				$userInfo['first_name'] = $aInfo['first_name'];
				$userInfo['last_name'] = $aInfo['last_name'];
				$userInfo['email'] = $aInfo['email'];

				if (strlen($trainingID) > 11) {

					update_post_meta($order_id, 'is_gtw_'.$i, $trainingID);

					$userInfo = array();

					$userInfo['firstName'] = $aInfo['first_name'];
					$userInfo['lastName'] = $aInfo['last_name'];
					$userInfo['email'] = $aInfo['email'];
					$userInfo['source'] = 'estrellita.com';
					$userInfo['organization'] = 'org';

					$gtw = new GTW(array());

					$trainingResponse = $gtw->gtw_register_user($trainingID, $userInfo);
					$trainingResponse = json_decode(json_encode($trainingResponse), True);

					update_post_meta( $order_id, 'gtw_response_'.$i, maybe_serialize( $trainingResponse ));
					update_post_meta( $order_id, 'gtw_webinar_id_'.$i, $trainingID );			

				} else {

					update_post_meta($order_id, 'is_zoom', true);

					$zoom = new ZOOM(array(''));

					$trainingResponse = $zoom->register_user($trainingID, $userInfo);
					$tempResp = $trainingResponse;

					$trainingResponse = json_encode($trainingResponse);

					update_post_meta( $order_id, 'zoom_response_'.$i, maybe_serialize( $trainingResponse ));
					update_post_meta( $order_id, 'zoom_webinar_id_'.$i, $trainingID );

				}
				//check for error in response
		        $json = get_post_meta($order_id, 'zoom_response_'.$i, true);
		        $array = json_decode($json, true);

		        if (isset($array['code'])) {
		            update_post_meta( $order_id, 'zoom_response_error_'.$i, $array['code']);
		            update_post_meta( $order_id, 'zoom_response_message_'.$i, $array['message']);

		   //          $mailRecipients = array();
					// $mailRecipients[] = 'email';
					// @wp_mail( $mailRecipients, 'Zoom Registration Error ('.$order_id.') ', $array['message'], $headers, $attachments );

		        }

			}

		}
		
	}

	$sendRep = false;

	if ($sendRep) {

		//check for rep notices
		if (is_rep_notify_product($order_id)) {

			$order = new WC_Order($order_id);
			$mailRep = notify_rep_email($order_id);
			$mailRecipients = array();
			$mailRecipients[] = $mailRep;

			$mailMessage = 'This is an internal confirmation of the order sent to the customer.<br><br>';
			$mailMessage .= 'Materials Order - #'.get_post_meta($order_id, '_order_number_formatted', true).' - (ID: '.$order_id.') <a href="'.get_site_url().'/wp-admin/post.php?post='.$order_id.'&action=edit&classic-editor">View '.get_the_title($order_id).'</a>.';

			$mailMessage .= silibas_create_rep_order_info($order);
			
			$order_note = 'Email sent to rep: '.$mailRep;
			$note = __($order_note);

			// Add the note
			$order->add_order_note( $note );

			$headers = 'From: '.$blogname.' <info@estrellita.com>' . "\r\n";
			$headers .= 'Content-type: text/html';

			@wp_mail( $mailRecipients, 'Materials Order Complete ', $mailMessage, $headers, $attachments );

		}

	}


}

function silibas_create_rep_order_info($order) {

	$repMsg = '';

	$repMsg .= '<p>Billing:</p>';
	$repMsg .= '<p>'.$order->get_formatted_billing_address().'</p>';
	$repMsg .= '<p>&nbsp;</p>';
	$repMsg .= '<p>Shipping: </p>';
	$repMsg .= '<p>'.$order->get_formatted_shipping_address().'</p>';
	$repMsg .= '<style>.woocommerce-Price-amount {display:block;clear:both;}</style>';

		$repMsg .= wc_get_email_order_items($order, array(
			'show_sku'      => false,
			'show_image'    => false,
			'image_size'    => array( 32, 32 ),
			'plain_text'    => false,
			'sent_to_admin' => false,
		));

	return $repMsg;

}


function siliabs_get_product_id_by_variation_sku($sku) {
    $args = array(
        'post_type'  => 'product_variation',
        'meta_query' => array(
            array(
                'key'   => '_sku',
                'value' => $sku,
            )
        )
    );
    // Get the posts for the sku
    $posts = get_posts( $args);
    if ($posts) {
        return $posts[0]->post_parent;
    } else {
        return false;
    }
}

add_action('admin_head', 'maillogcss');

function maillogcss() {
	?>
<style type="text/css">#wp-mail-logging-modal-content {max-width:725px!important;}</style>
	<?php
}




add_action('admin_menu', 'oe_management_function');

function oe_management_function() {
	add_management_page('Order Export Format', 'Order Export Format', 'edit_theme_options', 'export-formatter', 'member_management_page');
}

function member_management_page() {

	global $wpdb, $post, $options, $woocommerce;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');  
	?>
	
	<div class="wrap">

		<h2>Export Formatter</h2>

	<?php

	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
		update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
	endif;

	wp_enqueue_media();

	?>
	<h3>Bulk Registration</h3>
	<form method='post'>
		<div class='image-preview-wrapper'>
			<input style="width:100%;" type="text" id="csvfile" name="csvfile" value="<?php echo get_attached_file(get_option( 'media_selector_attachment_id' )) ; ?>" readonly>
			<input style="display:none;" type="text" id="csvID" name="csvID" value="<?php echo get_option( 'media_selector_attachment_id' ) ; ?>" readonly>
		</div>
		<hr>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Choose File' ); ?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
		<input type="button" id="validateFile" name="submit_image_selector" value="Import Alumni" class="button-secondary">
	</form>
	<hr>


	
	<?php


?>

<hr>
	<table id="responseTable" class="wp-list-table widefat fixed striped table-view-list">
		<thead>
		<tr>
		  <th>First Name</th>
		  <th>Last Name</th>
		  <th>Order Date</th>
		  <th>Invoice Number</th>
		  <th>Quote Number</th>
		  <th>PO Number</th>
		  <th>District Name</th>
		  <th>Date Invoiced</th>
		  <th>Shipping Address</th>
		  <th>Item</th>
		  <th>Price</th>
		  <th>Quantity</th>
		  <th>Terms</th>
		  <th>Sales Rep</th>
		  <th>Rate</th>
		  <th>Region</th>
		  <th>Amount</th>
		  <th>Shipping Total</th>
		</tr>
		</thead>
		<tbody>
			<?php 

			?>
		</tbody>
	</table>
	<hr>
	<div id="status"></div>
	<div id="registeredCount">Orders: <span id="processedUsers">0</span></div>
	<hr>
		<input id="exportCSV" type="submit" name="submit_image_selector" value="Reformat Export File" class="button-primary">
	</div>

	<?php
}



function silibas_replaceAccents($str) {

	$search = explode(",",
"ç,¢,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,£,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
	$replace = explode(",",
"c,o,ae,oe,a,e,i,o,u,a,e,i,o,u,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");

	$str = preg_replace('/[ ]{2,}|[\t]/', '', trim($str));
	
	return str_replace($search, $replace, $str);

}

function exporter_bulk_register_user_table_data($csvURL) {

	$tableData = '';

	$f = fopen($csvURL, "r");
	fgetcsv($f);
	$i=0;
	while (($line = fgetcsv($f)) !== false) {

		if(strpos(utf8_encode($line[0]), '@') !== false){

			$tableData .= '<tr id="userrow-'.$i.'">';

			 foreach ($line as $cell) {
				  $tableData .= '<td>' . replaceAccents(utf8_encode($cell)) . '</td>';
			 }

			 $tableData .= '<td>&nbsp;</td>';
			 $tableData .= '<td>&nbsp;</td>';
			 $tableData .= '<td>&nbsp;</td>';
			 $tableData .= "</tr>\n";

		 }

		 $i++;

	}


	fclose($f);

	return $tableData;

}


add_action('wp_ajax_csv_validate_file', 'silibas_media_selector_settings_validate_callback');

function silibas_media_selector_settings_validate_callback() {



	die();

}









add_action('wp_ajax_sc_ajax_register_bulk_user', 'silibas_bulk_regsiter_single_user_callback');

function silibas_bulk_regsiter_single_user_callback() {

	

	die();

}


add_action( 'admin_footer', 'exporter_media_selector_print_scripts' );

function exporter_media_selector_print_scripts() {

	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

	?>


	<script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {		
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; 
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>;

			jQuery('#upload_image_button').on('click', function( event ){

				event.preventDefault();

				if ( file_frame ) {
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					file_frame.open();
					return;
				} else {
					wp.media.model.settings.post.id = set_to_post_id;
				}

				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false
				});

				file_frame.on( 'select', function() {
					attachment = file_frame.state().get('selection').first().toJSON();

					$('#csvfile').val(attachment.url);
					$('#csvID').val(attachment.id);

					wp.media.model.settings.post.id = wp_media_post_id;
				});

				file_frame.open();
			});

			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});

			jQuery('#validateFile').on('click', function( event ){

				var csvInfo = {
					'action': 'csv_validate_file',
					'csvPath': jQuery('#csvfile').val(),
					'csvID': jQuery('#csvID').val()
				};
				console.log(csvInfo);

				jQuery.post("/wp-admin/admin-ajax.php", csvInfo, function(csvInfoResponse) {
					jQuery('#responseTable tbody').html('');
					jQuery('#responseTable tbody').html(csvInfoResponse);
				});

			});

			jQuery('#reformatExport').on('click', function( event ){
				
			    jQuery('html, body').animate({
			        scrollTop: jQuery("#wpbody-content")
			    }, 2000);

				var users_info = [];

				jQuery('#responseTable > tbody  > tr').each(function(index, tr) { 
					if (index < 99) {
						jQuery('#userrow-'+index+'').find("td:eq(6)").text('registering');
					 		var registerData = {
					 			'action': 'sc_ajax_register_bulk_user',
								// 'firstname': jQuery(this).find("td:eq(1)").text(),
								// 'lastname': jQuery(this).find("td:eq(2)").text(),
								// 'email': jQuery(this).find("td:eq(0)").text(),
								// 'info': jQuery("td:eq(3)").text(),
								// 'source': 'vcleadership.org',
								'index': index,
							};
						console.log(registerData);

						users_info.push(registerData);

	   				}

				});

				//info packaged
				$=jQuery;
				var each = '';
			    j = 0;
			    function nextAjax(i) {

			        var data = users_info[i];

			        $.post(ajaxurl, data, function(response) {
			            // n = new Date($.now());
			            // m = n.getHours()+':'+n.getMinutes();

			            resp = jQuery.parseJSON(response);
			            console.log(response);
			            //successful
			            	//$("#userrow-"+data['index']).find("td:eq(4)").html(resp.username);
			            	//$("#userrow-"+data['index']).find("td:eq(5)").html(resp.password);
			            	//$("#userrow-"+data['index']).find("td:eq(6)").html(resp.response);
			           		
			           		j++;
			            	
			            	$("#processedUsers").html(j);
			            
			            if( j==users_info.length ){
			                alert('finished');
			            } else {
			                nextAjax(j);
			            }

			        });
			    }
			    nextAjax(j);
			});
	 
		});

		function waitForElementToDisplay(selector, callback, checkFrequencyInMs, timeoutInMs) {
		  var startTimeInMs = Date.now();
		  (function loopSearch() {
		    if (document.querySelector(selector) != null) {
		      callback();
		      return;
		    }
		    else {
		      setTimeout(function () {
		        if (timeoutInMs && Date.now() - startTimeInMs > timeoutInMs)
		          return;
		        loopSearch();
		      }, checkFrequencyInMs);
		    }
		  })();
		}

		function functABC(sendData) {
		  return new Promise(function(resolve, reject) {
		    jQuery.ajax({
			  url: "/wp-admin/admin-ajax.php",
		      data: sendData,
		      success: function(data) {
		        resolve(data) // Resolve promise and go to then()
		      },
		      error: function(err) {
		        reject(err) // Reject the promise and go to catch()
		      }
		    });
		  });
	}

	</script><?php

}



function silibas_meta_fields_add_meta_box(){
	add_meta_box(
		'meta_fields_meta_box', 
		__( 'Rep Notifications' ), 
		'silibas_meta_fields_build_meta_box_callback', 
		'product',
		'side',
		'default'
	 );
}

// build meta box
function silibas_meta_fields_build_meta_box_callback( $post ){
	  wp_nonce_field( 'meta_fields_save_meta_box_data', 'meta_fields_meta_box_nonce' );


	  ?>
	  <div class="inside">
	  	  <p><strong>Notify sales rep on complete:</strong></p>

	  	  <?php

	  	  if (is_meta_rep_notify_product($post->ID)) {
	  	  	echo '<p><span style="color:#109c41;">Product is tracked.</span> <a href="/wp-admin/tools.php?page=rep-central">Edit</a></p>';
	  	  } else {
	  	  	echo '<p><span style="color:#d62d1e; font-weight:bolder">Product is not tracked. </span><a href="/wp-admin/tools.php?page=rep-central">Edit</a></p>';
	  	  }
	  	  //
	  	  ?>
		
	  </div>
	  <?php
}
add_action( 'add_meta_boxes', 'silibas_meta_fields_add_meta_box' );




/* csv export


 */

function sv_wc_csv_export_order_line_item_name( $line_item, $item, $product ) {

	if ( $product->is_type( 'variation' ) ) {

		$product_name = $product->get_title();
		$product_name_pieces = explode('-', $product_name);

		$product_name = trim($product_name_pieces[0]);

		$product_name = str_replace('National', '', $product_name);

		$var_desc = get_post_meta($product->get_id(), '_variation_description', true);
		
		if ($var_desc != '') {
			$product_name .= 'Territory '. ucwords($var_desc) .'';
		}
		
		$line_item['name'] = $product_name;

	}

	return $line_item;
}

add_filter( 'wc_customer_order_export_csv_order_line_item', 'sv_wc_csv_export_order_line_item_name', 10, 3 );



// function sv_wc_csv_export_order_line_item_sku( $line_item, $item, $product ) {

// 	if ( $product->is_type( 'variation' ) ) {

// 		$sku = wc_get_product( $product->get_sku());
		
// 		$line_item['region'] = 'Z-'.$sku;
// 	}

// 	return $line_item;
// }
// add_filter( 'wc_customer_order_export_csv_order_line_item', 'sv_wc_csv_export_order_line_item_sku', 10, 3 );

function limit_order_search_fields($search_fields) {
	if (current_user_can('manage_options') || current_user_can('manage_woocommerce')) {
		if (is_admin() && class_exists('woocommerce') && isset($_GET['post_type']) && $_GET['post_type'] === 'shop_order') {
			$search_fields = array(
			'_billing_email',
			//'_billing_address_index',
			//'_billing_first_name',
			//'_billing_last_name',
			// '_shipping_first_name',
			// '_shipping_last_name',
			'ID',
			);
		}
	}
	return $search_fields;
}
add_filter('woocommerce_shop_order_search_fields', 'limit_order_search_fields');
