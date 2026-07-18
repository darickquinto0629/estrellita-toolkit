<?php

add_action('admin_head', 'silibas_tailor_manager_view');
add_action('admin_footer', 'silibas_tailor_manager_view_scripts');

function silibas_tailor_manager_view() {

    global $current_user;


    if ($current_user->ID == 86) {
    	?>
	<link rel='stylesheet' id='custom_wp_admin_css-css'  href='//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' type='text/css' media='all' />

    	<?php
    }

	if (silibas_is_shop_mananer()) {

		echo '<!-- uid: '.$current_user->ID.'-->';

    	?>

	<style>

		#woocommerce-order-actions {
			display: block!important;
		}

		#adminmenu li#menu-dashboard,
		#adminmenu li#menu-posts,
		#adminmenu li#menu-posts-tribe_events,
		#adminmenu li#menu-media,
		#adminmenu li#menu-posts-quotes,
		#adminmenu li#menu-posts-training_forms,
		#adminmenu li#menu-pages,
		#adminmenu li#menu-posts-gtt_notifications,
		#adminmenu li#menu-comments,
		#adminmenu li#menu-tools,
		#adminmenu li#menu-settings,
		#adminmenu li#toplevel_page_real3d_flipbook_menu,
		#adminmenu li#toplevel_page_video-user-manuals-plugin,
		#wpadminbar #wp-admin-bar-comments,
		#wpadminbar #wp-admin-bar-new-content,
		#wpadminbar #wp-admin-bar-wpseo-menu,
		#wpadminbar #wp-admin-bar-tribe-events,
		#wpadminbar #wp-admin-bar-wp-logo,
		#order_page_create_invoice,
		#screen-meta-links,
		#woocommerce-adq-quote,
		#postcustom,
		#woocommerce-order-downloads,
		#order_page_create_invoice,
		#woocommerce-order-actions li#actions,
		.wc-order-data-row button.refund-items,
		.wp-admin #order_data .wc-order-status,
		a.page-title-action,
		p.order_number,
		label[for=order_date],
		._billing_wc_avatax_vat_id_field,
		.order_data_column:first-of-type > p:first-of-type,
		.wc-proposal-sent,
		.wc-proposal-expired,
		.wc-proposal-rejected,
		.wc-proposal-accepted,
		._billing_company_field,
		{
			display: none!important;
		}
		.wc-order-totals .label {
			color:#555;
			font-size: 100%;
		}
		#woocommerce-order-items .wc-order-totals .label {
		    vertical-align: middle!important;
		}
		#woocommerce-order-notes,
		#woocommerce-order-actions {
			display: block!important;
		}
		#order_data .order_data_column ._billing_company_field {
			display: none!important;
		}
	</style>
	<link rel='stylesheet' id='custom_wp_admin_css-css'  href='//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' type='text/css' media='all' />

	<div class="modal" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" style="display:none;">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	            <h4 class="modal-title" id="myModalHeader"></h4>
	            </div>
	            <div class="modal-body" id="myModalBody">
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default refreshURL" data-dismiss="modal">Refresh URL</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div>
	  </div>
	</div>

    	<?php

 if ($current_user->ID == 85) {

    	?>
    	<style>
    	#menu-posts-customerquote {
    		display: none!important;
    	}
    .wp-admin #adminmenumain,
    .wp-admin #menu-posts-product
     {display: block!important;}
     .wp-admin #wpcontent {
	    margin-left: 160px!important;
	}
    	</style>
    	<?php
    }
    }

   

    $a = 3;
    if ($a != 3) {
   // if (silibas_is_shop_mananer_alt()) {
    	?>

	<style>
		#woocommerce-order-actions {
			display: block!important;
		}
		.wc-order-totals .label {
			color:#555;
			font-size: 100%;
		}
		#woocommerce-order-items .wc-order-totals .label {
		    vertical-align: middle!important;
		}
		#woocommerce-order-notes,
		#woocommerce-order-actions {
			display: block!important;
		}
		.om_button {
			float: right;
		}

	</style>
	<link rel='stylesheet' id='custom_wp_admin_css-css'  href='//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' type='text/css' media='all' />

	<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	            <h4 class="modal-title" id="myModalHeader"></h4>
	            </div>
	            <div class="modal-body" id="myModalBody">
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default refreshURL" data-dismiss="modal">Refresh URL</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div>
	  </div>
	</div>

    	<?php
    }


}

add_action('admin_footer', 'silibas_admin_modal');

function silibas_admin_modal() {

$current_user = wp_get_current_user();
if ( current_user_can( 'edit_users' ) ) {
?>
<style>
	.unitPricingContainer p span {
	    width: 50%;
	    display: block;
	    float: left;
	}
	.newData .select2-container--default .select2-selection--single .select2-selection__rendered {
    	color: #b20000;
    }
	.presentData .select2-container--default .select2-selection--single .select2-selection__rendered,
	 .name_success {
    	color: #35933f;
    }
	</style>
<?php
	?>
	<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	            <h4 class="modal-title" id="myModalHeader"></h4>
	            </div>
	            <div class="modal-body" id="myModalBody">
	            </div>
	            <div class="modal-footer">
					<button type="button" class="btn btn-default refreshURL" data-dismiss="modal">Refresh URL</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div>
	  </div>
	</div>


	<?php
	}
}

