<?php

add_action( 'scg_school_data', 'scg_cache_schools' );
add_action( 'scg_district_data', 'scg_cache_districts' );


add_action('init', 'x_cpt_district', 0);
add_action('init', 'x_cpt_district_tax', 0);

function x_cpt_district()
{
	register_post_type(
		'district',
		array(
			'labels' => array(
				'name' => __('Districts'), 'singular_name' => __('District'),
				'add_new' => __('Add New District'),
				'add_new_item' => __('Add New District'),
				'edit' => __('Edit District'),
				'edit_item' => __('Edit District '),
				'new_item' => __('New District'),
				'view' => __('View District'),
				'view_item' => __('View District'),
				'search_items' => __('Search Districts'),
				'not_found' => __('No Districts found'),
				'not_found_in_trash' => __('No Districts found in Trash'),
				'parent' => __('District Source'),
			),
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'supports' => array('title', 'custom-fields'),
			'public' => true,
			'menu_position' => 16
		)
	);
}

function x_cpt_district_tax()
{
	register_taxonomy(
		'schoolname',
		'district',
		array(
			'label' => __('Schools'),
			'rewrite' => array('slug' => 'name'),
			'hierarchical' => true
		)
	);	
}






//* Add district field to the checkout page
add_action('woocommerce_billing_fields', 'scg_sd_add_select_checkout_field');

function scg_sd_add_select_checkout_field( $fields ) {

	$district_cached_data = maybe_unserialize(get_option('district_data'));

	$district_ops = array();

	$district_ops['0'] = __( 'Select a district', 'scg' );

	foreach($district_cached_data as $d_id => $district_name) {
		$district_ops[$d_id] = __( $district_name, 'scg' );
	}

	// echo '<h4>'.__('School District / School Name').'</h4>';

	$fields['district_name'] =  array(
	    'type'          => 'select',
	    'class'         => array( 'district-name' ),
	    'label'         => __( 'School District' ),
	    'options'       => $district_ops,
	    'required'	=> true,
    );
    
    $fields['district-id'] = array(
        'label' => __('District Name', 'woocommerce'),
        'required' => true, 
        'clear' => true, 
        'type' => 'text', 
        'class' => array('district-id')  
    );

	$fields['school_name'] =  array(
	    'type'          => 'select',
	    'class'         => array( 'school-name' ),
	    'label'         => __( 'School Name' ),
	    'options'       => array(
	    	'0'		=> __( 'Select a school', 'scg' ),
	    ),
	    'required'	=> true,
    );

    $fields['school-id'] = array(
        'label' => __('School Name', 'woocommerce'),
        'required' => true, 
        'clear' => true, 
        'type' => 'text', 
        'class' => array('school-id')  
    );

	return $fields;

}


add_action( 'woocommerce_checkout_create_order', 'scg_district_school_name_save' );

function scg_district_school_name_save( $order ) {

    if ( isset($_POST['district-id']) && ! empty($_POST['district-id']) ) {
    	if (is_numeric($_POST['district-id'])) {
	        $order->update_meta_data( 'billing_school_district_name_id', sanitize_text_field( $_POST['district_name'] ) );
	        $order->update_meta_data( 'billing_school_district_name', sanitize_text_field( get_the_title($_POST['district-id']) ) );
    	} else {
    		//store the name without the ID and let the match happen in dashboard
	        $order->update_meta_data( 'billing_school_district_name', sanitize_text_field( $_POST['district-id'] ) );
	        $order->update_meta_data( 'billing_school_district_name_id', '' );
    	}

    }

    if ( isset($_POST['school-id']) && ! empty($_POST['school-id']) ) {
    	//check to make sure is number
    	if (is_numeric($_POST['school-id'])) {
    		//id is set, use that and apply post titlte to name field
	        $order->update_meta_data( 'billing_school_name_id', sanitize_text_field( $_POST['school_name'] ) );

	        //get term name by ID
	        $term = get_term_by( 'id', sanitize_text_field($_POST['school-id']), 'schoolname' ); 
	        $order->update_meta_data( 'billing_school_name', sanitize_text_field( $term->name ) );

    	} else {
    		//store the name without the ID and let the match happen in dashboard
	        $order->update_meta_data( 'billing_school_name', sanitize_text_field( $_POST['school-id'] ) );
	        $order->update_meta_data( 'billing_school_name_id', '' );
    	}

    }

}


//woocommerce_checkout_create_order


add_action('admin_menu', 'scg_dm_page');


function scg_dm_page() {

	add_management_page('School/District Management', 'School Management', 'edit_theme_options', 'district-management', 'scg_district_page');

}

