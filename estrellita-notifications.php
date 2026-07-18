<?php

/* schedule notificaiton cron job */
register_activation_hook( __FILE__, 'silibas_notifications_activation' );

function silibas_notifications_activation() {
	if ( ! wp_next_scheduled ( 'silibas_notifications' ) ) {
		wp_schedule_event( time(), 'hourly', 'silibas_notifications' );
	}
}

register_deactivation_hook( __FILE__, 'silibas_notifications_deactivation' );

function silibas_notifications_deactivation() {
	wp_clear_scheduled_hook( 'silibas_notifications' );
}


add_action( 'silibas_notifications', 'silibas_notifications_cron_job' );

function silibas_notifications_cron_job() {

	global $wpdb;

	$currentTime = time();
	$idsToSend = array();
	$sendPosts = array();

	$notificationQuery = new WP_Query(
		array(
			'post_type' => 'gtt_notifications',
			'orderby' => 'meta_value_num',
			'meta_key' => 'gtt_send_date',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'gtt_send_date',
					'value' => intval( strtotime( date( 'Y-m-d' ) ) ),
					'compare' => '>=',
					'type' => 'numeric'
				)
			)
		)
	);

	if ( $notificationQuery->have_posts() ) {

		while ( $notificationQuery->have_posts() ) : $notificationQuery->the_post();

		$postID = $notificationQuery->post->ID;
		$idsToSend[] = $postID;

		endwhile;

		wp_reset_postdata();

	}


	if ( !empty( $idsToSend ) ) {

		foreach ( $idsToSend as $futureSendID ) {

			$today = date( 'Y-m-d', $currentTime );

			$sendTS = get_post_meta( $futureSendID, 'gtt_send_date', true );
			$hasSent = get_post_meta( $futureSendID, 'gtt_sent', true );

			if ( $hasSent == 'false' ) {
				$sendDate = date( 'Y-m-d', $sendTS );

				if ( $sendDate == $today ) {
					$sendPosts[] = $futureSendID;
					silibas_send_notification( $futureSendID, 'true' );
				}
			}

		}

	}

	update_option( 'notifications_sent', json_encode( implode( ',', $sendPosts ) ) );

}


add_action( 'admin_menu', 'silibas_add_tools_pages' );

function silibas_reset_stock_meta() {



}


function silibas_cron_check_proposals() {

	$processingOrders = get_option('processing_orders');

	if (strpos($a, ',') !== false) {
	 
	 	$processingOrders = explode(',', $processingOrders);

	 	foreach($processingOrders as $order_id) {

	 		silibas_cron_processing_paid_quotes($order_id);
	 	}

	} else {

	 	$order_id = $processingOrders;

	 	silibas_cron_processing_paid_quotes($order_id);

	}
	
	update_option('processing_orders', '');

}


function silibas_cron_processing_paid_quotes($order_id) {

 	if (get_post_meta($order_id, '_transaction_id', true) != '') {

 		$order = new WC_Order($order_id);
    	$order->update_status('processing');
    	$order->add_order_note( __( 'Workflow update: Moved to processing', 'woocommerce' ), false, true );
	}


}

function silibas_add_tools_pages() {
	add_management_page( 'Dev Central', 'Dev Central', 'edit_theme_options', 'dev-central', 'dev_log_functions' );
	// add_management_page( 'Member Codes', 'Member Codes', 'edit_theme_options', 'member-central', 'member_central' );
	add_management_page( 'Regional Rep Admin', 'Regional Reps', 'edit_theme_options', 'rep-central', 'rep_central' );

}