function silibas_tailor_manager_view_scripts() {

	global $post;

?>
		<style>
		#mv_other_fields .inside {padding-bottom:45px;}

		#mv_other_fields .unlock-sd {
			margin-right:5px;
			float: left;
		}
		#mv_other_fields .unlock-sd .dashicons {
			font-size: 18px;
		}
		#mv_other_fields .editmeta {
			float:left;
			margin-right:10px;
		}
		.name_error {color: #b20000 !important;}
		#spinning {
			height: 100%;
		    width: 100%;
		    position: absolute;
		    background-color: rgba(0, 0, 0, .4);
		    z-index: 999;
		}
		.spin-container {
		    height: 50px;
		    position: relative;
		    width: 50px;
		    top: 42%;
		    left: 50%;
		}
		.refreshURL {display: none;border:1px solid #222;background-color:#ccc;}
		</style>
<?php
	if (silibas_is_shop_mananer_alt()) { ?>

	<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js?ver=4.9.10'></script>
	
	<script>
	jQuery(function() {

		jQuery("input.hasDatepicker, input.hour, input.minute").css('display', 'none');

		jQuery('body').on('click', '.completeOrder', function(e) {	

			var approveShip = true;

			var orderID = jQuery(this).data('postid');
			var businessData = '';
			var schooldistrict = jQuery('#district-select :selected').text();
			var schooldistrict_id = jQuery('#district-id').val();
			var schoolname = jQuery('#school-select :selected').text();
			var schoolname_id = jQuery('#school-id').val();

			if (schooldistrict == '') {
				approveShip = false;
			}
			if (!jQuery.isNumeric(schooldistrict_id) || schooldistrict_id != 0) {
				if (!approveShip) {
					approveShip = false;
				}
			} else {
				approveShip = false;
			}
			if (schoolname == '') {
				approveShip = false;
			}
			if (!jQuery.isNumeric(schoolname_id)) {

				if (schoolname == 'Select School') {
					approveShip = false;
				} else {
				}
			}

			if(jQuery('#new').is(':checked')) {
				var businessData = 'new';
			}; 
			if(jQuery('#existing').is(':checked')) {
				var businessData = 'existing';
			}; 

			if (businessData == '') {
				alert('Please select new or existing business before proceeding');
				return false;
			}

    		if (jQuery('#apEmail').val() != '' && jQuery('#region').val() != '') {

				jQuery('#basicModal').modal('show');
				jQuery('#basicModal .modal').css('display', 'block');
				jQuery('#basicModal').removeClass('fade');

				jQuery("#myModalBody").html("<p>Complete Order Initiated...</p>");


				var updateMetaData = {
		            'action': 'silibas_completed_update_order_meta',
		            'postID': orderID,
		            'poNumber': jQuery('#poNumber').val(),
		            'apEmail': jQuery('#apEmail').val(),
		            'businessData': businessData,
		            'region': jQuery('#region').val(),
	        		'salesperson': jQuery('#salesperson').val(),
			        'schooldistrict': schooldistrict,
			        'schoolname': schoolname,
			        'schooldistrict_id': schooldistrict_id,
			        'schoolname_id': schoolname_id,
		          };

		          if (jQuery('.unit_1').val() != '') {
		            var unitData = {};

		            //has unit price data to save
		            jQuery('.unitPrice').each(function () {
		              unitData[jQuery(this).attr('data-productid')] = jQuery(this).val();
		            });

		            updateMetaData['unitPrices'] = unitData;          

		          }

		          if (jQuery('#alt_subtotal').val() != '') {
		            updateMetaData['alt_subtotal'] = jQuery('#alt_subtotal').val(); 
		          }

		          if (jQuery('#alt_shipping').val() != '') {
		            updateMetaData['alt_shipping'] = jQuery('#alt_shipping').val(); 
		          }

		          if (jQuery('#alt_tax').val() != '') {
		            updateMetaData['alt_tax'] = jQuery('#alt_tax').val(); 
		          }

		          if (jQuery('#alt_total').val() != '') {
		            updateMetaData['alt_total'] = jQuery('#alt_total').val(); 
		          }

		          
		          jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", updateMetaData, function(apResp, status) {


		          });

					var data = {
						'action': 'complete_order',
						'orderID': orderID,
					};

					jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
						jQuery('#myModalBody').html(response);
						jQuery('#ajaxResponse').html('Updated');
						jQuery('.refreshURL').fadeIn();
					});

			} else {
	          alert('Please include A/P Email & Region/County fields to continue');
	          return false;
	        }
			return false;
			e.preventDefault();

		});

		jQuery('body').on('click', '.closedLost', function(e) {		

			var approveShip = false;

			var orderID = jQuery(this).data('postid');
				
			jQuery('#basicModal').modal('show');

	        jQuery('#basicModal').modal('show');
	        jQuery('#basicModal .modal').css('display', 'block');
	        jQuery('#basicModal').removeClass('fade');

	        jQuery("#myModalBody").html("<p>Clost / Lost status initiated......</p>");


			var data = {
				'action': 'move_closed',
				'orderID': orderID,
			};
			
			jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
				jQuery('#myModalBody').html(response);
				jQuery('#ajaxResponse').html('Updated');
				jQuery('.refreshURL').fadeIn();
			});

			
			return false;

		});



		jQuery('body').on('click', '.orderReview', function(e) {		

			var approveShip = true;
			var businessData = '';

			var curStatus = jQuery('#current-status').val();
			var shouldVal = true;

			//check for new/existing business
			if(jQuery('#new').is(':checked')) {
				var businessData = 'new';
			}; 
			if(jQuery('#existing').is(':checked')) {
				var businessData = 'existing';
			}; 

			var orderID = jQuery(this).data('postid');
			var poNumber = jQuery('#poNumber').val();
			var ccNumber = jQuery('#_transaction_id').val();
			var region = jQuery('#region').val();
			var apemail = jQuery('#apEmail').val();
			var salesperson = jQuery('#salesperson').val();

			var emailAddress = jQuery('#emailAddress').val();
			var schooldistrict = jQuery('#district-select :selected').text();
			var schooldistrict_id = Number(jQuery('#district-id').val());
			var schoolname = jQuery('#school-select :selected').text();
			var schoolname_id = Number(jQuery('#school-id').val());

			if (typeof schoolname_id !== 'number' || isNaN(schoolname_id)) {
				var schoolname_id = '';
			}

			if (typeof schooldistrict_id !== 'number' || isNaN(schooldistrict_id)) {
				var schooldistrict_id = '';
			}			

			if (shouldVal) {
				console.log(shouldVal);
				if (poNumber == '') {
					approveShip = false;
				}
				if (region == '') {
					approveShip = false;
				}
				if (apemail == '') {
					approveShip = false;
				}
				if (salesperson == '') {
					approveShip = false;
				}

				if (schooldistrict == '') {
					approveShip = false;
				}

				if (businessData == '') {
					alert('Please select new or existing business before proceeding');
					approveShip = false;
					return false;
				}

			}
			

			if (approveShip) {

				jQuery('#basicModal').modal('show');

		        jQuery('#basicModal').modal('show');
		        jQuery('#basicModal .modal').css('display', 'block');
		        jQuery('#basicModal').removeClass('fade');

		        jQuery("#myModalBody").html("<p>Review status initiated......</p>");

				var data = {
					'action': 'move_review',
			        'poNumber': jQuery('#poNumber').val(),
			        'apEmail': jQuery('#apEmail').val(),
			        'region': jQuery('#region').val(),
			        'salesperson': jQuery('#salesperson').val(),
					'orderID': orderID,
		            'businessData': businessData,
			        'schooldistrict': schooldistrict,
			        'schoolname': schoolname,
			        'schooldistrict_id': schooldistrict_id,
			        'schoolname_id': schoolname_id,
				};
				
				jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
					jQuery('#myModalBody').html(response);
					jQuery('#ajaxResponse').html('Updated');
					jQuery('.refreshURL').fadeIn();
				});

			} else {
				alert('Please fill out all required fields');
			}

			
			return false;

		});

		jQuery('body').on('click', '.moveInvoiced', function(e) {		

			var approveShip = false;

			var orderID = jQuery(this).data('postid');
			var emailAddress = jQuery('#emailAddress').val();
				
			jQuery('#basicModal').modal('show');

          jQuery('#basicModal').modal('show');
          jQuery('#basicModal .modal').css('display', 'block');
          jQuery('#basicModal').removeClass('fade');

          jQuery("#myModalBody").html("<p>Invoiced status initiated......</p>");


			var data = {
				'action': 'move_invoiced',
				'orderID': orderID,
			};

			jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
				jQuery('#myModalBody').html(response);
				jQuery('#ajaxResponse').html('Updated');
				jQuery('.refreshURL').fadeIn();
			});

			
			return false;

		});
		jQuery('body').on('click', '.refreshURL', function(e) {
			location.reload();
		});
		//refreshURL


		jQuery('body').on('click', '.saveOrder', function(e) {
			var approveShip = true;
			var businessData = '';

			var curStatus = jQuery('#current-status').text();
			var shouldVal = false;

			if (curStatus == 'wc-completed' || curStatus == 'wc-approved-shipping') {
				var shouldVal = true;
			}
			
			//check for new/existing business
			if(jQuery('#new').is(':checked')) {
				var businessData = 'new';
			}; 
			if(jQuery('#existing').is(':checked')) {
				var businessData = 'existing';
			}; 

			var orderID = jQuery(this).data('postid');
			var poNumber = jQuery('#poNumber').val();
			var ccNumber = jQuery('#_transaction_id').val();
			var region = jQuery('#region').val();
			var apemail = jQuery('#apEmail').val();
			var salesperson = jQuery('#salesperson').val();

			var schooldistrict = jQuery('#district-select :selected').text();
			var schooldistrict_id = Number(jQuery('#district-id').val());
			var schoolname = jQuery('#school-select :selected').text();
			var schoolname_id = Number(jQuery('#school-id').val());

			if (typeof schoolname_id !== 'number' || isNaN(schoolname_id)) {
				var schoolname_id = '';
			}

			if (typeof schooldistrict_id !== 'number' || isNaN(schooldistrict_id)) {
				var schooldistrict_id = '';
			}

			if (shouldVal) {
				if (poNumber == '') {
					approveShip = false;
				}
				if (region == '') {
					approveShip = false;
				}
				if (apemail == '') {
					approveShip = false;
				}
				if (salesperson == '') {
					approveShip = false;
				}

				if (schooldistrict == '') {
					approveShip = false;
				}
		
				if (!jQuery.isNumeric(schooldistrict_id) || schooldistrict_id != 0) {
					if (!approveShip) {
						//approveShip = false;
					}
				} else {
					//approveShip = false;
				}	

				if (schoolname == '') {
					approveShip = false;
				}

				if (!jQuery.isNumeric(schoolname_id)) {

					if (schoolname == 'Select School') {
						approveShip = false;
					}

				}

				if (businessData == '') {
					alert('Please select new or existing business before proceeding');
					return false;
				}

				console.log(shouldVal);
				console.log(region);
				console.log(apemail);
				console.log(salesperson);
				console.log(schooldistrict);
				console.log(businessData);

			}

			console.log(approveShip);

			if (approveShip) {
				
				jQuery('#basicModal').modal('show');

		        jQuery('#basicModal').modal('show');
		        jQuery('#basicModal .modal').css('display', 'block');
		        jQuery('#basicModal').removeClass('fade');

				jQuery("#myModalBody").html("<p>Saving Order metadata......</p>");

				var data = {
					'action': 'save_ordermetadata',
					'orderStatus': curStatus,
					'orderID': orderID,
					'poNumber': poNumber,
					'ccNumber': ccNumber,
			        'apEmail': apemail,
			        'region': region,
		            'businessData': businessData,
			        'salesperson': salesperson,
			        'schooldistrict': schooldistrict,
			        'schoolname': schoolname,
			        'schooldistrict_id': schooldistrict_id,
			        'schoolname_id': schoolname_id,
				};

		        if (jQuery('.unit_1').val() != '') {
		          var unitData = {};
		          //has unit price data to save
		          jQuery('.unitPrice').each(function () {
		            unitData[jQuery(this).attr('data-productid')] = jQuery(this).val();
		          });
		          data['unitPrices'] = unitData;          
		        }

		    	if (jQuery('#alt_subtotal').val() != '') {
		          data['alt_subtotal'] = jQuery('#alt_subtotal').val(); 
		        }
		        if (jQuery('#alt_shipping').val() != '') {
		          data['alt_shipping'] = jQuery('#alt_shipping').val(); 
		        }
		        if (jQuery('#alt_tax').val() != '') {
		          data['alt_tax'] = jQuery('#alt_tax').val(); 
		        }
		        if (jQuery('#alt_total').val() != '') {
		          data['alt_total'] = jQuery('#alt_total').val(); 
		        }

				jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
					jQuery('#myModalBody').html(response);
					jQuery('#ajaxResponse').html('Updated');
					jQuery('.refreshURL').fadeIn();
				});

			} else {
				alert('Please fill out all required fields');
			}

			return false;

		});


		jQuery('body').on('click', '.approveShipping', function(e) {		

			var approveShip = true;
			var businessData = '';
			
			//check for new/existing business
			if(jQuery('#new').is(':checked')) {
				var businessData = 'new';
			}; 
			if(jQuery('#existing').is(':checked')) {
				var businessData = 'existing';
			}; 



			var orderID = jQuery(this).data('postid');
			var poNumber = jQuery('#poNumber').val();
			var ccNumber = jQuery('#_transaction_id').val();
			var region = jQuery('#region').val();
			var apemail = jQuery('#apEmail').val();
			var salesperson = jQuery('#salesperson').val();
			var schooldistrict = jQuery('#district-select :selected').text();
			var schooldistrict_id = jQuery('#district-id').val();
			var schoolname = jQuery('#school-select :selected').text();
			var schoolname_id = jQuery('#school-id').val();



			if (businessData == '') {
				alert('Please select new or existing business before proceeding');
				return false;
			}
			if (poNumber == '') {
				approveShip = false;
			}

			if (region == '') {
				approveShip = false;
			}
			if (apemail == '') {
				approveShip = false;
			}
			if (salesperson == '') {
				approveShip = false;
			}
			if (schooldistrict == '') {
				approveShip = false;
			}

			if (schoolname == '') {
				approveShip = false;
			}

			if (approveShip) {
				
				jQuery('#basicModal').modal('show');

		          jQuery('#basicModal').modal('show');
		          jQuery('#basicModal .modal').css('display', 'block');
		          jQuery('#basicModal').removeClass('fade');
					

				jQuery("#myModalBody").html("<p>Approval Initiated......</p>");

				var data = {
					'action': 'approve_shipping',
					'orderID': orderID,
					'poNumber': poNumber,
					'ccNumber': ccNumber,
			        'apEmail': apemail,
			        'region': region,
		            'businessData': businessData,
			        'salesperson': salesperson,
			        'schooldistrict': schooldistrict,
			        'schoolname': schoolname,
			        'schooldistrict_id': schooldistrict_id,
			        'schoolname_id': schoolname_id,
				};

		          if (jQuery('.unit_1').val() != '') {
		            var unitData = {};

		            //has unit price data to save
		            jQuery('.unitPrice').each(function () {
		              unitData[jQuery(this).attr('data-productid')] = jQuery(this).val();
		            });

		            data['unitPrices'] = unitData;          

		          }

		          if (jQuery('#alt_subtotal').val() != '') {
		            data['alt_subtotal'] = jQuery('#alt_subtotal').val(); 
		          }

		          if (jQuery('#alt_shipping').val() != '') {
		            data['alt_shipping'] = jQuery('#alt_shipping').val(); 
		          }

		          if (jQuery('#alt_tax').val() != '') {
		            data['alt_tax'] = jQuery('#alt_tax').val(); 
		          }

		          if (jQuery('#alt_total').val() != '') {
		            data['alt_total'] = jQuery('#alt_total').val(); 
		          }


				jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
					jQuery('#myModalBody').html(response);
					jQuery('#ajaxResponse').html('Updated');
				});

			} else {

				alert('Please fill out all required fields');

			}

			return false;

		});

		jQuery('#myModalBody').on('hidden.bs.modal', function () {
		    location.reload();
		})



	});
	</script>
		<?php

		if (strpos($_SERVER['REQUEST_URI'], 'post.php?post=') === false) {
			return;
		}

		if (empty($post) || $post->post_type != 'shop_order') {
		     return;
		}



	}
}