function scg_district_page() {
	echo '<div id="pageWrap"><h1>Start</h1>';


	global $wpdb;

	$ninety_days_ago = date('Y-m-d', strtotime('-90 days'));
	$today = date('Y-m-d');

	$args = array(
	    'limit'        => -1, // Retrieve all matching orders
	    'type'         => 'shop_order',
	    'status'       => array('wc-completed'), // Adjust statuses as needed
	    'date_created' => $ninety_days_ago . '...' . $today,
	);

	$orders = wc_get_orders($args);

	foreach ($orders as $order) {
	    // Process each order
	    $order_id = $order->get_id();
	    $order_date = $order->get_date_created();
	    
	    $title = get_post_meta($order_id, '_billing_school_district', true);
	    $myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'district' AND post_title LIKE '%s'", '%'. $wpdb->esc_like( $title ) .'%') );

	    echo "Order ID: $order_id, Date: $order_date : Fuzzy Match = ID (".$myposts[0]->ID.")".$myposts[0]->post_title."<br>";

	}



	// $title = get_post_meta(92705, '_billing_school_district', true);
	// $myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'district' AND post_title LIKE '%s'", '%'. $wpdb->esc_like( $title ) .'%') );

	// echo '<pre>';
	// print_r($myposts);

	// echo '</pre>';


	?>





	</div>
	<?php


}
add_action('wp_ajax_scg_get_schools', 'scg_ajax_disrict_school_names');
add_action('wp_ajax_nopriv_scg_get_schools', 'scg_ajax_disrict_school_names');

function scg_ajax_disrict_school_names() {
	
	global $options;

	$post_id = $_POST['post_id'];

	if ($post_id == 0 || $post_id == '') {
		$school_data = maybe_unserialize(get_option('school_data'));
	} else {
		$school_data = scg_get_disrict_school_names($post_id);
	}

	echo json_encode($school_data);

	die();

}

function scg_get_disrict_school_names($post_id) {
	
	$school_arr = [];

	$school_names = wp_get_object_terms( $post_id, 'schoolname', array( 'fields' => 'all' ) );

	foreach($school_names as $term) {
		$school_arr[$term->term_id] = $term->name;
	}

	return $school_arr;
}

function scg_sil_the_slug_exists($post_name, $post_type) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "' AND post_type = '" . $post_type . "'", 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
}

function scg_sil_the_district_ID($post_name, $post_type) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_id FROM wp_posts WHERE post_name = '" . $post_name . "' AND post_type = '" . $post_type . "'", 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
}
//scg_cache_districts
//scg_cache_schools
function scg_cache_districts() {

	$districtData = [];

	$loop = new WP_Query(
	    [
	        'post_type' => 'district',
	        'posts_per_page' => -1,
	    ]
	);
	while ( $loop->have_posts() ) : $loop->the_post();
		if ($loop->post->post_title != '') {
			$districtData[$loop->post->ID] = $loop->post->post_title;
		}
	endwhile;

	asort($districtData);

	update_option('district_data', maybe_serialize($districtData));

}

function scg_cache_schools() {

	$schoolData = [];

	$terms = get_terms(['taxonomy' => 'schoolname', 'hide_empty' => false]);

	foreach($terms as $term) {
		$schoolData[$term->term_id] = $term->name;
	}

	asort($schoolData);

	update_option('school_data', maybe_serialize($schoolData));

}


function scg_sil_csv_to_array($csv_file) {

	$csv_lines = file($csv_file);

	if (file_exists($csv_file)) {

		$csv_lines = file($csv_file);
		$updateCount = count($csv_lines);

		$csv_data = array();

		$i = 0;

		//turn csv data into an array
		foreach($csv_lines as $csv_value) {
			$csv_data[$i] = explode("\t", $csv_value);
			$i++;
		}

	}

	return $csv_data;

}


//district_name


add_action('wp_footer', 'scg_checkout_scg_select2');

