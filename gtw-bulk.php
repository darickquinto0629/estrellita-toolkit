<?php

//$contributor = get_role('contributor'); 
//$contributor->add_cap('upload_files'); 


add_action('admin_menu', 'gtw_management_function');

function gtw_management_function() {
	add_management_page('Webinars', 'Webinars', 'manage_woocommerce', 'gtw-registration', 'gtw_management_page');
}

function gtw_management_page() {

	global $wpdb, $post, $options, $woocommerce;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');  
	
	$contributor = get_role('estrellitamanager'); 
	$contributor->add_cap('upload_files'); 

	$contributor = get_role('administrator'); 
	$contributor->add_cap('upload_files'); 


	$showCurrentTrainings = FALSE;

	$resendUser = true;
	$resendID = 68184;	
	//2621277921068705035



	$trainingID = '5860629393929095952';

	?>
	
	<div class="wrap">

	<?php

	$order_id = $_GET["order"];
	$order = wc_get_order( $order_id );

	if (!$order_id) {
		echo '<p>Order not found. <a href="/wp-admin/edit.php?post_type=shop_order">Return to orders</a>.</p>';
		die();
	}

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
		<input type="button" id="validateFile" name="submit_image_selector" value="Validate File" class="button-secondary">
	</form>
	<hr>
	<h3>Order Data</h3>


	<table id="orderTable" class="wp-list-table widefat fixed striped table-view-list">
		<thead>
			<td>Order</td>
			<td>Status</td>
			<td>PD</td>
			<td>Qty</td>
			<td>GTW ID</td>
		</thead>
	
	<?php
	echo '<tbody><tr>';
	echo '<td><a href="/wp-admin/post.php?post='.$order_id.'&action=edit" id="order_id">'.$order_id.'</a></td>';
	echo '<td>'.$order->get_status().'</td>';

	foreach ($order->get_items() as $item_key => $item ) {

	    $item_id = $item->get_id();
	    $item_name    = $item->get_name();

	    $product_id   = $item->get_product_id();
	    $variation_id = $item->get_variation_id();

	    $product      = $item->get_product();
	    $product_sku    = $product->get_sku();

	    $item_data    = $item->get_data();

	    echo '<td>'.$item_name.'</td>';
	    echo '<td id="qty">'.$item_data['quantity'].'</td>';
	    echo '<td id="sku"><a href="https://dashboard.gotowebinar.com/webinar/'.$product_sku.'" target="_blank">'.$product_sku.'</a></p>';

	}
	echo '</tr><tbody></table>';

?>

<hr>
	<table id="responseTable" class="wp-list-table widefat fixed striped table-view-list">
		<thead>
		<tr>
		  <th>First Name</th>
		  <th>Last Name</th>
		  <th>Email</th>
		  <th>School Name</th>
		  <th>Response</th>
		</tr>
		</thead>
		<tbody>
			<?php 
			 //echo bulk_register_user_table_data(get_attached_file(get_option( 'media_selector_attachment_id' )) ); 			
			?>
		</tbody>
	</table>
	<hr>
	<div id="status"></div>
	<div id="registeredCount">Registered: <span id="processedUsers">0</span></div>
	<hr>
		<input id="registerBulk" type="submit" name="submit_image_selector" value="Run Bulk Registration" class="button-primary">
	</div>

	<?php
}