add_action('wp_ajax_complete_order', 'silibas_complete_order_ajax');

function silibas_complete_order_ajax() {

	$response = '';

	$order_id = $_POST['orderID'];

	$my_post = array(
		'ID'            => $order_id,
		'post_status'   => 'wc-completed',
	);

	wp_update_post( $my_post );


	$order = wc_get_order(  $order_id );

	$note = __("Manually Completed via AJAX");

	// Add the note
	$order->add_order_note( $note );

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Status changed: Completed</p>';

	$trainingAdded = silbas_woocommerce_order_status_completed_gtw($order_id);

	if ($trainingAdded) {

		$note = __("User added to GoToTraining");

		// Add the note
		$order->add_order_note( $note );

		$response .= '<p>User added to training</p>';

	}

	//check that taxes are sent for quotes completed
	if (get_post_meta($order_id, '_wc_avatax_status', true) != 'posted') {
		 if (get_post_meta($order_id, '_order_tax', true) != '') {

		     if (get_post_meta($order_id, '_wc_avatax_tax_calculated', true) == 'yes') {

		        $wcTax = new WC_AvaTax_Order_Handler();
		        $result = $wcTax->process_order($order);

				$note = __("Completed with taxes sent");

				// Add the note
				$order->add_order_note( $note );

		        $response .= '<p>Taxes sent to Avalara</p>';

		     }

		 }
	}

	if (get_post_meta($order_id, '_payment_method', true) != 'authorize_net_aim') {

        $isCCorder = silibas_is_cc_paid($order_id);

        if (!$isCCorder) {
			//IF NOT CREDIT CARD ORDER
			silibas_auto_send_invoice_pdf($order_id);
			$response .= '<p><strong>EMAILED a/p copy of invoice</strong></p>';
		}

	}

	$response .= '<p><strong>Refresh page to view updates</strong></p>';

	echo $response;

	die();


}
add_action('wp_ajax_save_ordermetadata', 'silibas_save_ordermetadata_ajax');

function silibas_save_ordermetadata_ajax() {

	$response = '';

	$order_id = $_POST['orderID'];
	$order_status = $_POST['orderStatus'];
	$poNumber = $_POST['poNumber'];
	$ccNumber = $_POST['ccNumber'];
	$apEmail = $_POST[ 'apEmail' ];
	$region = $_POST[ 'region' ];
	$salesperson = $_POST[ 'salesperson' ];
	$businessData = $_POST[ 'businessData' ];
	$schooldistrict = $_POST[ 'schooldistrict' ];
	$schooldistrict_id = $_POST[ 'schooldistrict_id' ];
	$schoolname = $_POST[ 'schoolname' ];
	$schoolname_id = $_POST[ 'schoolname_id' ];

	update_post_meta($order_id, 'apEmail', $apEmail);
	update_post_meta($order_id, 'region', $region);

	update_post_meta($order_id, 'businessType', $businessData);
	update_post_meta($order_id, 'salesperson', $salesperson);

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Order Data Updated</p><p>Order status unchanged. Saved data below:</p>';

	$response .= '<p>PO: '.$poNumber.' </p>';
	$response .= '<p>a/p email: '.$apEmail.' </p>';
	$response .= '<p>region: '.$region.' </p>';
	$response .= '<p>save status: '.$order_status.' </p>';

	if ($order_status == 'wc-completed') {
		$response .= scg_update_new_district_names($order_id, $schooldistrict, $schoolname, $schooldistrict_id, $schoolname_id);
	} else {
		//all other order status can update/temp set the name

			update_post_meta($order_id, 'billing_school_name', $schoolname);
			update_post_meta($order_id, 'billing_school_name_id', $schoolname_id);
			$response .= '<p>Current school name (ID): '.$schoolname.' ('.$schoolname_id.')</p>';

			update_post_meta($order_id, 'billing_school_district_name', $schooldistrict);
			update_post_meta($order_id, 'billing_school_district_name_id', $schooldistrict_id);

			$response .= '<p>Current school district (ID): '.$schooldistrict.' ('.$schooldistrict_id.')</p>';
		
	}

	$response .= '<p>sales person: '.$salesperson.' </p>';
	$response .= '<p>new/existing: '.$businessData.' </p>';


//

	if ($ccNumber != '') {
		//transaction exists from credit card order
		//$response .= '<p>PO Number was not updated as Credit Card Transaction exists.</p>';
		$response .= '<p>Refresh page to view updates.</p>';

		update_post_meta($order_id, '_cc_transaction_id', $ccNumber);

	} else {
		update_post_meta($order_id, '_payment_method', 'alg_custom_gateway_1');
		update_post_meta($order_id, '_payment_method_title', 'Purchase Order');

		// $response .= '<p>Payment Method: Purchase Order</p>';
		// $response .= '<p>Purchase Order: '.$poNumber.'</p>';


	}
	
	update_post_meta($order_id, '_transaction_id', $poNumber);

	echo $response;

	die();
}



add_action('wp_ajax_approve_shipping', 'silibas_approve_shipping_ajax');

function silibas_approve_shipping_ajax() {

	//approve for shipping ajax approve_shipping

	$response = '';

	$order_id = $_POST['orderID'];
	$poNumber = $_POST['poNumber'];
	$ccNumber = $_POST['ccNumber'];
	$apEmail = $_POST[ 'apEmail' ];
	$region = $_POST[ 'region' ];
	$salesperson = $_POST[ 'salesperson' ];
	$businessData = $_POST[ 'businessData' ];

	$schooldistrict = $_POST[ 'schooldistrict' ];
	$schooldistrict_id = $_POST[ 'schooldistrict_id' ];
	$schoolname = $_POST[ 'schoolname' ];
	$schoolname_id = $_POST[ 'schoolname_id' ];


	$my_post = array(
		'ID'            => $order_id,
		'post_status'   => 'wc-approved-shipping',
	);

	//update order meta if applicable


	update_post_meta($order_id, 'apEmail', $apEmail);
	update_post_meta($order_id, 'region', $region);




	update_post_meta($order_id, 'businessType', $businessData);

	if ($_POST[ 'unitPrices' ] != '') {
		//has unit data to store
		update_post_meta($order_id, 'unitPricing', true);
		foreach($_POST[ 'unitPrices' ] as $pid => $unitPrice) {
			if ($unitPrice != '' || $unitPrice != 0) {
				update_post_meta($order_id, 'unit_'.$pid, $unitPrice);
			}
		}
	}

	if ($_POST[ 'alt_subtotal' ] != '') {
		update_post_meta($order_id, 'alt_subtotal', $_POST[ 'alt_subtotal' ]);
	}
	if ($_POST[ 'alt_shipping' ] != '') {
		update_post_meta($order_id, 'alt_shipping', $_POST[ 'alt_shipping' ]);
	}
	if ($_POST[ 'alt_tax' ] != '') {
		update_post_meta($order_id, 'alt_tax', $_POST[ 'alt_tax' ]);
	}
	if ($_POST[ 'alt_total' ] != '') {
		update_post_meta($order_id, 'alt_total', $_POST[ 'alt_total' ]);
	}



	wp_update_post( $my_post );

	$order = new WC_Order($order_id);

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Status: Approved for Shipping</p>';

	update_post_meta($order_id, 'salesperson', $salesperson);

	$response .= scg_update_new_district_names($order_id, $schooldistrict, $schoolname, $schooldistrict_id, $schoolname_id);

	if ($ccNumber != '') {

		//transaction exists from credit card order
		$response .= '<p>PO Number was not updated as Credit Card Transaction exists.</p>';
		$response .= '<p><strong>Refresh page to view updates. PO field will show Credit Card Transaction ID.</strong></p>';

	} else {

		update_post_meta($order_id, '_payment_method', 'alg_custom_gateway_1');
		update_post_meta($order_id, '_payment_method_title', 'Purchase Order');

		$response .= '<p>Payment Method: Purchase Order</p>';

		update_post_meta($order_id, '_transaction_id', $poNumber);

		$response .= '<p>Purchase Order: '.$poNumber.'</p>';

		$order = wc_get_order( $order_id );
		
		WC()->mailer()->emails['WC_Email_New_Order']->trigger( $order->get_id(), $order );
		


	    $document_type = 'invoice';

	    $document = wcpdf_get_document('invoice', $order, true);

	    $invoice = wcpdf_get_invoice( $order, true );

		$response .= '<p>Admin Email Sent</p>';
		$response .= '<p><strong>Invoice Generated</strong></p>';
		$response .= '<p><strong>Refresh page to view updates</strong></p>';

	}

	$order = wc_get_order(  $order_id );
	$note = __("Order moved to awaiting shipment via ordermanager.");
	$order->add_order_note( $note );
	
	
	echo $response;

	die();

}


