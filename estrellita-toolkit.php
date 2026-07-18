<?php
/*
Plugin Name: Estrelitta Toolkit
Description: Website Plugins, Widgets, Shortcodes, Extras
Author: Estrellita
*/


/* updates 1/20/21 joe */

function explugin_activate() {
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'explugin_activate' );

function explugin_deactivate() {
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'explugin_deactivate' );

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/shortcodes.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/shortcodes.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/functions.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/functions.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/post_types.php" ) ) {

	if (plugin_dir_url(__FILE__) == 'https://estrellita.com/wp-content/plugins/estrellita-toolkit/') {
		require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/post_types.php"  );
	}
	if (plugin_dir_url(__FILE__) == 'https://estrellamaint.wpengine.com/wp-content/plugins/estrellita-toolkit/') {
		require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/post_types.php"  );
	}

}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce-forms.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce-forms.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce-invoiced.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/woocommerce-invoiced.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtt-api.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtt-api.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-redux.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-redux.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-notifications.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-notifications.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-redux.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-redux.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-gtt-list.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-gtt-list.php"  );
}
if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-meta-fields.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-meta-fields.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-shopmanager.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/estrellita-shopmanager.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtt-oauth2.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtt-oauth2.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtw-oauth2.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-gtw-oauth2.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/membership.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/membership.php"  );
}


if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/zoom-bulk.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/zoom-bulk.php"  );
}

if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-zoom-api.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/class-zoom-api.php"  );
}
if ( file_exists(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/school-districts.php" ) ) {
	require_once( WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/school-districts.php"  );
}


function estrellita_toolkit_enqueue_script() {
    
    if (is_page(463)) {

    	wp_enqueue_script( 'toolkit-js', "https://estrellita.com/wp-content/plugins/estrellita-toolkit/js/toolkit.js" , false );

    }

}

add_action( 'wp_enqueue_scripts', 'estrellita_toolkit_enqueue_script' );


/**
schedule cron jobs
**/


add_filter( 'cron_schedules', 'silibas_add_intervals');

function silibas_add_intervals($schedules) {
	// add a 'weekly' interval
	$schedules['oneminute'] = array(
		'interval' => 60,
		'display' => __('Once every 60 seconds')
	);
	$schedules['fiveminutes'] = array(
		'interval' => 300,
		'display' => __('Once every five minutes')
	);
	$schedules['fifteenminutes'] = array(
		'interval' => 900,
		'display' => __('Once every fifteen minutes')
	);
	return $schedules;
}


register_activation_hook( __FILE__, 'silibas_check_quote_taxes_schedule' );

function silibas_check_quote_taxes_schedule(){

  $timestamp = wp_next_scheduled( 'silibas_check_quote_taxes' );

  if( $timestamp == false ){

    wp_schedule_event( time(), 'oneminute', 'silibas_check_quote_taxes' );
    wp_schedule_event( time(), 'hourlycheck', 'silibas_goto_api_access' );

  }

}


add_action( 'silibas_check_quote_taxes', 'silibas_create_backup' );

function silibas_create_backup(){


}

//add_action('admin_init', 'silibas_goto_api_access');

function silibas_goto_api_access() {

	$gtw = new GTW(array(''));

	$gtwToken = $gtw->getToken();

	if (!$gtwToken) {
		//email error notification
		//silibas_send_error('GoToTraining Token Error', 'Reset API key: https://estrellita.com/wp-admin/tools.php?page=gtt-central');

	}

}