function has_test_code($order_id) {

$testCodes = array('YSTJJ-GYB46-YSTJJ-GYB46-K1213',
			'YRNM4-1TUG3-YRNM4-1TUG3-K1213','YY9GW-6T25N-YY9GW-6T25N-K1213',
			'YFZDZ-EHKDN-YFZDZ-EHKDN-K1213','XFM3D-VZFSS-XFM3D-VZFSS-K1213',
			'XEH5R-8WGYJ-XEH5R-8WGYJ-K1213','Y3QRC-ENBTB-Y3QRC-ENBTB-K1213','XPGPT-WTFHZ-XPGPT-WTFHZ-K1213','XCFER-W1MNH-XCFER-W1MNH-K1213','WHTEU-X2M28-WHTEU-X2M28-K1213','VTGCY-FNWHG-VTGCY-FNWHG-K1213','WT7WS-35AMM-WT7WS-35AMM-K1213','WAEDQ-NKWR4-WAEDQ-NKWR4-K1213','V2AYW-DAGT6-V2AYW-DAGT6-K1213','VDPA6-UMDUK-VDPA6-UMDUK-K1213','UDGJW-TNQ19-UDGJW-TNQ19-K1213','UF1VP-MYFMT-UF1VP-MYFMT-K1213','UBT5W-CVX5G-UBT5W-CVX5G-K1213','TWEKS-NKJRS-TWEKS-NKJRS-K1213','T5TTW-YJSRU-T5TTW-YJSRU-K1213','TMWP2-RTJ6R-TMWP2-RTJ6R-K1213','TQKJM-UT7FZ-TQKJM-UT7FZ-K1213','T7YFY-J2WDX-T7YFY-J2WDX-K1213','TMQC7-ZJRVP-TMQC7-ZJRVP-K1213','RTHC4-ENZYD-RTHC4-ENZYD-K1213','SGHSS-DDXBA-SGHSS-DDXBA-K1213','RCJ1Q-HH4NG-RCJ1Q-HH4NG-K1213','SUMSC-74F4U-SUMSC-74F4U-K1213','T2RQT-BSKTS-T2RQT-BSKTS-K1213','SW2ST-JBPU2-SW2ST-JBPU2-K1213','QF2EN-2ZZMH-QF2EN-2ZZMH-K1213','Q3AMC-AUQP6-Q3AMC-AUQP6-K1213','QD3QY-BSHKB-QD3QY-BSHKB-K1213','PTVYU-UBCRA-PTVYU-UBCRA-K1213','PT2XR-DMQMF-PT2XR-DMQMF-K1213','QC85W-EWWJC-QC85W-EWWJC-K1213','QAB5K-YZTDW-QAB5K-YZTDW-K1213','QJ9CG-E8S1B-QJ9CG-E8S1B-K1213','MVKWX-5SRRY-MVKWX-5SRRY-K1213','M2DPV-1MY54-M2DPV-1MY54-K1213','N6Q3D-SBQC7-N6Q3D-SBQC7-K1213','PP3W6-NUEXQ-PP3W6-NUEXQ-K1213','MTDKQ-NYMD1-MTDKQ-NYMD1-K1213','NQB1H-JCUHF-NQB1H-JCUHF-K1213','NDA2H-JNWNB-NDA2H-JNWNB-K1213','MWNRF-XWUWQ-MWNRF-XWUWQ-K1213','K8Q3H-RAHCX-K8Q3H-RAHCX-K1213','JP1XZ-GN8QC-JP1XZ-GN8QC-K1213','JVR45-F4ADX-JVR45-F4ADX-K1213','KKGMH-NQZFM-KKGMH-NQZFM-K1213','KR5ZH-1UMBJ-KR5ZH-1UMBJ-K1213','KQYQJ-F4HUV-KQYQJ-F4HUV-K1213','K1DHN-Y25EW-K1DHN-Y25EW-K1213','KTCMJ-1SYJQ-KTCMJ-1SYJQ-K1213','J4J8A-EKJWK-J4J8A-EKJWK-K1213','GYXJR-NQHAH-GYXJR-NQHAH-K1213','J5NQW-FQXTB-J5NQW-FQXTB-K1213','HDH6X-4RBCG-HDH6X-4RBCG-K1213','HXJRY-CEJCX-HXJRY-CEJCX-K1213','H2FA7-KDFVB-H2FA7-KDFVB-K1213','GZPU1-MKPRY-GZPU1-MKPRY-K1213','GUYBK-XVVTN-GUYBK-XVVTN-K1213','HQCSG-GVPUG-HQCSG-GVPUG-K1213','FFCAB-A7RQX-FFCAB-A7RQX-K1213','GNGST-TNV7N-GNGST-TNV7N-K1213','FCTGX-2GUFG-FCTGX-2GUFG-K1213','GUSY9-GWNED-GUSY9-GWNED-K1213','DTMC5-BVMPE-DTMC5-BVMPE-K1213','EH9VE-1KDAZ-EH9VE-1KDAZ-K1213','FZEZK-FQMYU-FZEZK-FQMYU-K1213','DYFTS-YTS4S-DYFTS-YTS4S-K1213','FJZ39-KYRKA-FJZ39-KYRKA-K1213','FVGX8-FPGTF-FVGX8-FPGTF-K1213','BJPES-JV1DB-BJPES-JV1DB-K1213','CKFUR-MARSC-CKFUR-MARSC-K1213','CCBK4-AARB2-CCBK4-AARB2-K1213','CSEHS-KRJWP-CSEHS-KRJWP-K1213','C6RGK-S1GRF-C6RGK-S1GRF-K1213','CPHZX-ZN7QG-CPHZX-ZN7QG-K1213','6MSGZ-K2HPA-6MSGZ-K2HPA-K1213','AHJCE-CTGDA-AHJCE-CTGDA-K1213','ARKZA-NXMY2-ARKZA-NXMY2-K1213','76VJR-QUCRY-76VJR-QUCRY-K1213','ARBCE-TP6JZ-ARBCE-TP6JZ-K1213','B2TK6-5VYKE-B2TK6-5VYKE-K1213','9FBAN-BPRVC-9FBAN-BPRVC-K1213','8VFWY-UW71K-8VFWY-UW71K-K1213','B2TK6-5VYKE-B2TK6-5VYKE-K1213','4UWMD-NWZ3R-4UWMD-NWZ3R-K1213','6BATT-HSEFT-6BATT-HSEFT-K1213','26BDK-DYDVA-26BDK-DYDVA-K1213','1WBDK-VHBFH-1WBDK-VHBFH-K1213','3MZEY-QJBHK-3MZEY-QJBHK-K1213','3Z4FW-SGDJG-3Z4FW-SGDJG-K1213','5X93T-RH3RJ-5X93T-RH3RJ-K121');

	$codesText = get_post_meta($order_id, 'codeText', true);

	$hasCode = false;

	foreach($testCodes as $testCode) {
		if (strpos($codesText, $testCode) !== false) {
		    $hasCode = true;
		}
	}

	return $hasCode;

}