add_action('wp_ajax_move_invoiced', 'silibas_move_invoiced_ajax');

function silibas_move_invoiced_ajax() {

	global $woocommerce;

	$response = '';

	$order_id = $_POST['orderID'];
	
	$my_post = array(
		'ID'            => $order_id,
		'post_status'   => 'wc-invoiced',
	);

	wp_update_post( $my_post );

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Status: INVOICED</p>';



	if (get_post_meta($order_id, '_wc_avatax_status', true) != 'posted') {
		 if (get_post_meta($order_id, '_order_tax', true) != '') {

		     if (get_post_meta($order_id, '_wc_avatax_tax_calculated', true) == 'yes') {
		        $order = new WC_Order($order_id);

		        $wcTax = new WC_AvaTax_Order_Handler();
		        $result = $wcTax->process_order($order);

		        $response .= '<p>Taxes sent to Avalara</p>';

		     }

		 }

	}

	$response .= '<p><strong>Refresh page to view updates</strong></p>';
	
	echo $response;

	die();

}

add_action('wp_ajax_move_closed', 'silibas_move_closed_ajax');

function silibas_move_closed_ajax() {

	$response = '';

	$order_id = $_POST['orderID'];

	$my_post = array(
		'ID'            => $order_id,
		'post_status'   => 'wc-closed-lost',
	);

	wp_update_post( $my_post );

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Status: CLOSED / LOST</p>';

	$response .= '<p><strong>Refresh page to view updates</strong></p>';
	
	echo $response;

	die();

}


add_action('wp_ajax_move_review', 'silibas_move_review_ajax');

function silibas_move_review_ajax() {

$response = '';

	$order_id = $_POST['orderID'];
	$apEmail = $_POST[ 'apEmail' ];
	$region = $_POST[ 'region' ];
	$poNumber = $_POST[ 'poNumber' ];
	$salesperson = $_POST[ 'salesperson' ];
	$businessData = $_POST[ 'businessData' ];

	$schooldistrict = $_POST[ 'schooldistrict' ];
	$schooldistrict_id = $_POST[ 'schooldistrict_id' ];
	$schoolname = $_POST[ 'schoolname' ];
	$schoolname_id = $_POST[ 'schoolname_id' ];

	$my_post = array(
		'ID'            => $order_id,
		'post_status'   => 'wc-order-review',
	);

	wp_update_post( $my_post );

	update_post_meta($order_id, 'apEmail', $apEmail);
	update_post_meta($order_id, 'region', $region);
	update_post_meta($order_id, 'salesperson', $salesperson);

	update_post_meta($order_id, 'businessType', $businessData);

	if ($poNumber != '') {
		update_post_meta($order_id, '_transaction_id', $poNumber);
	}

	$response .= '<h3>Order '.$order_id.' <h3>';
	$response .= '<p>Status: REVIEW REQUESTED</p>';
	$response .= '<p>ap data updated</p>';

	if ($order_status == 'wc-completed' || $order_status == 'wc-approved-shipping') {
		$response .= scg_update_new_district_names($order_id, $schooldistrict, $schoolname, $schooldistrict_id, $schoolname_id);
	} else {
		//billing_school_name
		if ($schoolname_id == '' || $schoolname_id == 0) {
			update_post_meta($order_id, 'billing_school_name', $schoolname);
			update_post_meta($order_id, 'billing_school_name_id', $schoolname_id);
			$response .= '<p>Current school name updated: '.$schoolname.' ('.$schoolname_id.')</p>';
		}
		//if ($schooldistrict_id == '' || $schooldistrict_id == 0) {
			update_post_meta($order_id, 'billing_school_district_name', $schooldistrict);
			update_post_meta($order_id, 'billing_school_district_name_id', $schooldistrict_id);

			$response .= '<p>Current school district updated: '.$schooldistrict.' ('.$schooldistrict_id.')</p>';
		//}

	}

	$response .= '<p><strong>Refresh page to view updates</strong></p>';
	
	echo $response;

	die();

}

function scg_update_new_district_names($order_id, $schooldistrict, $schoolname, $schooldistrict_id, $schoolname_id) {

	$schoolResp = '<p>';

	// $schoolResp .= '['.$order_id .' - '.$schooldistrict.' - '.$schoolname.' - '.$schooldistrict_id.' - '.$schoolname_id.']';

	//if schooldistrict ids/names create new posts if applicable
	if (!is_numeric($schooldistrict_id) || $schooldistrict_id == 0 || $schooldistrict_id == '0') {
		//create distirct post
		$district_args = array(
			'post_type' => 'district',
			'post_name' => sanitize_title($schooldistrict),
			'post_title'    => $schooldistrict,
			'post_status'   => 'publish',
		);

		$schooldistrict_id = wp_insert_post($district_args);

		$schoolResp .= '<span style="color:#b20000;">New District Added ('.$schooldistrict.')</span><br>';

		scg_cache_districts();
	}

	//if schooldistrict ids/names create new posts if applicable
	if (!is_numeric($schoolname_id)) {
		//create school term/taxonomy and attach to post
		$schoolterm  = get_term_by('name', $schoolname, 'schoolname');
		
		if ($schoolterm == false){

			$term = wp_insert_term($schoolname, 'schoolname');

    		$term_id = $term['term_id'] ;

			$schoolResp .= '<span style="color:#b2000;">New School Added ('.$schoolname.')</span><br>';

    		scg_cache_schools();

		} else {
			$term_id = $schoolterm->term_id ;
		}

		
		$schoolname_id = $term_id;

	}

	wp_set_post_terms( $schooldistrict_id, $term_id, 'schoolname', true);

	update_post_meta($order_id, 'billing_school_district_name', $schooldistrict);
	update_post_meta($order_id, 'billing_school_name', $schoolname);
	update_post_meta($order_id, 'billing_school_district_name_id', $schooldistrict_id);
	update_post_meta($order_id, 'billing_school_name_id', $schoolname_id);

	$schoolResp .= 'School Info Saved: '.$schooldistrict . ' - ' .$schoolname.'</p>';

	return $schoolResp;	

}

function silibas_get_processing_notification_content( $order, $heading = false, $mailer ) {
 
    $template = 'emails/customer-completed-order.php';
 
    return wc_get_template_html( $template, array(
        'order'         => $order,
        'email_heading' => $heading,
        'sent_to_admin' => true,
        'plain_text'    => false,
        'email'         => $mailer
    ) );
}

function silibas_is_shop_mananer() {
	
	global $current_user;

	$user_roles = $current_user->roles;
	
	$user_role = array_shift($user_roles);

	if ($user_role == 'estrellitamanager' ) {
		return true;
	} else {
		return false;
	}

}



function wpturbo_disable_metabox_dragging() {
	if (silibas_is_shop_mananer()) {
    	wp_deregister_script( 'postbox' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpturbo_disable_metabox_dragging' );


function silibas_is_shop_mananer_alt() {
	
	global $current_user;

	$user_roles = $current_user->roles;
	
	$user_role = array_shift($user_roles);

	if ($user_role == 'estrellitamanager' || $user_role == 'administrator' ) {
		return true;
	} else {
		return false;
	}

}




// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
if ( ! function_exists( 'mv_add_meta_boxes' ) )
{
    function mv_add_meta_boxes()
    {
        add_meta_box( 'mv_other_fields', __('Order Management','woocommerce'), 'mv_add_other_fields_for_packaging', 'shop_order', 'normal', 'high' );
    }
}

add_action( 'add_meta_boxes', 'mv_add_meta_boxes_unit_pricing' );
if ( ! function_exists( 'mv_add_meta_boxes_unit_pricing' ) )
{
    function mv_add_meta_boxes_unit_pricing()
    {
        add_meta_box( 'mv_unit_fields', __('Alternate Invoice Pricing','woocommerce'), 'silibas_add_unit_pricing', 'shop_order', 'normal', 'high' );
    }
}


function bulk_pd_order($order_id) {

	$order = new WC_Order($order_id);

	$hasPD = false;

	foreach( $order->get_items() as $item_id => $item ){
	    //Get the product ID
	    $product_id = $item->get_product_id();

	    if (silibas_is_variable_product($product_id)) {
	    	$hasPD = true;
	    }
	    //$variation_id = $item->get_variation_id();

	}

}


function silibas_auto_send_invoice_pdf($order_id) {

	if (!get_post_meta($order_id, 'sent_pdf', true)) {

		$mailto = get_post_meta($order_id, 'apEmail', true);

		$order = new WC_Order($order_id);

	    $document_type = 'invoice';

	    $document = wcpdf_get_document('invoice', $order, true);

	    $invoice = wcpdf_get_invoice( $order, true );

	    $filename = $document->get_filename();

	    $msg = 'invoice attached';
		$attachments = array(realpath($_SERVER["DOCUMENT_ROOT"]).'/silibas-invoices/attachments/'.$filename);

		//set error catch if file doesn't exist
		$headers = 'From: Estrellita <info@estrellita.com>' . "\r\n";
		$headers .= 'Content-type: text/html';

		if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).'/silibas-invoices/attachments/'.$filename)) {
			$mailRecipients = array();
			$mailRecipients[] = $mailto;

			@wp_mail( $mailRecipients, 'Estrellita Invoice ', $msg, $headers, $attachments );

			// The text for the note
			$note = __("Invoice emailed to user: ".$mailto." with PDF attached ");

			// Add the note
			$order->add_order_note( $note );

			update_post_meta($order_id, 'sent_pdf', true);
		} else {

            $isCCorder = silibas_is_cc_paid($order_id);

            // $order_retries = [];

            if (!$isCCorder) {	}



		}


	}

	return 'run' . get_post_meta($order_id, 'sent_pdf', true);

}