function scg_checkout_scg_select2() {


	if (is_checkout() || is_page(5170)) {
	//if is woocommerce checkout or quote
	?>
	<style>
	.district-id,
	.school-id,
	#district-id_field,
	#school-id_field {display:none;}
	.woocommerce-input-wrapper {
		position: relative;
	}
	.adq-billing .select2-container {
		min-width: 320px;
	}
	#spinning {
		position: absolute;
		z-index: 999;
		top:0;
		left:0;
		width:100%;
		height:100%;
		background-color:rgba(255,255,255,.7);
	}
		.spin-container {
			position: absolute;
			top:50%;
			left:50%;
			z-index: 1001;
		}
	.custom-invalid-required-field label {
		color: var(--wc-red);
	}
	</style>
	<script>

	jQuery(document).ready(function($) {

		 $('form.woocommerce-checkout, form.adq-billing').on('submit', function(event) {

		 	var districtID = $("#district-id").val();
		 	var schoolID = $("#school-id").val();

		 	if (districtID == '') {
		 		<?php if (is_page(5170)) { ?>
		 			alert('Please select a school district');
		 			$('.adq-billing').slideDown();
	 			<?php } ?>
		 		$('.district-name').addClass('custom-invalid-required-field');
		 	} else {
		 		$('.district-name').removeClass('custom-invalid-required-field');

		 	}
		 	if (schoolID == '') {
		 		<?php if (is_page(5170)) { ?>
		 			alert('Please select a school name');
		 			$('.adq-billing').slideDown();
	 			<?php } ?>
		 		$('.school-name').addClass('custom-invalid-required-field');
		 	} else {
		 		$('.school-name').removeClass('custom-invalid-required-field');
		 	}
		 	if (schoolID == '' || districtID == '') {

		 		return false;
		 		preventDefault();
		 	} else {
		 		return true;
		 	}



		 });

	    $("#district_name").select2({
		  tags: true
		});

		$("#district_name").on("change", function (e) {
			//enable if disabled school name
			$("#school_name").prop("disabled", false);
			$('#school-id').val('');
			//show spinner
			spinner_init('show');

			var districtID = $("#district_name :selected").val();

			if ($.isNumeric(districtID)) {
				//ajax to get available schools
				$('#district-id').val(districtID);
				//reset schools
				$("#school_name option").each(function() {
				    $(this).remove();
				});

				$('#school_name').append('<option value="0">Select School</option>');
				
				console.log('District ID: '+districtID);

				var updateMetaData = {
					'action':'scg_get_schools',
					'post_id': districtID,
				};

				console.log(updateMetaData);

				jQuery.post("/wp-admin/admin-ajax.php", updateMetaData, function(apResp, status) {
					console.log('res step: '+apResp);
					const data = JSON.parse(apResp);
					console.log('res parsed: '+data);
					$.each(data, function(key, value) {
			            $('#school_name').append('<option value="'+key+'">'+value+'</option>');
			            console.log('key-> '+key + ": value->" + value);
			        });

					if (!apResp) {
						console.log('error no current schools missing');
					}
					//remove spinner
					spinner_init('complete');
				});
			} else {
				console.log('name to commit: '+districtID);
				//confirm new district to add
				$('#district-id').val(districtID);

				var updateMetaData = {
					'action':'scg_get_schools',
					'post_id': '0'
				};

				jQuery.post("/wp-admin/admin-ajax.php", updateMetaData, function(apResp, status) {
					console.log('res step: '+apResp);
					const data = JSON.parse(apResp);
					console.log('res parsed: '+data);
					$.each(data, function(key, value) {
			            $('#school_name').append('<option value="'+key+'">'+value+'</option>');
			            console.log('key-> '+key + ": value->" + value);
			        });

					if (!apResp) {
						console.log('error no current schools missing');
					}
					//remove spinner
					spinner_init('complete');
				});


			}

			$('#school_name').fadeIn();
			
		});

	    $("#school_name").select2({
		  tags: true
		});

		$("#school_name").on("change", function (e) {

			var schoolID = $("#school_name :selected").val();
			console.log('school change' + schoolID);

			$('#school-id').val(schoolID);

		});

		//if school name change

		//school-ID
		function spinner_init(status) {
			if (status == 'show') {
				$('.woocommerce-additional-fields').addClass('enqueue');
				$('.woocommerce-additional-fields').prepend('<div id="spinning"><div class="spin-container"><img src="/wp-content/plugins/estrellita-toolkit/img/1488.gif" /></div></div>');
			} else {
				$('.woocommerce-additional-fields').removeClass('enqueue');
				$('.woocommerce-additional-fields #spinning').remove();
			}
		}
		
	});


	</script>
<?php
	}
}


function scg_toolkit_enqueue_script() {

	if (is_page(5170)) {
    	wp_enqueue_style( 'select2-css', '/wp-content/plugins/woocommerce/assets/css/select2.css', array(), '');
    	wp_enqueue_script( 'select2-js', '/wp-content/plugins/woocommerce/assets/js/selectWoo/selectWoo.full.min.js', 'jquery', '');
	}

}

add_action( 'wp_enqueue_scripts', 'scg_toolkit_enqueue_script' );