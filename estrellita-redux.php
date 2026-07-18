<?php
	/**
	* ReduxFramework Sample Config File
	* For full documentation, please visit: http://docs.reduxframework.com/
	*/

	if ( ! class_exists( 'Redux' ) ) {
	  return;
	}


	// This is your option name where all the Redux data is stored.
	$opt_name = "estrellita_options";

	/*
	*
	* --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
	*
	*/

	$sampleHTML = '';
	if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
	  Redux_Functions::initWpFilesystem();

	  global $wp_filesystem;

	  $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
	}

	// Background Patterns Reader
	$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
	$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
	$sample_patterns	 = array();

	if ( is_dir( $sample_patterns_path ) ) {

	  if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
		 $sample_patterns = array();

		 while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

			if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
				$name			 = explode( '.', $sample_patterns_file );
				$name			 = str_replace( '.' . end( $name ), '', $sample_patterns_file );
				$sample_patterns[] = array(
					'alt' => $name,
					'img' => $sample_patterns_url . $sample_patterns_file
				);
			}
		 }
	  }

	}

	/*
	*
	* --> Action hook examples
	*
	*/

	/**
	* ---> SET ARGUMENTS
	* All the possible arguments for Redux.
	* For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
	* */

	$theme = wp_get_theme(); // For use with some settings. Not necessary.

	$args = array(
	  // TYPICAL -> Change these values as you need/desire
	  'opt_name'			=> $opt_name,
	  // This is where your data is stored in the database and also becomes your global variable name.
	  'display_name'		=> $theme->get( 'estrellita_options' ),
	  // Name that appears at the top of your panel
	  'display_version'	 => $theme->get( 'Version' ),
	  // Version that appears at the top of your panel
	  'menu_type'			=> 'menu',
	  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	  'allow_sub_menu'	  => true,
	  // Show the sections below the admin menu item or not
	  'menu_title'		  => __( 'Site Options', 'estrellita-redux' ),
	  'page_title'		  => __( 'Site Options', 'estrellita-redux' ),
	  // You will need to generate a Google API key to use this feature.
	  // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	  'google_api_key'	  => '',
	  // Set it you want google fonts to update weekly. A google_api_key value is required.
	  'google_update_weekly' => false,
	  // Must be defined to add google fonts to the typography module
	  'async_typography'	=> true,
	  // Use a asynchronous font on the front end or font string
	  //'disable_google_fonts_link' => true,					// Disable this in case you want to create your own google fonts loader
	  'admin_bar'			=> true,
	  // Show the panel pages on the admin bar
	  'admin_bar_icon'	  => 'dashicons-portfolio',
	  // Choose an icon for the admin bar menu
	  'admin_bar_priority'   => 50,
	  // Choose an priority for the admin bar menu
	  'global_variable'	 => 'estopts',
	  // Set a different name for your global variable other than the opt_name
	  'dev_mode'			=> true,
	  // Show the time the page took to load, etc
	  'update_notice'		=> true,
	  // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	  'customizer'		  => true,
	  // Enable basic customizer support
	  //'open_expanded'	=> true,					// Allow you to start the panel in an expanded way initially.
	  //'disable_save_warn' => true,					// Disable the save warning when a user changes a field

	  // OPTIONAL -> Give you extra features
	  'page_priority'		=> null,
	  // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	  'page_parent'		 => 'themes.php',
	  // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	  'page_permissions'	=> 'manage_options',
	  // Permissions needed to access the options panel.
	  'menu_icon'			=> '',
	  // Specify a custom URL to an icon
	  'last_tab'			=> '',
	  // Force your panel to always open to a specific tab (by id)
	  'page_icon'			=> 'icon-themes',
	  // Icon displayed in the admin panel next to your menu_title
	  'page_slug'			=> '',
	  // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
	  'save_defaults'		=> true,
	  // On load save the defaults to DB before user clicks save or not
	  'default_show'		=> false,
	  // If true, shows the default value next to each field that is not the default value.
	  'default_mark'		=> '',
	  // What to print by the field's title if the value shown is default. Suggested: *
	  'show_import_export'   => true,
	  // Shows the Import/Export panel when not used as a field.

	  // CAREFUL -> These options are for advanced use only
	  'transient_time'	  => 60 * MINUTE_IN_SECONDS,
	  'output'			  => true,
	  // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	  'output_tag'		  => true,
	  // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	  // 'footer_credit'	=> '',				  // Disable the footer credit of Redux. Please leave if you can help it.

	  // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	  'database'			=> '',
	  // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	  'system_info'		 => false,
	  // REMOVE

	  //'compiler'			=> true,

	  // HINTS
	  'hints'				=> array(
		 'icon'		 => 'el el-question-sign',
		 'icon_position' => 'right',
		 'icon_color'	=> 'lightgray',
		 'icon_size'	=> 'normal',
		 'tip_style'	=> array(
			'color'   => 'red',
			'shadow'  => true,
			'rounded' => false,
			'style'   => '',
		 ),
		 'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		 ),
		 'tip_effect'	=> array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'	=> 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'	=> 'click mouseleave',
			),
		 ),
	  )
	);

	// Panel Intro text -> before the form
	if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
	  if ( ! empty( $args['global_variable'] ) ) {
		 $v = $args['global_variable'];
	  } else {
		 $v = str_replace( '-', '_', $args['opt_name'] );
	  }
	  $args['intro_text'] = sprintf( __( '<p>Hi.</p>', 'estrellita-redux' ), $v );
	} else {
	  $args['intro_text'] = __( '<p>Hello!</p>', 'estrellita-redux' );
	}

	// Add content after the form.
	$args['footer_text'] = __( '<p>Hola.</p>', 'estrellita-redux' );

	Redux::setArgs( $opt_name, $args );

	/*
	* ---> END ARGUMENTS
	*/


	/*
	* ---> START HELP TABS
	*/

	$tabs = array(
	  array(
		 'id'	 => 'redux-help-tab-1',
		 'title'   => __( 'Theme Information 1', 'estrellita-redux' ),
		 'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'estrellita-redux' )
	  ),
	  array(
		 'id'	 => 'redux-help-tab-2',
		 'title'   => __( 'Theme Information 2', 'estrellita-redux' ),
		 'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'estrellita-redux' )
	  )
	);
	Redux::setHelpTab( $opt_name, $tabs );

	// Set the help sidebar
	$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'estrellita-redux' );
	Redux::setHelpSidebar( $opt_name, $content );


	/*
	* <--- END HELP TABS
	*/


	/*
	*
	* ---> START SECTIONS
	*
	*/

	/*

	  As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


	*/

	// -> START Editors
	Redux::setSection( $opt_name, array(
	  'title' => __( 'Home Page', 'estrellita-redux' ),
	  'id'	=> 'editor',
	  'icon'  => 'el el-edit',
	) );


	/**
	slideshow
	**/
	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'Extra', 'estrellita-redux' ),
	  'id'		=> 'slideshow-options',
	  //'icon'  => 'el el-home'
	  'desc'	  => __( ' ', 'estrellita-redux' ) . '',
	  'subsection' => true,
	  'fields'	=> array(
		
	  ),
	) );

	/**
	global options
	**/
	// -> START Color Selection
	Redux::setSection( $opt_name, array(
	  'title' => __( 'Global, & More...', 'estrellita-redux' ),
	  'id'	=> 'globalmore',
	  'desc'  => __( '', 'estrellita-redux' ),
	  'icon'  => 'el el-brush'
	) );





	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'Global Settings', 'estrellita-redux' ),
	  'id'		=> 'global-settings',
	  'desc'	  => __( '', 'estrellita-redux' ) . '',
	  'subsection' => true,
	  'fields'	=> array(

		array(
                'id'       => 'si-regcodes',
                'type'     => 'textarea',
                'title'    => __( 'Site Implemention registration codes', 'redux-framework-demo' ),
                'subtitle' => __( 'Codes', 'redux-framework-demo' ),
                'desc'     => __( 'comma separated values please', 'redux-framework-demo' ),
                'default'  => '',
                ),
array(
                'id'       => 'si-membercodes-prek',
                'type'     => 'textarea',
                'title'    => __( 'Member registration codes (Pre-K)', 'redux-framework-demo' ),
                'subtitle' => __( 'Codes', 'redux-framework-demo' ),
                'desc'     => __( 'comma separated values please', 'redux-framework-demo' ),
                'default'  => '',
	  ),

		array(
                'id'       => 'si-membercodes-k1',
                'type'     => 'textarea',
                'title'    => __( 'Member registration codes (K1)', 'redux-framework-demo' ),
                'subtitle' => __( 'Codes', 'redux-framework-demo' ),
                'desc'     => __( 'comma separated values please', 'redux-framework-demo' ),
                'default'  => '',
	  ),
		array(
                'id'       => 'si-membercodes-escalera',
                'type'     => 'textarea',
                'title'    => __( 'Member registration codes (Escalera)', 'redux-framework-demo' ),
                'subtitle' => __( 'Codes', 'redux-framework-demo' ),
                'desc'     => __( 'comma separated values please', 'redux-framework-demo' ),
                'default'  => '',
            ),
	  		array(
                'id'       => 'si-membercodes-lunita',
                'type'     => 'textarea',
                'title'    => __( 'Member registration codes (Lunita)', 'redux-framework-demo' ),
                'subtitle' => __( 'Codes', 'redux-framework-demo' ),
                'desc'     => __( 'comma separated values please', 'redux-framework-demo' ),
                'default'  => '',
            )



	  ),
		


	) );


	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'SEO Options', 'estrellita-redux' ),
	  'id'		=> 'seo-options',
	  'desc'	  => __( 'These options will override any options found elsewhere.', 'estrellita-redux' ) . '',
	  'subsection' => true,
	  'fields'	=> array(

		array(
				'id'	   => 'opt-ace-editor-js',
				'type'	 => 'ace_editor',
				'title'	=> __( 'JS Code', 'estrellita-redux' ),
				'subtitle' => __( 'Paste your JS code here.', 'estrellita-redux' ),
				'mode'	 => 'javascript',
				'theme'	=> 'chrome',
				'desc'	 => '',
				'default'  => ""
			),


		array(
				'id'	   => 'opt-ace-editor-css',
				'type'	 => 'ace_editor',
				'title'	=> __( 'CSS Code', 'estrellita-redux' ),
				'subtitle' => __( 'Paste your CSS code here.', 'estrellita-redux' ),
				'mode'	 => 'css',
				'theme'	=> 'monokai',
				'desc'	 => '',
				'default'  => ""
			),



		 ),
	) );

	
	Redux::setSection( $opt_name, array(
	  'title' => __( 'API+', 'estrellita-redux' ),
	  'id'	=> 'api',
	  'desc'  => __( '', 'estrellita-redux' ),
	  'icon'  => 'el el-cog'
	) );

	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'Go To Training', 'estrellita-redux' ),
	  'desc'	  => __( ' ', 'estrellita-redux' ) . '',
	  'id'		=> 'api-scrm',
	  'subsection' => true,
	  'fields'	=> array(
	  	array(
			'id'	 => 'registration-headline',
			'type'	=> 'text',
			'title'	=> __( 'Registration Headline', 'catalina-redux' ),
			'subtitle' => __( '', 'estrellita-redux' ),
			'default' => 'You\'re Registered'
		 ),

	  	array(
			'id'	 => 'registration-content',
				'type'	=> 'editor',
				'title'   => __( 'Registration Content', 'catalina-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 8,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),

	  	array(
			'id'	 => 'notification-headline',
			'type'	=> 'text',
			'title'	=> __( 'Notification Headline', 'catalina-redux' ),
			'subtitle' => __( '', 'estrellita-redux' ),
			'default' => 'Coming Soon'
		 ),

		 array(
			'id'	 => 'notification-content',
				'type'	=> 'editor',
				'title'   => __( 'Notification Content', 'catalina-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 8,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),



	  )
	) );

	Redux::setSection( $opt_name, array(
	  'title' => __( 'Email Messaging', 'estrellita-redux' ),
	  'id'	=> 'email',
	  'desc'  => __( '', 'estrellita-redux' ),
	  'icon'  => 'el el-cog'
	) );

	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'PD Messages', 'estrellita-redux' ),
	  'desc'	  => __( ' ', 'estrellita-redux' ) . '',
	  'id'		=> 'pd-messages-desc',
	  'subsection' => true,
	  'fields'	=> array(
	  	array(
			'id'	 => 'national-content',
				'type'	=> 'editor',
				'title'   => __( 'National PD Content', 'silibas-redux' ),
				'desc'   => __( '', 'silibas-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 16,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),
	  	array(
			'id'	 => 'district-content',
				'type'	=> 'editor',
				'title'   => __( 'District Specific Interactive Content', 'silibas-redux' ),
				'desc'   => __('', 'silibas-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 16,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),
	  	array(
			'id'	 => 'remote-content',
				'type'	=> 'editor',
				'title'   => __( 'District Specific Remote Content', 'silibas-redux' ),
				'desc'   => __('', 'silibas-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 16,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),
	  	array(
			'id'	 => 'onsite-content',
				'type'	=> 'editor',
				'title'   => __( 'Onsite PD Content', 'silibas-redux' ),
				'desc'   => __('', 'silibas-redux' ),
				'default' => '',
				'args'	=> array(
					'wpautop'	   => false,
					'media_buttons' => true,
					'textarea_rows' => 16,
					'teeny'		 => false,
					'quicktags'	 => true,
				)
			),



	  )
	) );

	Redux::setSection( $opt_name, array(
	  'title' => __( 'Commerce', 'estrellita-redux' ),
	  'id'	=> 'commerce',
	  'desc'  => __( '', 'estrellita-redux' ),
	  'icon'  => 'el el-cog'
	) );

	Redux::setSection( $opt_name, array(
	  'title'	 => __( 'PD Products', 'estrellita-redux' ),
	  'desc'	  => __( ' ', 'estrellita-redux' ) . '',
	  'id'		=> 'products-pd',
	  'subsection' => true,
	  'fields'	=> array(
	  	array(
			'id'	 => 'text-headline',
			'type'	=> 'select',
			'multi'    => true,
			'data' => 'posts',
			'args'     => array( 'post_type' =>  array( 'product' ), 'numberposts' => -1 ),
			'title'    => __('Select PD Products', 'redux-framework-demo'), 
			'subtitle' => __( '', 'estrellita-redux' ),
			// 'options'  => pd_products(),
		 ),



	  )
	) );

	if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
	  $section = array(
		 'icon'   => 'el el-list-alt',
		 'title'  => __( 'Documentation', 'estrellita-redux' ),
		 'fields' => array(
			array(
				'id'	  => '17',
				'type'	=> 'raw',
				'markdown' => true,
				'content'  => file_get_contents( dirname( __FILE__ ) . '/../README.md' )
			),
		 ),
	  );
	  Redux::setSection( $opt_name, $section );
	}
	/*
	* <--- END SECTIONS
	*/

	/**
	* This is a test function that will let you see when the compiler hook occurs.
	* It only runs if a field	set with compiler=>true is changed.
	* */
	function compiler_action( $options, $css, $changed_values ) {
	  echo '<h1>The compiler hook has run!</h1>';
	  echo "<pre>";
	  print_r( $changed_values ); // Values that have changed since the last save
	  echo "</pre>";
	  //print_r($options); //Option values
	  //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
	}

	/**
	* Custom function for the callback validation referenced above
	* */
	if ( ! function_exists( 'redux_validate_callback_function' ) ) {
	  function redux_validate_callback_function( $field, $value, $existing_value ) {
		 $error   = false;
		 $warning = false;

		 //do your validation
		 if ( $value == 1 ) {
			$error = true;
			$value = $existing_value;
		 } elseif ( $value == 2 ) {
			$warning = true;
			$value   = $existing_value;
		 }

		 $return['value'] = $value;

		 if ( $error == true ) {
			$return['error'] = $field;
			$field['msg']	= 'your custom error message';
		 }

		 if ( $warning == true ) {
			$return['warning'] = $field;
			$field['msg']	 = 'your custom warning message';
		 }

		 return $return;
	  }
	}

	/**
	* Custom function for the callback referenced above
	*/
	if ( ! function_exists( 'redux_my_custom_field' ) ) {
	  function redux_my_custom_field( $field, $value ) {
		 print_r( $field );
		 echo '<br/>';
		 print_r( $value );
	  }
	}

	/**
	* Custom function for filtering the sections array. Good for child themes to override or add to the sections.
	* Simply include this function in the child themes functions.php file.
	* NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
	* so you must use get_template_directory_uri() if you want to use any of the built in icons
	* */
	function dynamic_section( $sections ) {
	  //$sections = array();
	  $sections[] = array(
		 'title'  => __( 'Section via hook', 'estrellita-redux' ),
		 'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'estrellita-redux' ),
		 'icon'   => 'el el-paper-clip',
		 // Leave this as a blank section, no options just some intro text set above.
		 'fields' => array()
	  );

	  return $sections;
	}

	/**
	* Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
	* */
	function change_arguments( $args ) {
	  //$args['dev_mode'] = true;

	  return $args;
	}

	/**
	* Filter hook for filtering the default value of any given field. Very useful in development mode.
	* */
	function change_defaults( $defaults ) {
	  $defaults['str_replace'] = 'Testing filter hook!';

	  return $defaults;
	}

	// Remove the demo link and the notice of integrated demo from the redux-framework plugin
	function remove_demo() {

	  // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
	  if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		 remove_filter( 'plugin_row_meta', array(
			ReduxFrameworkPlugin::instance(),
			'plugin_metalinks'
		 ), null, 2 );

		 // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
		 remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
	  }
	}

	function pd_products() {

		$pdOptions = array(
		     '1' => 'Opt 1',
		     '2' => 'Opt 2',
		     '3' => 'Opt 3'
		);

		return $pdOptions;

	}
	function national_pd_names() {

		$pdText = '';

		//$pdProducts = silibas_national_pd_products();

		// foreach ($pdProducts as $pd) {
		// 	$pdText .= '<p>'.get_the_title($pd).'</p>';
		// }	

		return $pdText;
	}