//silibas_send_ap_invoice 
add_action('wp_ajax_silibas_send_ap_invoice', 'silibas_send_ap_invoice_callback');
add_action('wp_ajax_nopriv_silibas_send_ap_invoice', 'silibas_send_ap_invoice_callback' );


function silibas_send_ap_invoice_callback() {

	$mailto = $_POST[ 'mailto' ];
	$filename = $_POST[ 'filename' ];
	$order_id = $_POST[ 'order_id' ];

    $msg = 'invoice attached';
	$attachments = array(realpath($_SERVER["DOCUMENT_ROOT"]).'/silibas-invoices/attachments/'.$filename);

	$mailRecipients = array();
	$mailRecipients[] = $mailto;

	$headers = 'From: Estrellita <info@estrellita.com>' . "\r\n";
	$headers .= 'Content-type: text/html';

	$emailTitle = 'Estrellita Invoice ' . $order_id;
	@wp_mail( $mailRecipients, $emailTitle, $msg, $headers, $attachments );

	$response = '<p>Email sent to '.$mailto.' completed</p>';

	$order = new WC_Order($order_id);

	//$order->update_status( 'completed' );

	if (get_post_meta($order_id, '_wc_avatax_status', true) != 'posted') {
		 if (get_post_meta($order_id, '_order_tax', true) != '') {

		     if (get_post_meta($order_id, '_wc_avatax_tax_calculated', true) == 'yes') {
		        $order = new WC_Order($order_id);

		        $wcTax = new WC_AvaTax_Order_Handler();
		        $result = $wcTax->process_order($order);

		        $response .= '<p>Taxes sent to Avalara</p>';

		     }

		 }

	}

	$response .= '<p>Refresh page to see status updated to <strong>Completed</strong> and sent to A/P Email</p>';

	echo $response;

	die();
}

add_action('wp_ajax_silibas_completed_update_order_meta', 'silibas_completed_update_order_meta_callback');
add_action('wp_ajax_nopriv_silibas_completed_update_order_meta', 'silibas_completed_update_order_meta_callback' );


function silibas_completed_update_order_meta_callback() {

	$order_id = $_POST[ 'postID' ];
	$apEmail = $_POST[ 'apEmail' ];
	$region = $_POST[ 'region' ];
	$poNumber = $_POST[ 'poNumber' ];
	$salesperson = $_POST[ 'salesperson' ];
	$businessData = $_POST[ 'businessData' ];

	$schooldistrict = $_POST[ 'schooldistrict' ];
	$schooldistrict_id = $_POST[ 'schooldistrict_id' ];
	$schoolname = $_POST[ 'schoolname' ];
	$schoolname_id = $_POST[ 'schoolname_id' ];

	update_post_meta($order_id, 'apEmail', $apEmail);
	update_post_meta($order_id, 'region', $region);
	update_post_meta($order_id, '_transaction_id', $poNumber);
	update_post_meta($order_id, 'salesperson', $salesperson);
	update_post_meta($order_id, 'businessType', $businessData);

	if ($_POST[ 'unitPrices' ] != '') {
		//has unit data to store
		update_post_meta($order_id, 'unitPricing', true);
		foreach($_POST[ 'unitPrices' ] as $pid => $unitPrice) {
			if ($unitPrice != '' || $unitPrice != 0) {
				update_post_meta($order_id, 'unit_'.$pid, $unitPrice);
			}
		}
	}

	if ($_POST[ 'alt_subtotal' ] != '') {
		update_post_meta($order_id, 'alt_subtotal', $_POST[ 'alt_subtotal' ]);
	}
	if ($_POST[ 'alt_shipping' ] != '') {
		update_post_meta($order_id, 'alt_shipping', $_POST[ 'alt_shipping' ]);
	}
	if ($_POST[ 'alt_tax' ] != '') {
		update_post_meta($order_id, 'alt_tax', $_POST[ 'alt_tax' ]);
	}
	if ($_POST[ 'alt_total' ] != '') {
		update_post_meta($order_id, 'alt_total', $_POST[ 'alt_total' ]);
	}


	$response .= scg_update_new_district_names($order_id, $schooldistrict, $schoolname, $schooldistrict_id, $schoolname_id);


	echo $response;

	die();

}

add_action( 'woocommerce_order_status_completed','silibas_order_is_completed' );

function silibas_order_is_completed($order_id) {
	//woocommerce_order_status_completed

	$order = new WC_Order($order_id);
	$note = __("Completed with status change functions");
	// Add the note
	$order->add_order_note( $note );


	if (get_post_meta($order_id, '_last_completed', true) == '') {

		$date_fmt = 'd/m/Y';
		$modified_time = get_post_modified_time( $date_fmt, null, $order_id );
		update_post_meta($order_id, '_last_completed', $modified_time);

	}

	if (get_post_meta($order_id, '_wc_avatax_status', true) != 'posted') {

		 if (get_post_meta($order_id, '_order_tax', true) != '') {

		     if (get_post_meta($order_id, '_wc_avatax_tax_calculated', true) == 'yes') {

		        $wcTax = new WC_AvaTax_Order_Handler();
		        $result = $wcTax->process_order($order);

				$note = __("Completed with taxes sent (shopmanager:1200)");

				// Add the note
				$order->add_order_note( $note );

		        $response .= '<p>Taxes sent to Avalara</p>';

		     }

		 }

	}

	if (get_post_meta($order_id, '_payment_method', true) != 'authorize_net_aim') {
		//IF NOT A CREDIT CARD ORDER
		silibas_auto_send_invoice_pdf($order_id);

		$response .= '<p><strong>EMAILED a/p copy of invoice</strong></p>';

		if (silibas_is_cc_paid($order_id)) {
			update_post_meta($order_id, 'order_type', 'PO');
		} else {
			update_post_meta($order_id, 'order_type', 'CC');
		}

	} else {
		update_post_meta($order_id, 'order_type', 'CC');
	}

}


function silibas_is_cc_paid($order_id) {

	$isCC = false;

	if (!empty(get_post_meta($order_id, 'cc_order', true))) {
		
		$isCC = true;

	} else {	

	    $comments = silibas_custom_get_order_notes($order_id);

	    foreach($comments as $comment) {
	        if (strpos($comment, 'Charge Approved:') !== false) {
	            update_post_meta($order_id, 'cc_order', true);
	            $isCC = true;    
            
	        }
	    }   

	}


    return $isCC;

}


function silibas_is_cc_order($order_id) {

	$isCC = false;
	$ccCode = '';
	$msg = '<p>Credit Card Order: <strong>Paid</strong></p>';

	if (!empty(get_post_meta($order_id, 'cc_order', true))) {
		
		$isCC = true;
		$ccCode = $msg;

	} else {	

	    $comments = silibas_custom_get_order_notes($order_id);

	    foreach($comments as $comment) {
	        if (strpos($comment, 'Charge Approved:') !== false) {
	            update_post_meta($order_id, 'cc_order', true);
	            $isCC = true;    
	            $ccCode = $msg;
            
	        }
	    }   

	}

	update_post_meta($order_id, 'cc_log', $order_id);

    return $ccCode;

}

function siliabs_can_ordermanager_edit($order_id) {

	$user_id = get_current_user_id();

	if ($user_id == 80) {
		$status = get_post_status($order_id);
		
		$blockStatusChanges = array(
			'wc-approved-shipping',
			'wc-completed',
			'wc-closed-lost',
			'wc-failed',
			'wc-refunded',
			'wc-cancelled',
			'wc-proposal-expired',
		);

		if (in_array($status, $blockStatusChanges)) {
			return 'disabled';
		}

	} else {

	}

	return '';

}



// function save_post($post_id) {
//     // Insert some actual logic to ensure you're not doing this on every post all the time

//     update_post_meta($post_id, 'i_am_saved', 'totes saved to post #' . $post_id);
// }

function siliabs_order_addon_purchase_order($post_id) {

	$disabled = siliabs_can_ordermanager_edit($post_id);
	$status = '';

	$purchaseOrder = '<div class="om_addon po_data">';
	//if credit card, lock in PO otherwise, be editable/addable
		$poVal = get_post_meta($post_id, '_transaction_id', true);
		$purchaseOrder .= '<label for="poNumber">Purchase Order Number:<span style="color:#b20000;font-weight:900;">*</span></label> <input class="'.$status.'" id="poNumber" type="text" placeholder="12343" value="'.$poVal.'" '.$disabled.'>';
	$purchaseOrder .= '</div>';

	return $purchaseOrder;


}
function silibas_order_addon_business($post_id) {
	//New Business or Existing business
	$disabled = siliabs_can_ordermanager_edit($post_id);

	$businessType = get_post_meta($post_id, 'businessType', true);
	$newCheck = '';
	$exisCheck = '';

	if ($businessType == 'new') {
		$newCheck = 'checked';
	} elseif ($businessType == 'existing') {
		$exisCheck = 'checked';
	}

   	$businessData = '<div class="om_addon business_data">';
   		$businessData .= '<p>New/Existing Business (please select one below)<span style="color:#b20000;font-weight:900;">*</span></p>';

		$businessData .= '<input type="radio" id="new" name="businessData" value="new" '.$newCheck.' '.$disabled.'>';

		$businessData .= '<label for="new">New Business</label><br>';

		$businessData .= '<input type="radio" id="existing" name="businessData" value="existing" '.$exisCheck.' '.$disabled.'>';

		$businessData .= '<label for="existing">Existing Business</label>';
	$businessData .= '</div>';

	return $businessData;
}