function replaceAccents($str) {
	$search = explode(",",
"ç,¢,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,£,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
	$replace = explode(",",
"c,o,ae,oe,a,e,i,o,u,a,e,i,o,u,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");

	$str = preg_replace('/[ ]{2,}|[\t]/', '', trim($str));
	
	return str_replace($search, $replace, $str);


}

function bulk_register_user_table_data($csvURL) {

	$tableData = '';

	$f = fopen($csvURL, "r");
	fgetcsv($f);
	$i=0;
	while (($line = fgetcsv($f)) !== false) {
		$tableData .= '<tr id="userrow-'.$i.'">';
		 foreach ($line as $cell) {
			  $tableData .= '<td>' . replaceAccents(utf8_encode($cell)) . '</td>';
		 }
		 $tableData .= "</tr>\n";
		 $i++;
	}

	fclose($f);

	return $tableData;

}

add_action( 'admin_footer', 'media_selector_print_scripts' );

function media_selector_print_scripts() {

	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

	?><script type='text/javascript'>

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

				jQuery.post("/wp-admin/admin-ajax.php", csvInfo, function(csvInfoResponse) {
					jQuery('#responseTable tbody').html('');
					jQuery('#responseTable tbody').html(csvInfoResponse);
				});

			});

			

			jQuery('#registerBulk').on('click', function( event ){
				
			    jQuery('html, body').animate({
			        scrollTop: jQuery("#quote_id")
			    }, 2000);

				var users_info = [];

				jQuery('#responseTable > tbody  > tr').each(function(index, tr) { 
					if (index < 99) {
						jQuery('#userrow-'+index+'').find("td:eq(4)").text('registering');
					 		var registerData = {
					 			'action': 'gtw_ajax_register_bulk_user',
					 			'trainingID': jQuery('#sku').text(),
								'firstname': jQuery(this).find("td:eq(0)").text(),
								'lastname': jQuery(this).find("td:eq(1)").text(),
								'email': jQuery(this).find("td:eq(2)").text(),
								'order_id': jQuery('#order_id').text(),
								'source': 'estrellita.com',
								'index': index,
							};

						users_info.push(registerData);

	   				}

				});



				//info packaged

				console.log(users_info);
				$=jQuery;
				var each = '';
			    j = 0;
			    function nextAjax(i) {
			        var data = users_info[i];

			        $.post(ajaxurl, data, function(response) {
			            n = new Date($.now());
			            m = n.getHours()+':'+n.getMinutes();
			            $("#userrow-"+data['index']).find("td:eq(4)").html(m+' '+response);
			            j++;
			            $("#processedUsers").html(j);
			            if( j==users_info.length ){
			                alert('registration finished');
			            } else {
			                nextAjax(j);
			            }
			        });
			    }
			    console.log('Start')
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

add_action('wp_ajax_test_promise', 'testpromisecallback');

function testpromisecallback() {


	usleep(2000000);

	echo 'success';

	die();

}

add_action('wp_ajax_gtw_ajax_register_bulk_user', 'bulk_regsiter_single_user_callback');

function bulk_regsiter_single_user_callback() {

	$returnResponse = '';

	$userInfo = [];

	$userInfo['firstName'] = $_POST['firstname'];
	$userInfo['lastName'] = $_POST['lastname'];
	$userInfo['email'] = $_POST['email'];
	$userInfo['source'] = 'bulk registration';

	$trainingID = $_POST['trainingID'];
	$order_id = $_POST['order_id'];
	$index = $_POST['index'];

	$gtw = new GTW(array());

	$trainingResponse = $gtw->gtw_register_user($trainingID, $userInfo);
	$trainingResponse = json_decode(json_encode($trainingResponse), true);

	update_post_meta($order_id, 'bulk_registrant_'.$index, $_POST['firstname'] . ' ' .$_POST['lastname'] . ' - '.$_POST['email']);


	if ($trainingResponse['status'] == 'APPROVED') {
		echo '<div id="success-'.$index.'"><strong>success</strong> ' . $trainingResponse['registrantKey'].'</div>';
	} else {
		echo '';
	}

	//echo '<pre>'.print_r($trainingResponse, true).'</pre>';

	die();
}

add_action('wp_ajax_csv_validate_file', 'media_selector_settings_validate_callback');

function media_selector_settings_validate_callback() {

	global $options;

	$response = '';
	
	$order_id = $_POST['order_id'];
	$csvPath = $_POST['csvPath'];
	$csvID = $_POST['csvID'];

	update_option( 'media_selector_attachment_id', $csvID );
	//attach meta to quote

	echo bulk_register_user_table_data($csvPath);


	die();
}

