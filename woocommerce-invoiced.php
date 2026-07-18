<?php

//if ( is_user_logged_in() ) {

function register_invoiced_order_status() {
    register_post_status( 'wc-invoiced', array(
        'label'                     => 'Invoiced',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Invoiced <span class="count">(%s)</span>', 'Invoiced <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_invoiced_order_status' );

// Add to list of WC Order statuses
function add_invoiced_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-invoiced'] = 'Invoiced';
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_invoiced_to_order_statuses' );



add_action( 'init', 'register_review_order_status' );
function register_review_order_status() {
    register_post_status( 'wc-order-review', array(
        'label'                     => 'Review',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Review <span class="count">(%s)</span>', 'Review <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_review_order_status' );

function add_review_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-order-review'] = 'Review';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_review_to_order_statuses' );



add_action('admin_head', 'silibas_invoiced_manager_view');

function silibas_invoiced_manager_view() {

	if (silibas_can_view_invoiced()) {

	} else {
		?>

		<?php
	}
}


function silibas_can_view_invoiced() {
	
	global $current_user;

	$canView = array(3,81);

	$user_id = $current_user->ID;
	$user_roles = $current_user->roles;
	
	$user_role = array_shift($user_roles);

	if (in_array($user_id, $canView) || $user_role == 'estrellitamanager') {
		return true;
	} else {
		return false;
	}

}



add_filter( 'bulk_actions-edit-shop_order', 'silibas_register_bulk_action' ); // edit-shop_order is the screen ID of the orders page
 
function silibas_register_bulk_action( $bulk_actions ) {
 
	$bulk_actions['mark_wc_invoiced'] = 'Change status to Invoiced'; // 
	return $bulk_actions;
 
}
 
/*
 * Bulk action handler
 * Make sure that "action name" in the hook is the same like the option value from the above function
 */
add_action( 'admin_action_mark_wc_invoiced', 'silibas_bulk_process_custom_status' ); // admin_action_{action name}
 
function silibas_bulk_process_custom_status() {
 
	// if an array with order IDs is not presented, exit the function
	if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )
		return;
 
	foreach( $_REQUEST['post'] as $order_id ) {
 
		$order = new WC_Order( $order_id );
		$order_note = '-';
		$order->update_status( 'invoiced', $order_note, true ); 
 
	}
 
	// of course using add_query_arg() is not required, you can build your URL inline
	$location = add_query_arg( array(
    		'post_type' => 'shop_order',
		'wc-invoiced' => 1, // =1 is just the $_GET variable for notices
		'changed' => count( $_REQUEST['post'] ), // number of changed orders
		'ids' => join( $_REQUEST['post'], ',' ),
		'post_status' => 'all'
	), 'edit.php' );
 
	wp_redirect( admin_url( $location ) );
	exit;
 
}
 
/*
 * Notices
 */
add_action('admin_notices', 'silibas_custom_order_status_notices');
 
function silibas_custom_order_status_notices() {
 
	global $pagenow, $typenow;
 
	if( $typenow == 'shop_order' 
	 && $pagenow == 'edit.php'
	 && isset( $_REQUEST['wc-invoiced'] )
	 && $_REQUEST['wc-invoiced'] == 1
	 && isset( $_REQUEST['changed'] ) ) {
 
		$message = sprintf( _n( 'Order status changed.', '%s order statuses changed.', $_REQUEST['changed'] ), number_format_i18n( $_REQUEST['changed'] ) );
		echo "<div class=\"updated\"><p>{$message}</p></div>";
 
	}
 
}