function silibas_order_addon_ap_email($post_id) {

	$disabled = siliabs_can_ordermanager_edit($post_id);

	$apSaved = get_post_meta($post_id, 'apEmail', true);

   	$isCCorder = silibas_is_cc_paid($post_id);

   	if ($isCCorder) {
   		if ($apSaved == '') {
   			$apSaved = 'none';
   			//update_post_meta($post_id, 'apEmail', 'none');
   		}
   	}  	
	$apEmail = '';

   	$apEmail .= '<div class="om_addon ap_data">';
   		$apEmail .= '<label for="apEmail">A/P Email Address:<span style="color:#b20000;font-weight:900;">*</span></label> <input id="apEmail" type="text" value="'.$apSaved.'" '.$disabled.'>';
	$apEmail .= '</div>';

	return $apEmail;

}


function silibas_order_addon_region($post_id) {

	$disabled = siliabs_can_ordermanager_edit($post_id);

   	$userRegion = '<div class="om_addon region_data">';
   		$userRegion .= '<label for="region">Region/County<span style="color:#b20000;font-weight:900;">*</span></label> <input id="region" type="text" value="'.get_post_meta($post_id, 'region', true).'" '.$disabled.'>';
	$userRegion .= '</div>';

	return $userRegion;

}

function silibas_order_addon_salesperson($post_id) {

	$disabled = siliabs_can_ordermanager_edit($post_id);

   	$userRegion = '<div class="om_addon salesperson_data">';
   		$userRegion .= '<label for="salesperson">Salesperson<span style="color:#b20000;font-weight:900;">*</span></label> <input id="salesperson" type="text" value="'.get_post_meta($post_id, 'salesperson', true).'" '.$disabled.'>';
	$userRegion .= '</div>';

	return $userRegion;

}

function silibas_order_addon_school_district($post_id) {

	$district_cached_data = maybe_unserialize(get_option('district_data'));

	$disabled = siliabs_can_ordermanager_edit($post_id);

	$wrapClass = 'schooldistrict_data';
	
   	$order = new WC_Order($post_id);

   	$districtName = get_post_meta($post_id, '_billing_school_district', true);
   	$districtNamev2 = get_post_meta($post_id, 'billing_school_district_name', true);
   	$districtID = get_post_meta($post_id, 'billing_school_district_name_id', true);

   	if ($districtName == '') {
   		// $districtName = $order->get_billing_company();
   	}	
   	if ($districtID != '') {
   		if ($districtID != '0') {
	   		$disabled = 'disabled';
	   		$wrapClass .= ' presentData';
	   	} else {
	   		$wrapClass .= ' newData';	
   		}
   	}  else {
   		$wrapClass .= ' newData';
   	}

   	$userRegion = '<div class="om_addon '.$wrapClass.'">';

	$userRegion .= '<label for="schoolDistrict">School District<span style="color:#b20000;font-weight:900;">*</span>';
	
	if ($disabled != 'disabled') {
   		if ((current_user_can('manage_options'))) {
   			$userRegion .= '<a href="#" class="unlock-sd"><span class="dashicons dashicons-unlock"></span></a>';
   		}
   	}

   	$userRegion .= '</label>';

	$userRegion .= '<select id="district-select" class="dis-'.$districtID.'" '.$disabled.'>';

	if ($districtID == '' || $districtID == '0') {
		if ($districtNamev2 != '') {
			$userRegion .= '<option value="0">'.$districtNamev2.'</option>';
		} else {
			$userRegion .= '<option value="0">Select District</option>';
		}
	}

	foreach($district_cached_data as $d_id => $district_name) {
		if ($districtID == $d_id) {
			$userRegion .= '<option class="'.$d_id.'" value="'.$d_id.'" selected>'.$district_name.'</option>';
		} else {
			$userRegion .= '<option value="'.$d_id.'">'.$district_name.'</option>';
		}
	}

	$userRegion .= '</select>';
	if (get_post_meta($post_id, '_billing_school_district', true) != '' && $districtID == '') {
		$userRegion .= '&nbsp; &nbsp;<span>Replace...<strong>'.get_post_meta($post_id, '_billing_school_district', true).'</strong></span>';
	}

	$userRegion .= '<input id="district-id" type="text" value="'.$districtID.'" style="display:none;">';
	$userRegion .= '';
	$userRegion .= '';

	$userRegion .= '</div>';

	return $userRegion;

}

function scg_fuzzy_match_district($title) {

	$myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'district' AND post_title LIKE '%s'", '%'. $wpdb->esc_like( $title ) .'%') );

	return $myposts[0]->ID;

}


function silibas_order_addon_school_name($post_id) {

	$disabled = siliabs_can_ordermanager_edit($post_id);

	$wrapClass = 'schoolname_data';
	$school_name = '';
	$school_namev2 = get_post_meta($post_id, 'billing_school_name', true);
   	$school_ID = get_post_meta($post_id, 'billing_school_name_id', true);
   	$districtID = get_post_meta($post_id, 'billing_school_district_name_id', true);

   	if ($school_ID != '') {
   		$disabled == 'disabled';
   		$wrapClass .= ' presentData';
   	} else {
   		$wrapClass .= ' newData';
   	}

   	$userRegion = '<div class="om_addon '.$wrapClass.'">';

   		$userRegion .= '<label for="schoolName">School Name<span style="color:#b20000;font-weight:900;">*</span>';
   		//$userRegion .= '<input id="schoolName" type="text" value="'.$school_name.'" '.$disabled.'>';

   	if ($disabled != 'disabled') {

   		//if user is admin, ability to unlock settings
   		if ((current_user_can('manage_options'))) {
   			$userRegion .= '<a href="#" class="unlock-sd"><span class="dashicons dashicons-unlock"></span></a>';
   		}

   	}

   		$userRegion .= '</label>';


	
	// if (get_post_meta($post_id, '_billing_school_name', true) != '') {
	// 	$userRegion .= '<span>Replace... '.get_post_meta($post_id, '_billing_school_name', true).'</span>';
	// }

	$userRegion .= '<select id="school-select" class="dis-" data-schoolid="'.$school_ID.'" data-districtid="'.$districtID.'" style="display:none;" '.$disabled.'>';
   
	if ($school_ID == '') {

		$userRegion .= '<option value="0">'.$school_namev2.'</option>';

		if ($districtID != '') {
			$school_data = scg_get_disrict_school_names($districtID);
			foreach($school_data as $term_id => $name) {
				$userRegion .= '<option value="'.$term_id.'">'.$name.'</option>';
			}
		}

	} else {

		$term = get_term_by( 'id', $school_ID, 'schoolname' ); 

		$userRegion .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
		
		if ($districtID != '') {
			
			$school_data = scg_get_disrict_school_names($districtID);

			foreach($school_data as $term_id => $name) {

				if ($term_id != $school_ID) {
					$userRegion .= '<option value="'.$term_id.'">'.$name.'</option>';
				}

			}

		}

	}

	$userRegion .= '</select>';
	$userRegion .= '<input id="school-id" type="text" value="'.	$school_ID.'" style="display:none;">';
	$userRegion .= '</div>';

	return $userRegion;

}



add_action('admin_footer', 'test_scg_select2');

function test_scg_select2() {
?>
<script>
jQuery(document).ready(function($) {

    $("#district-select").select2({
	  tags: true
	});

	$("#district-select").on("change", function (e) {
		//enable if disabled school name
		$("#school-select").prop("disabled", false);
		$('#school-id').val('');
		//show spinner
		spinner_init('show');

		var districtID = $("#district-select :selected").val();

		if ($.isNumeric(districtID)) {

			$(".schooldistrict_data").removeClass('newData').addClass('presentData');

			//ajax to get available schools
			$('#district-id').val(districtID);
			//reset schools
			$("#school-select option").each(function() {
			    $(this).remove();
			});

			$('#school-select').append('<option value="0">Select School</option>');

			var updateMetaData = {
				'action':'scg_get_schools',
				'post_id': districtID
			};

			jQuery.post("/wp-admin/admin-ajax.php", updateMetaData, function(apResp, status) {

				const data = JSON.parse(apResp);

				$.each(data, function(key, value) {
		            $('#school-select').append('<option value="'+key+'">'+value+'</option>');

		        });
				//remove spinner
				spinner_init('complete');
			});
		} else {
			//add new district
			$(".schooldistrict_data").removeClass('presentData').addClass('newData');

			$("#district-select :selected").val('0');
			$("#district-id").val('');

			console.log('update sel val ' + $("#district-id").val());

			//confirm new district to add
			$('.schooldistrict_data .select2-selection__rendered').css('border', '1px solid #b2000');

			var updateMetaData = {
				'action':'scg_get_schools',
				'post_id': '0'
			};

			jQuery.post("/wp-admin/admin-ajax.php", updateMetaData, function(apResp, status) {
								
				const data = JSON.parse(apResp);
				
				$.each(data, function(key, value) {
		            $('#school-select').append('<option value="'+key+'">'+value+'</option>');
		        });

				//remove spinner
				spinner_init('complete');

			});


		}
		
		spinner_init('complete');

		$('#school-select').fadeIn();
		
	});

    $("#school-select").select2({
	  tags: true
	});

	$("#school-select").on("change", function (e) {

		var schoolID = $("#school-select :selected").val();
		$('#school-id').val(schoolID);

		if (isNumeric(schoolID) && schoolID != 0) {
			$(".schoolname_data").removeClass('newData').addClass('presentData');
		} else {
			$(".schoolname_data").removeClass('presentData').addClass('newData');
		}

	});

	//if school name change
	function isNumeric(str) {
	  return !isNaN(str) && !isNaN(parseFloat(str));
	}

	//school-ID
	function spinner_init(status) {
		if (status == 'show') {
			$('#mv_other_fields').addClass('enqueue');
			$('#mv_other_fields').prepend('<div id="spinning"><div class="spin-container"><img src="/wp-content/plugins/estrellita-toolkit/img/1488.gif" /></div></div>');
		} else {
			$('#mv_other_fields').removeClass('enqueue');
			$('#mv_other_fields #spinning').remove();
		}
	}

	$(".unlock-sd").on("click", function (e) {
		$('.unlock-sd').fadeOut();
		$("#district-select").prop("disabled", false);
		$("#school-select").prop("disabled", false);
	});
	
});


</script>
<?php
}




