<?php
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
/**
 * A custom Quote Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */

// add_action('admin_head', 'testtrigger');

// function testtrigger() {

// 	$betaEmail = new WC_Quote_Order_Email();

// 	$betaEmail->beta_trigger();

// }

/*
class WC_Quote_Order_Email extends WC_Email {


	 public function __construct() {
	 
	    // set ID, this simply needs to be a unique name
	    $this->id = 'wc_quote_order';
	 
	    // this is the title in WooCommerce Email settings
	    $this->title = 'Customer Quote Request';
	 
	    // this is the description in WooCommerce email settings
	    $this->description = 'Quote Request Notification emails are sent when a customer places a quote for an order';
	 
	    // these are the default heading and subject lines that can be overridden using the settings
	    $this->heading = 'Quote Request';
	    $this->subject = 'Quote Request';
	 
	    // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
	    $this->template_html  = 'emails/customer-new-quote.php';
	    $this->template_plain = 'emails/plain/customer-new-quote.php';
	 
	    // Trigger on new paid orders
	    add_action( 'woocommerce_order_status_request_to_quote-sent', array( $this, 'trigger' ) );
 
	    // Call parent constructor to load any other defaults not explicity defined here
	    parent::__construct();
	 
	    // this sets the recipient to the settings defined below in init_form_fields()
	    $this->recipient = $this->get_option( 'recipient' );
	 
	    // if none was entered, just use the WP admin email as a fallback
	    if ( ! $this->recipient )
	        //$this->recipient = get_option( 'admin_email' );
	        $this->recipient = 'joe@searlecreative.com';

	}

	public function trigger( $order_id, $isTest = false ) {
	 
	 	global $woocommerce, $wpdb;

	    // bail if no order ID is present
	    if ( ! $order_id )
	        return;
	 
	    $this->object = new WC_Order( $order_id );

	    if (get_post_meta($order_id, 'quote_sent', true) != 'true' || $isTest) {

			// replace variables in the subject/headings
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );
		 
			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
			//$this->send( 'joehowarddesign@gmail.com', $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			//$this->send( 'info@estrellita.com', $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

		    if ( ! $this->is_enabled() )
		        return;
		 
		    // woohoo, send the email!
	    
		    $customerEmail = get_post_meta($order_id, '_billing_email', true);

			$this->send( $customerEmail , $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			$this->send( get_option( 'admin_email' ), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );    			


			$order = new WC_Order($order_id);
			//$order->update_status('proposal-sent', 'quote emailed: ');

			update_post_meta($order_id, 'quote_sent', 'true');
			update_post_meta($order_id, 'delivery_test', 'true');
			update_post_meta($order_id, 'email to:',  $customerEmail);

			$this->silibas_set_gtt($order_id);

		} else {

		}


	}


	public function silibas_set_gtt($order_id) {


		$formData = apply_filters('get_the_excerpt', get_post_field('post_excerpt', $order_id));

		$formData = str_replace(array('<p>', '</p>'), array('', ''), $formData);

		update_post_meta($order_id, 'form_response', $formData);

		$this->silibas_translate_form_resp($order_id);

		return true;

	}

	public function silibas_translate_form_resp($order_id) {

		$formResp = get_post_meta($order_id, 'form_response', true);
		
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
					$infoStr['givenname'] = $vBlock['value'];
				} elseif ($vBlock['name'] == 'Last Name') {
					$infoStr['surname'] = $vBlock['value'];
				} elseif ($vBlock['name'] == 'Email') {
					$infoStr['email'] = $vBlock['value'];
				} elseif ($vBlock['name'] == 'product_id') {
					$infoStr['product_id'] = $vBlock['value'];
				}

			}

			$infoStr = json_encode($infoStr);

			update_post_meta($order_id, 'attendee_info_'.$i, $infoStr);

			$i++;

		}

	}

	public function beta_trigger() {

		$this->trigger('7414', true);

	}

	public function silibas_has_tax($order_id) {
		global $wpdb;

		$taxRate = $wpdb->get_var($wpdb->prepare("SELECT order_item_type FROM " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '".$order_id."' AND order_item_name = 'AVATAX'", 'ARRAY_A'));

		if ($taxRate == 'tax') {
			return true;
		} else {
			return false;
		}

	}

	public function get_content_html() {
	    ob_start();
	    woocommerce_get_template( $this->template_html, array(
	        'order'         => $this->object,
	        'email_heading' => $this->get_heading()
	    ) );
	    return ob_get_clean();
	}
	 
	 

	public function get_content_plain() {
	    ob_start();
	    woocommerce_get_template( $this->template_plain, array(
	        'order'         => $this->object,
	        'email_heading' => $this->get_heading()
	    ) );
	    return ob_get_clean();
	}
	 
	public function init_form_fields() {
	 
	    $this->form_fields = array(
	        'enabled'    => array(
	            'title'   => 'Enable/Disable',
	            'type'    => 'checkbox',
	            'label'   => 'Enable this email notification',
	            'default' => 'yes'
	        ),
	        'recipient'  => array(
	            'title'       => 'Recipient(s)',
	            'type'        => 'text',
	            'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'recipient' ) ) ),
	            'placeholder' => '',
	            'default'     => ''
	        ),
	        'subject'    => array(
	            'title'       => 'Subject',
	            'type'        => 'text',
	            'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
	            'placeholder' => '',
	            'default'     => ''
	        ),
	        'heading'    => array(
	            'title'       => 'Email Heading',
	            'type'        => 'text',
	            'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
	            'placeholder' => '',
	            'default'     => ''
	        ),
	        'email_type' => array(
	            'title'       => 'Email type',
	            'type'        => 'select',
	            'description' => 'Choose which format of email to send.',
	            'default'     => 'html',
	            'class'       => 'email_type',
	            'options'     => array(
	                'plain'     => 'Plain text',
	                'html'      => 'HTML', 'woocommerce',
	                'multipart' => 'Multipart', 'woocommerce',
	            )
	        )
	    );
	}





} // end \WC_Quote_Order_Email class

*/