function member_central() {


	global $wpdb, $post, $options, $woocommerce;

?>
<div class="wrap">
	<h1>Member Codes & Reports</h1>
	
	<?php

	$siteOptions = get_option('estrellita_options');

	$prekcodes = 'si-membercodes-prek';
	$k1codes = 'si-membercodes-k1';
	$lunitacodes = 'si-membercodes-lunita';
	$escaleracodes = 'si-membercodes-escalera';

	echo '<hr>done<hr>';

	$displayOrders = false;
	$checkCsvCode = true;

	if ($checkCsvCode) {

		

		$k1Orders = tp_get_orders_ids_by_product_id(64340, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$k1OrdersBundle = tp_get_orders_ids_by_product_id(64362, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));


		?><h3>K1 Orders (<?php echo count($k1Orders); ?>)</h3><?php

		foreach($k1Orders as $order_id) {
			//$tc = has_test_code($order_id);

			//if ($tc) {
				echo '<h4>'.$order_id.'</h4>';
			//}

		}

		?><h3>K1 Bundle Orders (<?php echo count($k1OrdersBundle); ?>)</h3><?php

		foreach($k1OrdersBundle as $order_id) {
			$tc =  has_test_code($order_id);

			if ($tc) {
				echo '<h4>'.$order_id.'</h4>';
			}
		}


	}
	



	if ($displayOrders) {

		$k1Orders = tp_get_orders_ids_by_product_id(64340, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$k1OrdersBundle = tp_get_orders_ids_by_product_id(64362, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$prekOrders = tp_get_orders_ids_by_product_id(64340, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$preKOrdersBundle = tp_get_orders_ids_by_product_id(64514, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$lunitaOrders = tp_get_orders_ids_by_product_id(64338, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));
		$escaleraOrders = tp_get_orders_ids_by_product_id(64344, array('wc-completed', 'wc-processing', 'wc-approved-shipment', 'wc-invoiced'));



		?><h3>K1 Orders (<?php echo count($k1Orders); ?>)</h3><?php

		foreach($k1Orders as $order_id) {
			echo list_member_order($order_id);
		}

		?><h3>K1 Bundle Orders (<?php echo count($k1OrdersBundle); ?>)</h3><?php

		foreach($k1OrdersBundle as $order_id) {
			echo list_member_order($order_id);
		}


		?><h3>PreK Orders (<?php echo count($prekOrders); ?>)</h3><?php
		foreach($prekOrders as $order_id) {
			echo list_member_order($order_id);
		}


		?><h3>PreK Bundle Orders (<?php echo count($preKOrdersBundle); ?>)</h3><?php
		foreach($preKOrdersBundle as $order_id) {
			echo list_member_order($order_id);
		}


		?><h3>Lunita Orders (<?php echo count($lunitaOrders); ?>)</h3><?php
		foreach($lunitaOrders as $order_id) {
			echo list_member_order($order_id);
		}


		?><h3>Escalera Orders (<?php echo count($escaleraOrders); ?>)</h3><?php
		foreach($escaleraOrders as $order_id) {
			echo list_member_order($order_id);
		}




	}

	?>

	</div>
	<?php


}


function list_member_order($order_id) {

	$order = new WC_Order($order_id);
	$order_date = $order->order_date;
	
	$returnCode = '<p><a href="https://estrellita.com/wp-admin/post.php?post='.$order_id.'&action=edit">'.$order_id.'</a> - '.$order_date.' (<strong>'.$order->get_status().'</strong>)  '.$order->get_billing_email().' -'.$order->get_billing_first_name().' '.$order->get_billing_last_name().' / ';

	$returnCode .= '<input type="text" value="'.get_post_meta($order_id, 'membershipcodes', true).'"> <a href="#" class="button btn updateCode">Set Code</a> <a href="#" class="button btn queryCode">Query Code</a></p><hr>';

	return $returnCode;

}


function dev_log_functions() {

	global $wpdb, $post, $options, $woocommerce;




?>
<div class="wrap">
	<h1>Development</h1>
<?php

	global $woocommerce;

echo 'Init<hr>';

$ordersInvoicing = array(
	// 91309,
	// 91779,
);

if (!empty($ordersInvoicing)) {
	foreach($ordersInvoicing as $order_id) {
		echo silibas_auto_send_invoice_pdf($order_id);
		echo ' - '.$order_id.'<hr>';

	}
}




?>
</div>
<?php

}

function proposal_accept_notification() {

	global $options, $wpdb, $post, $woocommerce;


	$loop = new WP_Query(
    array(
        'post_type' => 'shop_order',
        'status' => 'wc-proposal-accepted',
        'posts_per_page' => -1,
        'order' => 'ASC'
        )
    );
    while ( $loop->have_posts() ) : $loop->the_post();

		$comments = silibas_custom_get_order_notes( $loop->post->ID );

		foreach($comments as $comment) {

			if (strpos($comment, 'Authorize.Net AIM') !== false) {
			   	
				$order_id = $loop->post->ID;


				$order = new WC_Order($order_id);
				$order->update_status('processing', 'order_note');

				//enque order to send

				if (get_post_meta($loop->post->ID, 'proposal_admin_sent', true) != '1') {

					resend_send_admin_order_email($order);

					update_post_meta( $order_id, 'proposal_admin_sent', '1' );

				}

			}

		}	

	endwhile;

}


function resend_send_admin_order_email( $order ) {
	// Send the admin new order email.
	WC()->payment_gateways();
	WC()->shipping();
	WC()->mailer()->emails['WC_Email_New_Order']->trigger( $order->get_id(), $order );

	// Note the event.
	$order->add_order_note( __( 'Order details manually sent to admin.', 'woocommerce' ), false, true );

	do_action( 'woocommerce_after_resend_order_email', $order, 'new_order' );

	// Change the post saved message.
	add_filter( 'redirect_post_location', array( 'WC_Meta_Box_Order_Actions', 'set_email_sent_message' ) );

}



function silibas_custom_get_order_notes( $order_id ) {
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


add_action( 'estrellita_accepted_quote_notices', 'estrellita_accepted_quotes_cron_callback' );

function estrellita_accepted_quotes_cron_callback() {

	global $wpdb, $post, $options, $woocommerce;

	$post_status = 'wc-proposal-accepted';

	$result = $wpdb->get_results( "SELECT ID FROM $wpdb->posts
	     WHERE post_type = 'shop_order'
	     AND post_status IN ('{$post_status}')
	" );

	echo '<h3>Count '.count( $result ).'</h3>';

	foreach ( $result as $orderResult ) {

		$suspectID = $orderResult->ID;

		if ( get_post_meta( $suspectID, 'admin_notified', true ) != 'true' ) {

			$orderComments = estrellita_get_all_order_comments( $suspectID );

			echo '<p>Check: <a href="/wp-admin/post.php?post='.$suspectID.'&action=edit" target="_blank">'.$suspectID.'</a>';

			if ( estrellita_accepted_check( $orderComments ) ) {
				echo ' - <strong>Transaction Present</strong>';

				echo ' --sent --';
				estrellita_trigger_quote_purchased( $suspectID );

				update_post_meta( $suspectID, 'admin_notified', 'true' );


			}

			echo '</p>';

			echo '<hr>';

		} else {
			echo '<p>Check: <a href="/wp-admin/post.php?post='.$suspectID.'&action=edit" target="_blank">'.$suspectID.'</a>';
			echo ' - <strong>Transaction Present</strong> & already sent</p><hr>';
		}

	}


	echo '<h3>'.get_post_meta( $suspectID, 'admin_notified', true ).'</h3>';

}

function estrellita_accepted_check( $comments ) {

	$wasOrdered = false;


	foreach ( $comments as $comment ) {

		if ( strpos( $comment, 'Charge Approved:' ) !== false ) {
			$wasOrdered = true;
		}

	}

	return $wasOrdered;

}


function estrellita_get_all_order_comments( $postID ) {

	remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

	$comments = get_comments( array(
			'post_id' => $postID,
			'orderby' => 'comment_ID',
			'order'   => 'DESC',
			'approve' => 'approve',
			'type'    => 'order_note',
		) );

	$notes = wp_list_pluck( $comments, 'comment_content' );

	add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );

	return $notes;

}

function estrellita_trigger_quote_purchased( $order_id ) {

	global $woocommerce, $post;

	$order = new WC_Order( $order_id );

	$mailer = WC()->mailer();

	$subject = 'New Quote Accepted Order #'.$order_id;

	$message_body = estrellita_order_message( $order_id );

	$message = $mailer->wrap_message( sprintf( __( 'Order %s accepted and paid' ), $order->get_order_number() ), $message_body );

	$mailer->send( 'joehowarddesign@gmail.com', $subject, $message, '' );

	return true;

}


function estrellita_order_message( $order_id ) {

	global $woocommerce, $product;

	$order = new WC_Order( $order_id );

	$noticeCode = '<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">';
	$noticeCode .= '<thead>';
	$noticeCode .= '	<tr>';
	$noticeCode .= '		<th scope="col" style="text-align:left; border: 1px solid #eee;">Product</th>';
	$noticeCode .= '		<th scope="col" style="text-align:left; border: 1px solid #eee;">Quantity</th>';
	$noticeCode .= '		<th scope="col" style="text-align:left; border: 1px solid #eee;">Price</th>';
	$noticeCode .= '	</tr>';
	$noticeCode .= '</thead>';
	$noticeCode .= '<tbody class="tb">';

	$items = $order->get_items();

	foreach ( $items as $item ) {
		$noticeCode .= '<tr>';
		$noticeCode .= '<td class="col" style="border:1px solid #eee">'.$item['name'].'</td>';
		$noticeCode .= '<td class="col" style="border:1px solid #eee">'.$item['quantity'].'</td>';
		$noticeCode .= '<td class="col" style="border:1px solid #eee">$'.number_format( $item['line_total'], 2, '.', ',' ).'</td>';
		$noticeCode .= '</tr>';
	}

	$noticeCode .= '</tbody>';
	$noticeCode .= '<tfoot>';
	if ( $totals = $order->get_order_item_totals() ) {
		$i = 0;
		foreach ( $totals as $total ) {
			$i++;

			$noticeCode .= '<tr>';
			$noticeCode .= '<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;">'.$total['label'].'</th>';
			$noticeCode .= '<td style="text-align:left; border: 1px solid #eee;">'.$total['value'].'</td>';
			$noticeCode .= '</tr>';

		}
	}
	$noticeCode .= '</tfoot>';

	$noticeCode .= '</table>';

	$noticeCode .= '<p style="margin:20px auto;text-align:center"><a href="https://estrellita.com/wp-admin/post.php?post='.$order_id.'&action=edit">View order #'.$order_id.'</a></p>';

	return $noticeCode;

}



add_action( 'gtt_get_training_times', 'silibas_set_training_times' );

function silibas_set_training_times() {

	global $options;

	$authToken = '1oJZNopdXnsfFi6iOLQf79GLGBXY';

	$gtt = new G2T( array( 'authToken' => $authToken ) );

	$trainings = $gtt->getTrainings();

	foreach ( $trainings as $trainingInfo ) {

		$option_name = 'gtt_'.$trainingInfo->trainingId;
		$option_key_name = 'gtt_key_'.$trainingInfo->trainingKey;

		$timezone_offet = get_option( 'gmt_offset' );

		$startTimeStamp = strtotime( $trainingInfo->times[0]->startDate );
		$endTimeStamp = strtotime( $trainingInfo->times[0]->endDate );

		$startTimeHuman =  date_i18n( 'l, F, jS, Y | g:i A', $startTimeStamp );
		$endTimeHuman = date_i18n( 'g:i A', $endTimeStamp );

		$tz = date_default_timezone_set( get_option( 'timezone_string' ) );

		$dstime = date( 'T', $startTimeStamp );

		$humanTime = $startTimeHuman . '-'.$endTimeHuman . ' ('.$dstime.')';

		if ( !get_option( $option_name ) ) {
			update_option( $option_name, $humanTime );
		}

		if ( !get_option( $option_key_name ) ) {
			update_option( $option_key_name, $humanTime );
		}

	}

}


function silibas_get_training_time( $trainingID ) {

	global $option;

	echo get_option( 'gtt_'.$trainingID );

}




add_action( 'admin_menu', 'notifications_log_page' );

function notifications_log_page() {
	add_management_page( 'Notification Central', 'Notification Central', 'edit_theme_options', 'notification-central', 'notification_log_functions' );
}

function notification_log_functions() {

	global $wpdb, $post, $options, $woocommerce;


	$currentTime = time();
	$queryTime = strtotime( '-1 day', $currentTime );

	$currentDateF = date( 'Ymd', $currentTime );
	$cleanupCutoff = date( '-30 days', $currentTime );

	$idsToSend = array();
	$sendPosts = array();

	$timeOffset = get_option( 'gmt_offset' );


?>
	<div class="wrap">

		<div id="logData"></div>

		<h2>Cleanup Quotes</h2>

		<?php
		$date = date('Y-m-d', time());
		$cleanupCutoff = date('Y-m-d',(strtotime ( '-61 day' , strtotime ( $date) ) ));

		echo '<p>Cut '.$cleanupCutoff.'</p>';

			$initial_date = '2019-01-01';
			$final_date = '2022-06-01';
			//wc-proposal-expired
			
			$args = array(
	    		'status' => array('wc-quote-sent'),
	    		'limit' => 50,
	    		'date_created'=> $initial_date .'...'. $cleanupCutoff 
			);
			
			$orders = wc_get_orders( $args );

			foreach ($orders as $post) {

				echo '<p>ID: '.$post->ID.'</p>';
				$order = new WC_Order($post->ID);
				//wc-proposal-expired
			   	$order->update_status('proposal-expired');
			   	$order->add_order_note( __( 'Quote Expired', 'woocommerce' ), false, true );
			}



		?>

		<h2>Queued</h2>



	</div>

	<?php
}


function silibas_get_notification_time_html( $postID ) {

	$postInfo = get_post( $postID );
	$postContent = $postInfo->post_content;

	$commentStart = 'training date and time starts';
	$commentEnd = 'training date and time ends';

	$text_chunks = explode( $commentStart, $postContent );

	$isoText = explode( $commentEnd, $text_chunks[1] );

	$returnText = preg_replace( '/<!--(.*)-->/Uis', '', $isoText[0] );
	$cleanText = strip_tags( $returnText );
	$cleanText = str_replace( '-->', '', $cleanText );

	return $cleanText;

}



add_action( 'wp_ajax_send_notice', 'silibas_send_notice_callback' );

function silibas_send_notice_callback() {

	$updateReceipt = $_POST['receipt'];
	$postID = $_POST['postID'];

	$updateNotes = '';

	$updateNotes .= silibas_send_notification( $postID, $updateReceipt );


	if ( $updateReceipt == 'true' ) {

		update_post_meta( $postID, 'gtt_sent', 'true' );

	}

	echo $updateNotes;

	die();

}


function silibas_send_notification( $postID, $updateReceipt ) {

	global $post, $wpdb, $options;

	$returnReceipt = '';

	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	//prep the content
	$postInfo = get_post( $postID );

	$trainingName = $postInfo->post_title;

	$trainingHeadline = get_post_meta( $postID, 'gtt_headline', true );
	$trainingContent = $postInfo->post_content;

	$emailMessageTo = get_post_meta( $postID, 'gtt_send_to', true );

	//prep the mailing
	$mailRecipients = array();
	//$mailRecipients[] = 'optionalcc@example.com';

	$headers = 'From: '.$blogname.' <info@estrellita.com>' . "\r\n";
	$headers .= 'Content-type: text/html';

	$mailSubject = 'Your Upcoming Training';
	$mailRecipients[] = $emailMessageTo;

	//$htmlTemplate =  ABSPATH . 'wp-content/plugins/estrellita-toolkit/templates/email/estrellita-notifications-template.php';

	//$htmlCode = file_get_contents($htmlTemplate);

	$htmlCode = temp_html_template_two();

	$message = str_replace( '<!--notificationheader-->', $trainingHeadline, $htmlCode );
	$message = str_replace( '<!--notificationbody-->', $trainingContent, $htmlCode );

	//@wp_mail( $mailRecipients, $mailSubject, $message, $headers );

	update_post_meta( $postID, 'gtt_sent_verify', time() );

	if ( $updateReceipt == 'true' ) {
		update_post_meta( $postID, 'gtt_sent', 'true' );
	}

	$returnReceipt .= 'SENT -- to: '.$emailMessageTo. '<br>' . $mailSubject. '<br><pre></pre><hr>';

	return $returnReceipt;

}


add_action( 'wp_ajax_add_user_training', 'silibas_add_user_training_callback' );

function silibas_add_user_training_callback() {

	$order_id = $_POST['order_id'];
	$trainingID = $_POST['trainingID'];

	$userInfo = array();

	$userInfo['first_name'] = $_POST['firstname'];
	$userInfo['last_name'] = $_POST['lastname'];
	$userInfo['email'] = $_POST['email'];

	if (!empty($trainingID)) {


		//add user to training
		$trainingResponse = silibas_add_training_user( $trainingID, $userInfo );

		$trainingResponse = json_decode( json_encode( $trainingResponse ), True );

		//create notifications on success
		if ( $trainingResponse != '' ) {
			$joinUrl = $trainingResponse['joinUrl'];
			$confirmationUrl = $trainingResponse['confirmationUrl'];
			$registrantKey = $trainingResponse['registrantKey'];
			silibas_add_training_notifications(
				$trainingID,
				$userInfo,
				$joinUrl,
				$confirmationUrl,
				$registrantKey
			);

		}

		update_post_meta( $order_id, 'zoom_response', maybe_serialize( $trainingResponse ) );

	}
	
	echo $trainingID;

	die();

}

function temp_html_template_two() {


	$emailCode = '';

	$emailCode .= '<html><head><title>Estrellita</title></head>';
	$emailCode .= '<body class="viewframe"><div id="wrapper" dir="ltr" style="background-color: #ffffff; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">';
	$emailCode .= '<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0"><tbody><tr>';
	$emailCode .= '<td valign="top" align="center"><div id="template_header_image">';
	$emailCode .= '<p style="margin-top: 0;"><img src="http://estrellita.com/wp-content/uploads/2014/02/EstrellitaLogo_AcceleratedTagline_72dpi.jpg" alt="Estrellita" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></p></div>';
	$emailCode .= '<table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #e5e5e5; border-radius: 3px !important;" border="0" width="600" cellspacing="0" cellpadding="0">';
	$emailCode .= '<tbody><tr>';
	$emailCode .= '<td valign="top" align="center">';
	$emailCode .= '<table id="template_header" style="background-color: #e36207; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;" border="0" width="600" cellspacing="0" cellpadding="0"><tbody><tr>';
	$emailCode .= '<td id="header_wrapper" style="padding: 36px 48px; display: block;">';
	$emailCode .= '<h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #e98139; -webkit-font-smoothing: antialiased;"><!--notificationheader--></h1>';
	$emailCode .= '</td></tr></tbody></table></td></tr><tr><td valign="top" align="center">';
	$emailCode .= '<table id="template_body" border="0" width="600" cellspacing="0" cellpadding="0"><tbody><tr>';
	$emailCode .= '<td id="body_content" style="background-color: #fdfdfd;" valign="top">';
	$emailCode .= '<table border="0" width="100%" cellspacing="0" cellpadding="20"><tbody><tr>';
	$emailCode .= '<td style="padding: 48px 0;" valign="top">';
	$emailCode .= '<div id="body_content_inner" style="width:560px;margin:0 40px;color: #737373; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">';
	$emailCode .= '<!--notificationbody-->';
	$emailCode .= '</div>';
	$emailCode .= '</td></tr></tbody></table></td></tr></tbody></table></td></tr><tr>';
	$emailCode .= '<td valign="top" align="center"><table id="template_footer" border="0" width="600" cellspacing="0" cellpadding="10"><tbody><tr>';
	$emailCode .= '<td style="padding: 0; -webkit-border-radius: 6px;" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="10"><tbody><tr>';
	$emailCode .= '<td colspan="2" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #eea16a; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" valign="middle"><p>From one of the most trusted and effective early Spanish literacy programs for over 30 years.</p></td>';
	$emailCode .= '</tr></tbody></table></td></tr></tbody></table></td></tr>';
	$emailCode .= '</tbody></table></td></tr></tbody></table></div></body></html>';


	return $emailCode;
}

function timezoneDoesDST( $tzId, $tstamp ) {
	$tz = new DateTimeZone( $tzId );
	return count( $tz->getTransitions( $tstamp ) ) > 0;
}