function silibas_order_edit_metadata($post_id) {
	$disabled = siliabs_can_ordermanager_edit($post_id);
	$editbtn = '';

	if ($disabled != 'disabled') {
		$editbtn .= '<div class="editmeta">';
		$editbtn .= '<a href="#" class="button button-primary btn saveOrder" data-postid="'.$post_id.'">Save Data</a>';
		$editbtn .= '</div>';
	}
	
	return $editbtn;

}

function silibas_order_addon_tax_edit() {

	$taxEdit = '';

	return $taxEdit;

}

function silibas_order_addon_unit_price() {

	$unitPrice = '';


	return $unitPrice;

}

function silibas_order_addon_action_closed($post_id) {

	return ' <a href="#" class="button button-primary btn btn-approve closedLost" data-postID="'.$post_id.'" style="float:right;">Closed / Lost</a>';

}

function silibas_order_addon_action_review($post_id) {

	return '<a href="#" class="button button-primary btn btn-approve orderReview" data-postID="'.$post_id.'">Request Approval</a>';

}

function silibas_order_addon_action_approve_shipping($post_id) {

	return '<a href="#" class="button button-primary btn btn-approve approveShipping" data-postID="'.$post_id.'">Approve for Shipping</a>';

}


function silibas_order_addon_css() {

	$styles = '';
	$styles .= '<style>';
	$styles .= '.om_addon label {width:200px;}.om_addon {margin-bottom:10px;}';
	$styles .= '.om_addon .select2 {min-width:400px;margin-left:3px;}';
	$styles .= '</style>';

	return $styles;
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'silibas_add_unit_pricing' ) )
{
    function silibas_add_unit_pricing()
    {
    	global $post;

    	echo '<div class="unitPricingContainer">';
		$order = new WC_Order($post->ID);

		echo '<p>Please input unit pricing below if applicable. Include any formatting as you want it to appear on invoices.</p><hr>';

		$i = 0;
		foreach( $order->get_items() as $item_id => $item ){
		    //Get the product ID
		    $product_id = $item->get_product_id();

		    echo '<p><span>Product: '.get_the_title($product_id) .'</span> <span>$<input data-productid="'.$product_id.'" id="unit_'.$product_id.'" class="unitPrice unit_'.$i.'" type="text" value="'.get_post_meta($post->ID, 'unit_'.$product_id, true).'"></span></p><div style="clear:both"></div>';
		    $i++;
		}

		echo '<hr><p><span>Subtotal:</span><span>$<input id="alt_subtotal" class="alt_orderdata" type="text" value="'.get_post_meta($post->ID, 'alt_subtotal', true).'"></span></p><div style="clear:both"></div>';

		echo '<p><span>Shipping:</span><span>$<input id="alt_shipping" class="alt_orderdata" type="text" value="'.get_post_meta($post->ID, 'alt_shipping', true).'"></span></p><div style="clear:both"></div>';

		echo '<p><span>Tax:</span><span>$<input id="alt_tax" class="alt_orderdata" type="text" value="'.get_post_meta($post->ID, 'alt_tax', true).'"></span></p><div style="clear:both"></div>';

		echo '<p><span>Total:</span><span>$<input id="alt_total" class="alt_orderdata" type="text" value="'.get_post_meta($post->ID, 'alt_total', true).'"></span></p><div style="clear:both"></div>';


		echo '<p><em>User is responsable for the order totals above to be calculated accurately when overriding order data.</em></p>';



		echo '</div>';









    }

}


// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'mv_add_other_fields_for_packaging' ) )
{
    function mv_add_other_fields_for_packaging()
    {
        global $post;

        $hasBulkPD = false;

        $action = '';
        $humanStatus = '';

        $pendingStatus = array(
        	'wc-order-review',
        	);

        $prependingStatus = array(
        	'wc-processing',
        	'wc-proposal-sent',
        	'wc-proposal-expired',
        	'wc-proposal-accepted',
        	'wc-quote-sent',
        	);


        $postStatus = $post->post_status;

        if (bulk_pd_order($post->ID)) {
        	$hasBulkPD = true;
        }

        //add order meta data for any status
        $action .= silibas_is_cc_order($post->ID);
		$action .= siliabs_order_addon_purchase_order($post->ID);
        $action .= silibas_order_addon_ap_email($post->ID);
        $action .= silibas_order_addon_region($post->ID);
        $action .= silibas_order_addon_salesperson($post->ID);
        $action .= silibas_order_addon_school_district($post->ID);
        $action .= silibas_order_addon_school_name($post->ID);
        $action .= silibas_order_addon_business($post->ID);
        $action .= silibas_order_edit_metadata($post->ID);


        if (in_array($postStatus, $pendingStatus)) {

        	$action .= '<hr style="display:block;width:100%;clear:both;"><p>Status changes</p>';

        	$action .= silibas_order_addon_action_approve_shipping($post->ID);

			if (estrellita_has_pd_bulk($post->ID)) {
        		$action .= '<div style="text-align:right">';
        		$action .= '<a href="/wp-admin/tools.php?page=gtw-registration&order='.$post->ID.'" class="button btn bulkRegistration" data-postID="'.$post->ID.'" target="_blank">Bulk Registration</a>';
        		$action .= '</div>';
        	}

        	if (estrellita_has_pd_product($post->ID) || estrellita_has_ds_pd_product($post->ID)) {

        		$action .= '<hr>';
        		$action .= '<div style="text-align:right">';
        		$action .= '<p> ';      		
        		$action .= '<a href="#" class="button btn completeOrder" data-postID="'.$post->ID.'">Complete Order</a>';
        		$action .= '</p></div><hr>';

        	}

        	if (order_contains_membership_only($post->ID)) {
        		$action .= '<hr>';
        		$action .= '<div style="text-align:right">';
        		$action .= '<p> ';
        		$action .= '<a href="#" class="button btn completeOrder" data-postID="'.$post->ID.'">Complete Order</a>';
        		$action .= '</p></div><hr>';
        	}

        } else {
        	//non pending orders

        	if (in_array($postStatus, $prependingStatus)) {
        		// move to approval first
        		$action .= silibas_order_addon_action_review($post->ID);

        	} else {

		        if ($postStatus == 'wc-approved-shipping') {

					$humanStatus = '<strong>Approved for Shipping</strong><p>Purcahse Order: '.get_post_meta($post->ID, '_transaction_id', true).'</p>';
	        		$action .= '<hr>';

	        		$action .= '<div style="text-align:right">';
	        		$action .= '<p> ';
	        		$action .= '<a href="#" class="button btn completeOrder" data-postID="'.$post->ID.'">Complete Order</a>';
	        		$action .= '</p></div><hr>';
	        	}
	        	
	        	//$action .= silibas_invoice_pdf($post->ID);

        	}

        }

        if ($postStatus == 'wc-quote-sent') {
         	$action .= silibas_order_addon_action_closed($post->ID);
        }

        $action .= silibas_order_addon_css();

        echo '<p>Status: <span id="current-status">'.$postStatus.'</span> <span id="ajaxResponse"> '.$humanStatus.' '.$action.'</span></p>';

    }

}

function silibas_invoice_pdf($email_order_id) {

	$order = new WC_Order($email_order_id);

    $document_type = 'invoice';

    $document = wcpdf_get_document('invoice', $order, true);

    $invoice = wcpdf_get_invoice( $order, true );

    $filename = $document->get_filename();

    $pdf_data = $document->get_pdf();

    $pdf_path = trailingslashit(realpath($_SERVER["DOCUMENT_ROOT"])).'silibas-invoices/attachments/' . $filename;

    file_put_contents ( $pdf_path, $pdf_data ); 

	$pdf_url = add_query_arg( array(
        'action'        => 'generate_wpo_wcpdf',
        'document_type' => 'invoice',
        'order_ids'     => $order->get_id(),
        'order_key'     => $order->get_order_key(),
    ), admin_url( 'admin-ajax.php' ) );

    $link_text = '';

    $text = sprintf( '<p><a href="%s" target="_blank">%s</a></p>', esc_attr( $pdf_url ), esc_html( $link_text ) );

    $invoiceCode = $text;

    //$invoiceCode .= '<br><input type="submit" class="button apSend button-primary" name="apsend" value="Send Invoice to A/P Email" data-invoicename="'.$filename.'" data-orderid="'.$order->get_id().'">';

    return $invoiceCode;

}

function silibas_custom_email_invoice($filename, $mailto, $msg) {

	$attachments = array(realpath($_SERVER["DOCUMENT_ROOT"]).'/silibas-invoices/attachments/'.$filename);

	$mailRecipients = array();
	$mailRecipients[] = $mailto;

	$headers = 'From: Estrellita <info@estrellita.com>' . "\r\n";
	$headers .= 'Content-type: text/html';

	@wp_mail( $mailRecipients, 'Estrellita Invoice ', $msg, $headers, $attachments );

	return '<hr>sent <pre>'.print_r($attachments, true).'</pre><hr>';


}


// Save the data of the Meta field
add_action( 'save_post', 'mv_save_wc_order_other_fields', 10, 1 );

if ( ! function_exists( 'mv_save_wc_order_other_fields' ) ) {

    function mv_save_wc_order_other_fields( $post_id ) {

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'mv_other_meta_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'mv_other_meta_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        // --- Its safe for us to save the data ! --- //

        // Sanitize user input  and update the meta field in the database.
    }
    
}


// Display field value on the order edit page (not in custom fields metabox)
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){

    $my_custom_field = get_post_meta( $order->id, '_my_field_slug', true );

    if ( ! empty( $my_custom_field ) ) {
       // echo '<p><strong>'. __("My Field", "woocommerce").':</strong> ' . get_post_meta( $order->id, '_my_field_slug', true ) . '</p>';
    }

}





function sv_wc_cogs_add_purchase_order_column_header( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'order_total' === $column_name ) {
            $new_columns['purchase_order'] = __( 'Purchase Order', 'silibas' );
            $new_columns['salesperson'] = __( 'Salesperson', 'silibas' );
            $new_columns['pd'] = __( 'PD', 'silibas' );
            $new_columns['state'] = __( 'State', 'silibas' );
        }
    }

    return $new_columns;

}

add_filter( 'manage_edit-shop_order_columns', 'sv_wc_cogs_add_purchase_order_column_header', 20 );

function sv_wc_cogs_add_purchase_order_column_content( $column ) {

    global $post;

    if ( 'purchase_order' === $column ) {

    	echo get_post_meta($post->ID, '_transaction_id', true);
    
    } elseif ( 'salesperson' === $column ) {

    	echo get_post_meta($post->ID, 'salesperson', true);


    } elseif ( 'pd' === $column ) {

    	if (estrellita_has_pd_product($post->ID)) {

    		echo '<span class="dashicons dashicons-saved"></span>';
    	}

	} elseif ( 'state' === $column ) {

		if (get_post_meta($post->ID, '_shipping_state', true) == '') {
			echo get_post_meta($post->ID, '_billing_state', true);
		} else {
			echo get_post_meta($post->ID, '_shipping_state', true);
		}
    		
	}

}

add_action( 'manage_shop_order_posts_custom_column', 'sv_wc_cogs_add_purchase_order_column_content' );


function wc_custom_order_filter_fields( $fields ) {
    if ( ! in_array( '_transaction_id', $fields ) ) {
        array_push( $fields, '_transaction_id', '_billing_phone' );
    }
    return $fields;
}

add_filter( 'woocommerce_shop_order_search_fields', 'wc_custom_order_filter_fields' );



//add_filter( 'woocommerce_email_enabled_customer_completed_order', 'silibas_new_conditionally_send_wc_email', 10, 2 );

function silibas_new_conditionally_send_wc_email($whether_enabled, $object ) {

	$send = true;

	if (!empty($object)) {

		$order = new WC_Order( $object->id );

		if (isset($object->id)) {

			$orderNumber = $order->id;
			
			$trackingInfo = get_post_meta($orderNumber, 'ups_shipment_ids', true);

			if (get_post_meta($orderNumber, 'regcode', true) == '' && $trackingInfo == '') {
				update_option('aaa_log', 'false');
				return false;
			} else {

			}

		}
		
	}

	update_option('aaa_log', $whether_enabled);

	return $whether_enabled;

}
function estrellita_has_ds_pd_product($order_id) {

	$hasdsPDproduct = false;

	$order = wc_get_order( $order_id );

	$dsPDProducts = get_district_specific_pd();

	$productStr = '';

	if ( count( $order->get_items() ) > 0 ) {

		foreach ( $order->get_items() as $item ) {

			if ( 'line_item' == $item['type'] ) {

				//$_product = $order->get_product_from_item( $item );
				$product = $item->get_product();
				$product_id = $product->get_id();
				
				if (in_array($product_id, $dsPDProducts)) {
					$hasdsPDproduct = true;
				}
			}
		}
	}

	return $hasdsPDproduct;

}

function get_district_specific_pd() {
	$dspdProducts = array(70623,70622,70621,70620,65150,64396,64395,64394,64393);

	return $dspdProducts;
}

function get_pd_product_ids() {

	$pdProducts = array(
		7081, 
		5621, 
		5619, 
		5617, 
		5610, 
		2622, 
		2612, 
		2624, 
		2620, 
		2618, 
		600, 
		2593, 
		2184, 
		64393, 
		65150, 
		65148, 
		65141, 
		64396, 
		64395, 
		64394, 
		64393, 
		64372, 
		64370,
		64375,
		70622,
		70620,
		70621,
		70623,
		79351,
		79361,
		79375,
		79376,
		79378,
		79377
	);

	return $pdProducts;

}

function silibas_national_pd_products() {
	$nationalPDs = array(2618,2620,2624,64375,79351,79361);

	return $nationalPDs;
}

function silibas_remote_pd_products() {
	$nationalPDs = array(70623,70622,70621,70620);

	return $nationalPDs;
}

function silibas_onsite_pd_products() {
	$nationalPDs = array(2612,64370,64372,65141);

	return $nationalPDs;
}

function silibas_interactive_pd_products() {
	$nationalPDs = array(65150,64396,64395,64393);

	return $nationalPDs;
}

function order_contains_membership_only($order_id) {

	$memberProIDs = array(64338,64340,64342,64344);

	$onlyMembership = true;

	$order = wc_get_order( $order_id );

	if ( count( $order->get_items() ) > 0 ) {

		foreach ( $order->get_items() as $item ) {

			if ( 'line_item' == $item['type'] ) {

				//$_product = $order->get_product_from_item( $item );
				$product = $item->get_product();
				$product_id = $product->get_id();

				if (!in_array($product_id, $memberProIDs)) {
					$onlyMembership = false;
				}
			}
		}
	}

	return $onlyMembership;

}

function estrellita_has_pd_bulk($order_id) {

	$hasBulk = false;

	if (get_post_meta($order_id, 'bulk_registrant_0', true) != '') {
		return false;
	}

	$order = wc_get_order( $order_id );

	$pdProducts = get_pd_product_ids();

	foreach ($order->get_items() as $item_key => $item ) {

	    $item_id = $item->get_id();
	    $product_id   = $item->get_product_id();

	    if (in_array($product_id, $pdProducts)) {
	    	$product      = $item->get_product();
		    $product_sku    = $product->get_sku();

		    $item_data    = $item->get_data();

		    if ($item_data['quantity'] > 7 && $product_sku > 33333) {
		    	$hasBulk = true;
		    }

		}

	}

	return $hasBulk;

}

function estrellita_has_pd_product($order_id) {

	global $woocommerce;

	$hasPDproduct = false;
	$pdProducts = get_pd_product_ids();

	$order = wc_get_order( $order_id );

	foreach ( $order->get_items() as $item_id => $item ) {

	    $product = $item->get_product();

	    if ( $product instanceof WC_Product ) {

		    $product_id = $product->get_id();

		    if (in_array($product_id, $pdProducts)) {
		    	$hasPDproduct = true;
		    }

		    if ( $product && $product->get_parent_id() ) {

		    	$parent_id = $product->get_parent_id();
			    
			    if (in_array($parent_id, $pdProducts)) {
			    	$hasPDproduct = true;
			    }

			}

		}

	}


	return $hasPDproduct;

}

function estrellita_woocommerce_auto_complete_virtual_orders($order_status, $order_id ) {

	$order = wc_get_order( $order_id );

	$pdProducts = get_pd_product_ids();

	if ('processing' == $order_status) {
	
		$virtual_order = '';

		if ( count( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( 'line_item' == $item['type'] ) {

					$product = $item->get_product();
					$product_id = $product->get_id();

					//$_product = $order->get_product_from_item( $item );

					if ( ! $product->is_virtual() ) {
						$virtual_order = false;
						break;
					} else {
						if (!in_array($product_id , $pdProducts)) {
							$virtual_order = true;
						}	
					}


				}
			}
		}

		if ( $virtual_order ) {
			return 'completed';
		}
	}

	return $order_status;

}


add_action( 'rest_api_init', 'create_api_posts_meta_field' );

function create_api_posts_meta_field() {

    register_rest_field( 'shop_order', 'silibasonback', array(
           'get_callback'    => 'get_post_meta_for_api',
           'schema'          => null,
        )
    );
}

function get_post_meta_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];

    //return the post meta
    return get_post_meta( $post_id, 'silibasonback', true );

}



function wpse_258192_activation() {
  $admin = get_role( 'administrator' );
  $admin->add_cap( 'upload_csv' );
}

//* Remove upload_csv capability from administrator role
function wpse_258192_deactivation() {
  $admin = get_role( 'administrator' );
  $admin->remove_cap( 'upload_csv' );
}

//* Add filter to check filetype and extension
add_filter( 'wp_check_filetype_and_ext', 'wpse_258192_check_filetype_and_ext', 10, 4 );

//* If the current user can upload_csv and the file extension is csv, override arguments - edit - "$pathinfo" changed to "pathinfo"
function wpse_258192_check_filetype_and_ext( $args, $file, $filename, $mimes ) {
  if( current_user_can( 'upload_csv' ) && 'csv' === pathinfo( $filename )[ 'extension' ] ){
    $args = array(
      'ext'             => 'csv',
      'type'            => 'text/csv',
      'proper_filename' => $filename,
    );
  }
  return $args;
}



add_action('admin_bar_menu', 'add_toolbar_items', 100);

function add_toolbar_items($admin_bar){
    $admin_bar->add_menu( array(
        'id'    => 'est-manager',
        'title' => 'Manager',
        'href'  => '/wp-admin/edit.php?post_type=shop_order',
        'meta'  => array(
            'title' => __('Manager'),            
        ),
    ));
}

