<?php

add_shortcode('eflip', 'estrelitta_flip_book');

function estrelitta_flip_book($atts) {
	extract( shortcode_atts( array(
		  'src' => '',
		  'id'	=> '',
	 ), $atts));

	$flipCode = '';

	$flipCode .= '<div class="eflip-wrapper">';

	$flipCode .= '<a href="#" id="eflip-'.$id.'" class="eflip"><img src="'.$src.'"></a>';

	$flipCode .= '</div>';

	return $flipCode;

}


add_shortcode('addit', 'estrellita_add_to_cart');

function estrellita_add_to_cart($atts) {


	extract( shortcode_atts( array(
		  'type' => 'type',
		  'id'	=> 'id',
		  'img' => 'img',
		  'price' => 'price',
		  'text'	=> 'text',
		  'title'	=> 'title',
		  'cart'	=> '',
		  'duo'	=> '',
		  'quote'	=> '',
		  'ec'	=> '',
	 ), $atts));

	$getScript = false;

	$shortCode = '';

	if ($type == 'quote') {

		$shortCode .= silibas_get_quote_button($id, $ec);

	} elseif ($type == 'cart') {

		$shortCode .= silibas_get_cart_button($id, $ec);

		$getScript = true;
	
	} elseif ($duo == 'true') {

		$options = array();

		$options[] = $title;
		$options[] = $img;
		$options[] = $price;

		$shortCode .= silibas_get_product_html($id, $options, $text);

		if ($cart == 'false') {
			$shortCode .= '<style>#customCart-'.$id.' {display:none;}</style>';
		}
		if ($quote == 'false') {
			$shortCode .= '<style>#customQuote-'.$id.' {display:none;}</style>';
		}

		$getScript = true;



	} elseif ($type == 'product') {	
		$options = array();

		$options[] = $title;
		$options[] = $img;
		$options[] = $price;

		$shortCode .= silibas_get_product_html($id, $options, $text);

		if ($cart == 'false') {
			$shortCode .= '<style>#customCart-'.$id.' {display:none;}</style>';
		}
		if ($quote == 'false') {
			$shortCode .= '<style>#customQuote-'.$id.' {display:none;}</style>';
		}

		$getScript = true;

	} else {
	
		$shortCode = '';
	
	}

	$shortCode .= '<style>';
	$shortCode .= '.customQuoteForm .quantity, .customCartForm .quantity {float:left; max-width:65px; margin-right:10px;}';
	$shortCode .= '.customQuoteForm .button_add_to_quote {margin-top:0;margin-bottom:0;}';
	$shortCode .= '.customQuoteForm .button_add_to_quote {clear:none!important;}';
	$shortCode .= '.customCartForm .single_add_to_cart_button {font-size:16px; background-position: 0px 0px;bottom: 0px;box-sizing: border-box;color: rgb(0, 0, 0);cursor: pointer;display: inline-block;height: 34px;left: 0px;position: relative;right: 0px;text-ecoration: none;text-size-adjust: 100%;top: 0px;white-space: nowrap;background:#fdb143 none repeat scroll 0 0;border:1px solid #E2982F;border-radius: 4px 4px 4px 4px;font-family:inherit;padding: 6px 12px;}';
	$shortCode .= '.customCartForm .single_add_to_cart_button:hover {background:#eb5b43;border-color:#da3317;}';
	$shortCode .= '.estrelittaProduct .product_actions a {color:#68c6b6;}';
	$shortCode .= '.customQuoteForm .reponse_to_quote { display: block; }';
	$shortCode .= '.customQuoteForm .reponse_to_quote ~ .reponse_to_quote {display: none; }';
	$shortCode .= '.selectDate {margin-top:10px;}';
	$shortCode .= '.customQuoteForm #response-'.$id.' {display:block;}';

	$shortCode .= '</style>';

	$isVariant = silibas_is_variable_product($id);
	if ($id != 64367 || $id != 64362) {

	if (!$isVariant) {
		if ($type == 'quote') {
			$shortCode .= silibas_get_cart_script($id, 'simple', true);
		} else {
			$shortCode .= silibas_get_cart_script($id, 'simple', false);
		}
	} else {
		$shortCode .= silibas_get_cart_script($id, 'variant', false);
	}
}

	return $shortCode;

	

}

function silibas_is_variable_product($product_id) {

	global $woocommerce, $product;

	$variable_array = array('2624','2620','2618', '64375', '79351', '79361');

	$variationCatID = '76';

	$showVars = false;

	if (in_array($product_id, $variable_array)) {
		$showVars = true;
	}

	return $showVars;
}


function silibas_get_cart_button($product_id, $ecOption) {

	global $woocommerce, $product;

	$cartButtonCode = '';

	$showVars = silibas_is_variable_product($product_id);

	if ($showVars) {

		$currentTime = time();

		$args = array(
			'post_type'     => 'product_variation',
			'post_status'   => array( 'publish' ),
			'numberposts'   => -1,
			'orderby'       => 'menu_order',
			'order' => 'ASC', 
			'post_parent'   => $product_id 
		);
		$variations = get_posts( $args );

		$cartButtonCode .= '<div style="width:100%;margin:0 0 5px 0;">';
// $cartButtonCode .= '<!--';
// $cartButtonCode .= print_r($variations, true);
// $cartButtonCode .= ' -->';
		$cartButtonCode .= '<select id="date-'.$product_id.'" class="gtt_picker" name="attribute_date" data-attribute_name="attribute_date" "="" data-show_option_none="yes" style="width:100%;padding:4px;">';
		$cartButtonCode .= '<option value="">Choose an option</option>';

		foreach($variations as $var_post) {
			$varSku = get_post_meta($var_post->ID, '_sku', true);

			if ($varSku > 0) {
				$productDateName = get_post_meta($var_post->ID, 'attribute_date', true);

				$productDateStr = explode('(', $productDateName);

				$productTime = strtotime($productDateStr[0]);

				//check for EC dates specific to view
				$var_desc = get_post_meta($var_post->ID, '_variation_description', true);

				if ($productTime < $currentTime) {

				} else {
					if ($ecOption != '') {
						if ($ecOption == $var_desc) {
							$cartButtonCode .= '<option value="'.$var_post->ID.'" class="attached enabled educonsult '.$ecOption.' - '.$var_desc.'">'.get_post_meta($var_post->ID, 'attribute_date', true).'</option>';
						}
					} else {
						if ($var_desc == '' ) {
							$cartButtonCode .= '<option value="'.$var_post->ID.'" class="attached pddate enabled :'.$ecOption.' : '.$var_desc.'">'.get_post_meta($var_post->ID, 'attribute_date', true).'</option>';
						}			
					}
				}
			} else {
				///sku not found/set or is duplicate
			}

		}

		$cartButtonCode .= '	</select>';
		$cartButtonCode .= '</div><div style="clear:both"></div>';



		foreach($variations as $var_post) {

			$varSku = get_post_meta($var_post->ID, '_sku', true);

			if ($varSku > 0) {
				
				$cartButtonCode .= '<div id="addCart-'.$var_post->ID.'" class="selectDate" style="display:none;"><form id="customCart-'.$var_post->ID.'" class="cart customCartForm varForm" method="post" enctype="multipart/form-data">';

				$cartButtonCode .= '<div class="quantity">';
				$cartButtonCode .= '	<input id="qty-'.$var_post->ID.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">';
				$cartButtonCode .= '</div>';
				$cartButtonCode .= '<input type="hidden" name="add-to-cart" value="'.$var_post->ID.'">';
				$cartButtonCode .= '<button type="submit" class="single_add_to_cart_button button alt" data-pid="'.$var_post->ID.'" data-add="variation">Add to cart</button>';
				$cartButtonCode .= '<div class="clear"></div>'; 
				$cartButtonCode .= '</form></div>';
			} else {
				///sku not found/set or is duplicate
			}


		}

	} else {





		$cartButtonCode .= '<form id="customCart-'.$product_id.'" class="cart customCartForm" method="post" enctype="multipart/form-data">';
		$cartButtonCode .= '<div class="quantity">';
		$cartButtonCode .= '	<input id="qty-'.$product_id.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">';
		$cartButtonCode .= '</div>';
		$cartButtonCode .= '<input type="hidden" name="add-to-cart" value="'.$product_id.'">';
		$cartButtonCode .= '<button type="submit" class="single_add_to_cart_button button alt" data-pid="'.$product_id.'" data-add="simple">Add to cart</button>';
		$cartButtonCode .= '<div class="clear"></div>'; 
		$cartButtonCode .= '</form>';





	}

	return $cartButtonCode;

}


function silibas_get_quote_button($product_id, $ecOption) {

	$quoteButtonCode = '';

	$quoteButtonCode .= '<form id="customQuote-'.$product_id.'" class="cart customQuoteForm" method="post" enctype="multipart/form-data">';
	$quoteButtonCode .= '<div class="quantity">';
	$quoteButtonCode .= '	<input id="qty-'.$product_id.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">';
	$quoteButtonCode .= '</div>';
	$quoteButtonCode .= '<div class="simple_add_to_quote button_add_to_quote">  ';				  
	$quoteButtonCode .= '<button class="single_adq_button  button alt" id="add_to_quote" data-product-id="'.$product_id.'" data-product-type="simple" data-button="simple_add_to_quote" data-is_quote="1" type="button"> Add to quote</button>  ';
	$quoteButtonCode .= '</div>';
	$quoteButtonCode .= '<div class="clear"></div>'; 
	$quoteButtonCode .= '</form>';

	return $quoteButtonCode;
}

function silibas_get_quote_var_button($product_id, $option) {


	$showVars = silibas_is_variable_product($product_id);

	$quoteButtonCode = '';
		$quoteButtonCode .= '<!-- quote button id: '.$product_id.' -->';

	if ($showVars) {

		$quoteButtonCode .= '<!-- pre quote button query -->';

		$currentTime = time();

		$args = array(
			'post_type'     => 'product_variation',
			'post_status'   => array( 'publish' ),
			'numberposts'   => -1,
			'orderby'       => 'menu_order',
			'order' => 'ASC', 
			'post_parent'   => $product_id 
		);


		$variations = get_posts( $args );


		foreach($variations as $var_post) {

			$quoteButtonCode .= '<div id="wrapCustomQuote-'.$var_post->ID.'" class="selectDate" style="display:none;">';
			$quoteButtonCode .= '<form id="customQuote-'.$var_post->ID.'" class="cart customQuoteForm varForm" method="post" enctype="multipart/form-data">';
			$quoteButtonCode .= '<div class="quantity">';
			$quoteButtonCode .= '	<input id="qty-'.$var_post->ID.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">';
			$quoteButtonCode .= '</div>';
			$quoteButtonCode .= '<input type="hidden" name="add-to-cart" value="'.$var_post->ID.'">';
			$quoteButtonCode .= '<button class="single_adq_button  button alt" id="add_to_quote" data-product-id="'.$var_post->ID.'" data-product-type="simple" data-button="simple_add_to_quote" data-is_quote="1" type="button"> Add to quote</button>  ';
			$quoteButtonCode .= '<div class="clear"></div>'; 
			$quoteButtonCode .= '</form></div>';

		}

	}

	$quoteButtonCode .= '<!-- quote button code: '.$showVars.'-->';

	return $quoteButtonCode;


}



function silibas_get_cart_script($product_id, $product_type, $includeQuote) {

	if (!is_admin()) {

	$cartScript = '';

	$cartScript .= '';
	

	if ( $_SERVER['QUERY_STRING'] == 'fl_builder' ) {

	} else {

		if ($product_id != '') { ?>


		<script>

		jQuery(document).ready(function($) {
			<?php
			
			if ($product_type == 'simple') {
				?>
				console.log('simple-<?php echo $product_id;?> - included quote: <?php echo $includeQuote;?>');

				var cartID = 'customCart-<?php echo $product_id;?>';
				var quoteID = 'customQuote-<?php echo $product_id;?>';
				
				jQuery('body').on('click', '#customCart-<?php echo $product_id;?> .single_add_to_cart_button', function(e) {
					
					var pid = $(this).attr('data-pid');
					var qty = $('#qty-'+pid).val();

					console.log('pid: '+pid);
					console.log(qty);

		          	addToCart(pid, qty);

					if (jQuery('#customCart-<?php echo $product_id;?>:contains("View Cart")').length > 0) {
					} else {
						jQuery('#customCart-<?php echo $product_id;?>').delay( 2200 ).append('<div class="response_to_cart"><a href="/cart/" class="added_to_cart wc-forward">View Cart</a></div>');
					}

					e.preventDefault();

					return false;

				});
			<?php

			} else { 
				//variable product script
				$args = array(
					'post_type'     => 'product_variation',
					'post_status'   => array( 'publish' ),
					'numberposts'   => -1,
					'orderby'       => 'menu_order',
					'order'         => 'asc',
					'post_parent'   => $product_id 
				);
				$variations = get_posts( $args );


				foreach($variations as $var_post) {

				?>

				jQuery('body').on('click', '#customCart-<?php echo $var_post->ID;?> .single_add_to_cart_button', function(e) {
					
					var pid = $(this).attr('data-pid');
					var qty = $('#qty-'+pid).val();



					console.log('pid var: '+pid);
					console.log(qty);

		          	addToCart(pid, qty);

					if (jQuery('#customCart-<?php echo $var_post->ID;?>:contains("View Cart")').length > 0) {

					} else {
						jQuery('#customCart-<?php echo $var_post->ID;?>').delay( 2200 ).append('<div class="response_to_cart"><a href="/cart/" class="added_to_cart wc-forward">View Cart</a></div>');
					}

					e.preventDefault();

					return false;

				});


			<?php    } 

				} //end variation loop

			?>

			<?php if ($includeQuote) { 
				//include quote in script output
				//echo '//'.time();
				?> 

				jQuery('body').on('click', '#customQuote-<?php echo $product_id;?> .single_adq_button', function(e) {

					var qid = $(this).attr('data-product-id');

					if (qid == '2612') {
						console.log('add travel fee for '+qid);
					}

					if (jQuery('#customQuote-<?php echo $product_id;?> .reponse_to_quote').length) {

					} else {
						console.log('show quote cart link');
					}

					if (jQuery('#customQuote-<?php echo $product_id;?>:contains("View Quote")').length > 0) {
					
					} else {

						jQuery('#customQuote-<?php echo $product_id;?>').delay( 2000 ).append('<div class="reponse_to_quote" style="display:block!important;"><a href="/quote-list/" class="added_to_quote wc-forward">View Quote</a></div>');
					
					}

					console.log('-end: '+quoteID);
					
					e.preventDefault();

				});

			<?php } ?>

			jQuery("#date-"+<?php echo $product_id; ?>+"").change(function () {
				var datechoice = $(this).find('option:selected').val();

				console.log(datechoice);
				if (jQuery.isNumeric(datechoice)) {
					if (datechoice != '') {
						displayVarient('<?php echo $product_id; ?>', datechoice);
					} else {
						jQuery('.selectDate').hide();
					}
				} else {
					jQuery('.selectDate').hide();
				}

			});

		});
		</script>

	<?php
		}
	}
}

}

function silibas_get_product_html($postID, $options, $text) {

	global $post, $woocommerce;

	$onsiteAdds = array(2612,64370,79377);

	$woo_product = wc_get_product( $postID );

	if ($woo_product) {
		$pro_type = $woo_product->get_type();
	} else {
		$pro_type = 'simple';
	}
	
	$postCode = '';
	
	wp_reset_query();

	$postInfo = get_post($postID);
	$postTitle = get_the_title($postID);
	
	$productImages = GetImageUrlsByProductId($postID);

	if (!empty($productImages)) {
		$imageURL = $productImages[0];
	}

	if ($text == 'text') {
		$text = '';
		$text = silibas_get_the_excerpt($postID);
	} elseif ($text == '') {
		$text = silibas_get_the_excerpt($postID);
	}
	
	if ($postID == 1892) {
		$text = silibas_get_the_excerpt($postID);
	}

	$text = apply_filters('the_content', $text);

	$postCode .= '<div class="estrelittaProduct epid-'.$postID.'">';

	if ($options[1] != 'img') {
		$postCode .= '<div class="product_image">';
		$postCode .= '<img src="'.$options[1].'">';
		$postCode .= '</div>';
	}

	if ($options[0] != 'false') {
		$postCode .= '<div class="product_title">';
		$postCode .= '<h3>'.$postTitle.'</h3>';
		$postCode .= '</div>';
	}

	$postCode .= '<div class="product_description"><hr>';


	if ($postID == 1892) {
		$postCode .= $text;
	} else {
		$postCode .= $text;
	}


	$isBundle = silibas_is_bundle($postID);
	$isBundle = false;
	
	if ($isBundle) {
		$bundleIDs = silibas_get_bundle($postID);

		$bundlePrices = '';
		$parentPrice = 0;

		foreach($bundleIDs as $b) {
			$bundleID = $b->product_id;
			
			$digitCount = 2;

			if (get_post_meta( $bundleID, '_regular_price', true) > 999) {
				$digitCount = 0;
			}

			$productPrice = get_post_meta( $bundleID, '_regular_price', true);
			if ($productPrice = '' || $productPrice == '0') {
				$productPrice = '0.00';
			}


			$bundlePrices .=  '<h5>'.get_the_title($bundleID).' $'.number_format($productPrice, $digitCount, '.', ',').'</h5>';

			$parentPrice = $parentPrice + get_post_meta( $bundleID, '_regular_price', true);

			if (get_post_meta( $bundleID, '_sale_price', true) != '') {
				$parentPrice = $parentPrice + get_post_meta( $bundleID, '_sale_price', true);
			}
		
		}

		if ($parentPrice > 999) {
			$postCode .= '<h3>$'.number_format($parentPrice, 0, '.', ',').'</h3>';
		} else {
			$postCode .= '<h3>$'.number_format($parentPrice, 2, '.', ',').'</h3>';
		}

		$postCode .= '<h4>Includes:</h4>';

		$postCode .= $bundlePrices;

	} else {



		if ($pro_type == 'simple') {

			$productPrice = get_post_meta( $postID, '_regular_price', true);
		    if (in_array($postID, $onsiteAdds)) {
		    	$productPrice = '4200';
			}
			if (get_post_meta( $postID, '_sale_price', true) != '') {
				$salePrice = get_post_meta( $postID, '_sale_price', true);
				$priceCode = '<h3><strike>$ '.number_format($productPrice, 2, '.', ',').'</strike> '.number_format($salePrice, 2, '.', ',').'</h3>';
			} else {
				
				$priceCode = '<h3>$ '.number_format($productPrice, 2, '.', ',').'</h3>';
			}

		} elseif ($pro_type == 'variable') {

			$onlinePDs = array(64375, 2624, 2620, 2618, 79351, 79361);
			if (in_array($postID, $onlinePDs)) {
				$priceCode = '<h3>$129.00</h3>';
			} else {
				$priceCode = silibas_get_variant_price_range($postID);
			}

		} else {
			$productPrice = get_post_meta( $postID, '_regular_price', true);
			$priceCode = '<h3>$ '.number_format(floatval($productPrice), 2, '.', ',').'</h3>';
		}
		
		$postCode .= $priceCode;

	}

	$postCode .= '</div>';

	$postCode .= '<div class="product_actions updatedEC-'.$post->ID.'">';
		
		/* pd regional trainings 
			east => 88875
			west => 88874
			north => 88876
			south => 88877
			midwest => 94336
		*/

	if ($post->ID == 88874) {
		//christina east
		$eccode = 'west';
	} elseif ($post->ID == 88875) {
		//jose east
		$eccode = 'east';
	} elseif ($post->ID == 88876) {
		//north
		$eccode = 'north';
	} elseif ($post->ID == 88877) {
		//south
		$eccode = 'south';
	} elseif ($post->ID == 94336) {
		//south
		$eccode = 'midwest';
	} elseif ($post->ID === 463) {
		$eccode = '';
	} else {
		$eccode = '';
	}

	$postCode .= silibas_get_cart_button($postID, $eccode);
	$postCode .= silibas_get_quote_var_button($postID, $eccode);

	$postCode .= '</div>';
	$postCode .= '</div>';

	return $postCode;

}

function silibas_get_the_excerpt($post_id) {
	  global $post;  
	  $save_post = $post;
	  $post = get_post($post_id);
	  $output = get_the_excerpt();
	  $post = $save_post;
	  return $output;
}

function silibas_get_variant_price_range($product_id) {

	global $woocommerce, $product, $post;

	$var_prices = array();

	$args = array(
		'post_type'     => 'product_variation',
		'post_status'   => array( 'publish' ),
		'numberposts'   => -1,
		'orderby'       => 'menu_order',
		'order'         => 'asc',
		'post_parent'   => $product_id 
	);
	
	$variations = get_posts( $args );

	$prices = '';

	foreach($variations as $var_post) {
		$product_variation = new WC_Product_Variation($var_post->ID);
		$regular_price = $product_variation->regular_price;
		$var_prices[] = $regular_price;
	}

	$var_prices = array_unique($var_prices);

	if (count($var_prices) == 1) {
		$priceRange = '<h3>$'.number_format($var_prices[0], 2, '.', ',').'</h3>';
	} else {
		$minPrice = array_keys($var_prices, max($var_prices));		
		$maxPrice = array_keys($var_prices, max($var_prices));
		if ($minPrice == $maxPrice) {
			$priceRange = '<h3>$'.number_format($minPrice, 2, '.', ',').'</h3>';
		} else {
			$priceRange = '<h3>$'.number_format($minPrice, 2, '.', ',').' - $'.number_format($maxPrice, 2, '.', ',').'</h3>';
		}
	}
	

	return $priceRange;

}


function silibas_is_bundle($productID) {

	global $wpdb;

	$productIDs = $wpdb->get_results($wpdb->prepare("SELECT product_id FROM " . $wpdb->prefix . "woocommerce_bundled_items WHERE bundle_id = '" . $productID . "'", 'ARRAY_A'));

	if (!empty($productIDs)) {
		return true;
	} else {
		return false;
	}


}

function silibas_get_bundle($productID) {

	global $wpdb;

	$productIDs = $wpdb->get_results($wpdb->prepare("SELECT product_id FROM " . $wpdb->prefix . "woocommerce_bundled_items WHERE bundle_id = '" . $productID . "'", 'ARRAY_A'));

	return $productIDs;

}

function scrm_excerpt_by_id($post, $length = 10, $tags = '<a><em><strong>', $extra = ' . . .') {

	if(is_int($post)) {
		$post = get_post($post);
	} elseif(!is_object($post)) {
		return false;
	}

	if(has_excerpt($post->ID)) {
		$the_excerpt = $post->post_excerpt;
		return apply_filters('the_content', $the_excerpt);
	} else {
		$the_excerpt = $post->post_content;
	}

	$the_excerpt = strip_shortcodes(strip_tags($the_excerpt), $tags);
	$the_excerpt = preg_split('/\b/', $the_excerpt, $length * 2+1);
	$excerpt_waste = array_pop($the_excerpt);
	$the_excerpt = implode($the_excerpt);
	$the_excerpt .= $extra;

	return apply_filters('the_content', $the_excerpt);
}


function GetImageUrlsByProductId( $productId){
 
 	global $post;
 	
 	wp_reset_query();

 	$imgUrls = array();

	$args = array(
	   'post_type' => 'attachment',
	   'numberposts' => -1,
	   'post_status' => null,
	   'post_parent' => $productId
	  );

	  $attachments = get_posts( $args );
	     if ( $attachments ) {
	        foreach ( $attachments as $attachment ) {
	           $imgUrls[] = wp_get_attachment_image( $attachment->ID, 'full' );
	          }
	     }
	
	return $imgUrls;
}

/**
 * Add these few lines of code to your theme's
 * functions.php to enable HTML excerpts
 *
 */
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'html_trim_excerpt');
/**
 * Original function: wp_trim_excerpt
 * http://core.trac.wordpress.org/browser/tags/3.3.1/wp-includes/formatting.php#L1894
 */
function html_trim_excerpt($text = '') {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
		$text = str_replace(']]>', ']]&gt;', $text);
		$excerpt_length = apply_filters('excerpt_length', 55);
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$text = html_trim_words( $text, $excerpt_length, $excerpt_more );
		$text = apply_filters('the_content', $text);
	}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
/**
 * Original function: wp_trim_words
 * http://core.trac.wordpress.org/browser/tags/3.3.1/wp-includes/formatting.php#L1920
 */
function html_trim_words( $text, $num_words = 55, $more = null ) {
	if ( null === $more )
		$more = __( '&hellip;' );
	$original_text = $text;
	/**
	 * Remove space characters between html
	 * tags to avoid not matching the pattern
	 */
	$text = preg_replace('/(?<=>)[\s]*(?=<)/', '', $text);
	/**
	 * This is the best I could think of
	 * It doesn't catch any nested elements,
	 * only their parents as a whole
	 */
	$token_re = "/<[^>]+?\/>|<([a-z]+[1-6]*)[^>]*>.*?(?!<\\1)<\/\\1>|[^><\s]+/iu";
	preg_match_all($token_re, $text, $words);
	$words_array = $words[0];
	/**
	 * Need to count the number of real
	 * words so we get result as accurate
	 * as possible
	 */
	$count_array = array();
	$wordcount = 0;
	foreach ($words[0] as $key => $value) {
		$value = wp_strip_all_tags($value);
		$words = preg_split('/[\s]/', $value, -1, PREG_SPLIT_NO_EMPTY);
		$wordcount += $count_array[$key] = count($words);
	}
	if ($wordcount > $num_words) {
		while ($wordcount > $num_words) {
			array_pop($words_array);
			$wordcount -= array_pop($count_array);
		}
		$text = implode( ' ', $words_array );
		$text = $text . $more;
	} else {
		$text = implode( ' ', $words_array );
	}
	return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
}



add_shortcode('pdmap', 'estrellita_pd_map');

function estrellita_pd_map($atts) {
	extract( shortcode_atts( array(
		  'type' => 'type',
	 ), $atts));

	$mapCode = '';

	$mapCode .= '<div class="mapwrapper">';
	$mapCode .= get_map_svg();
	$mapCode .= '</div>';

	return $mapCode;

}

function get_map_svg() {
	$svg = '';
$svg .= '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"';
$svg .= '	 viewBox="0 0 959 593" style="enable-background:new 0 0 959 593;" xml:space="preserve">';
$svg .= '<style type="text/css">';
//east FFFFD9
//west 74A9CF
//midwest FC8D59
$svg .= '	.st0 {fill:#FFFFD9;stroke:#333;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}';
$svg .= '	.st1 {fill:#74A9CF;stroke:#333;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}';
$svg .= '	.st2 {fill:#FC8D59;stroke:#333;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}';
$svg .= '	.st3 {fill:#FFFFD9;stroke:#333;stroke-linecap:round;stroke-linejoin:round;}';
$svg .= '	.st4{font-family: "Cabin", sans-serif;}';
$svg .= '	.st5{font-size:12px;}';
$svg .= '.st6{fill:none;stroke:#000000;stroke-miterlimit:10;}';

$svg .= '.westregion, .midwest, .eastregion {transition: all .7s ease-out;}';
$svg .= '.region:hover {cursor:pointer;} html:has(.westregion:hover) .westregion {fill:#4f748e;} .westregion:hover {fill:#4f748e;}';
$svg .= 'html:has(.midwest:hover) .midwest {fill:#b76640;} .midwest:hover {fill:#b76640;} .midwest:hover {fill:#b76640;}';
$svg .= 'html:has(.eastregion:hover) .eastregion {fill:#bebe89;} .eastregion:hover {fill:#bebe89;} .eastregion:hover {fill:#bebe89;}';
$svg .= '</style>';

$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M72.8,532.1h-1.3l-2.1,3.1v3c0.1,0-0.7,1.7-0.7,1.7l-2.7,4.4v1.7c0.1,0-0.6,1.3-0.6,1.3l-1.8-0.4l-1,0.9';
$svg .= '	l0.4,1.7l-2.4,0.4h-2.5c0-0.1-4,1.4-4,1.4l-3.1,0.3l2.5,0.4l-2.1,0.6H53v1h-1.2v0.7l0.7,0.3l-0.6,1.7l-0.7-1.1l0.3-1.1l-1-0.6';
$svg .= '	l-1.5,0.4l-2.1-0.6l0.4,1.3l1.3,0.4l0.4,1.3l1.5,0.7l0.4,1.1l-1,2l-0.4,2.7l-1,1.6l1.2,0.7l1,3.7l0.4,2.8l0.9,0.7l0.6,0.9h1l1.9,0.9';
$svg .= '	l1.5-0.3h0.6c0,0.1,2.8-2.6,2.8-2.6l1.2-1l1-1.7v-3.4l-0.7-0.6h0.7v-2.8c-0.1,0,0.4-2.3,0.4-2.3l-0.3-0.6l0.9-0.6l0.6-1.6l-0.3-0.9';
$svg .= '	l2.4-0.6l0.9-1.3h1.2c0-0.1,1.3-1.7,1.3-1.7l3.6-2.8l1.8-1l0.3-1.3l3.1-1.4l1.5-3v-1.4c0.1,0,1.2-1.3,1.2-1.3l0.7-2.4h-3.6';
$svg .= '	c0-0.1-1.8-0.6-1.8-0.6l-0.7-1.8l-3.3-3.1L72.8,532.1z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M135.4,555.2v1.1l-0.3,1.3l0.4,1.4l-1.3,1.4v1.3l1.2,2h1l1,1.6l-0.6,3l-0.4,0.6v0.7L136,571l0.4,1.6h-1';
$svg .= '	c0-0.1-0.7,0.7-0.7,0.7v0.7c0.1,0-1,1.6-1,1.6h-1.3l-0.6-2.3l0.3-1.3l-0.7-1.4l-1.5-1v-1.1c-0.1,0-0.6-1.1-0.6-1.1l-0.7-0.3';
$svg .= '	l-0.9,0.4l-1.3-0.9l0.3-1.8l-0.6-0.9l0.7-2.6l1-0.3v-1.7l4.3-4.1l1.5-1.7h0.6l1.3,1.7L135.4,555.2z M157.1,532.2l-0.4-0.3l-0.7-1.3';
$svg .= '	l-1.3-0.6l-1.9,1.7l-1.8,2l-0.6,0.9l-3.3,1.7l-1.5,1.3l-1.2,1.1h-1.5l-0.3,2.8l-0.9,3.7l-1,1.4v1c-0.1,0-1,2.3-1,2.3v1.3l1.8,0.9';
$svg .= '	l0.3,0.9h3.7l0.9,0.6v1c0.1,0,0.9,0.1,0.9,0.1l0.4-1.8l0.4-0.9v-1.1c-0.1,0-1.2-0.3-1.2-0.3l-0.7-1l1-3.3l1.3-0.4l0.9,0.4l0.3,0.7';
$svg .= '	l2.5,0.4l0.3-1.4l0.4-0.6l-1-0.9l-0.4-1l-0.7-0.9v-1c-0.1,0,0.3-0.3,0.3-0.3v-0.6l-0.3-0.7l1.8-2.4l1-0.3l1-1.3l0.3-2.1h1.8';
$svg .= '	c0-0.1,0.4-0.9,0.4-0.9v-1V532.2z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M359.4,551.1h2.6l0.7-1v-1c0.2,0-1-1-1-1l-1.2-0.5l-1.6,0.5l-1,1.8l1.5,1L359.4,551.1z M357.9,538.1l-2,0.7h-3';
$svg .= '	c0-0.2-1.2,1-1.2,1h-0.7c0-0.2-0.7,1-0.7,1l0.5,1.8l-1.6,0.5v-1.2c-0.2,0-4.1-1.2-4.1-1.2l-0.5,1.2l-2.6-1.8l-2.1,1.5h-1.6';
$svg .= '	c0,0.2-1-0.5-1-0.5l1.5-1v-0.7c-0.2,0-1.6,0.2-1.6,0.2l-0.7-1l-1.2,0.7l-0.7-0.7l-0.7,1.6l-1-1.2v1.8c-0.2,0-1.5,0.2-1.5,0.2v1';
$svg .= '	c-0.2,0-1-0.7-1-0.7l0.5-2.3h-0.7l-1.2,1.2l-0.7-1.8h-2.1c0,0.2-1.2,1.2-1.2,1.2h-1c0-0.2,1.2-2.3,1.2-2.3v-1.5l-2.1,1.8h-1.2';
$svg .= '	l-0.7,1h-0.7c0-0.2-1.2,0.2-1.2,0.2l0.5,1l-0.5,0.5l-1-0.5l-2.5,1v0.7c0.2,0-0.7,1.6-0.7,1.6h-2.5c0-0.2-0.5,1.5-0.5,1.5l-3,2.6v2.1';
$svg .= '	l0.7,1l-0.7,1l-0.5-1l-2-1l-2.5,1.6v1c0.2,0-3,0.2-3,0.2l-1.2,1.5l-0.5-1.5h-1.6v-1c0.2,0-1,0-1,0l-0.5,0.5h-0.5c0-0.2-2,0.7-2,0.7';
$svg .= '	H294c0-0.2-1.2,0.7-1.2,0.7l-1-2.1l-3.1,1.5l-1.5-0.7l-1,1h-2.5c0,0.2-0.7,1.8-0.7,1.8l-1.2,0.5l-0.5,1l-1.2-1.2l-4.6,5.4l-2.6,1';
$svg .= '	l1.6,1.8h2.1c0-0.2,1,1,1,1l3.9,0.7l1.5-1.8l1.6,1.8l2.1,0.5h0.7c0-0.2,3,1.8,3,1.8l-0.5,1.8l0.7,1.6v1.6l5.1,2.3l1-0.7h1.5';
$svg .= '	c0,0.2-0.2,1.6-0.2,1.6l0.7,2.1l2.1-2.8l-0.5-1l1.2-1l2.6,2.1l1.2-2.3l1.5-0.7l1.6-2.6h1l0.7-1v-1.5l6.9-3.9h-3.5';
$svg .= '	c0-0.2-1.6-2.8-1.6-2.8l3.1-1.6l1,0.7l-1,2.3h0.7l1.5-2.6l4.4-3.9h1.6c0-0.2,0.7-1.8,0.7-1.8l-1.6-1l2.1-1.8l-2-1.2v-1.6';
$svg .= '	c-0.2,0-2.5,0.5-2.5,0.5l-3.1-1.6h4.1l2.1-1l3.9,0.7l-0.5,2.8l1,3h2.6l4.9-1.2l0.5-1.6h2.6l2.1-2.6l2.6-1.2l2,1.2l0.7,1.2l-0.7,1.2';
$svg .= '	l1.5,1.2l1.6-1.2h1.5l2.5-1.8h1.6c0,0.2,1.2-0.7,1.2-0.7l3-0.5l1-3.8l-1.2-2.1l1-1.6L357.9,538.1z"/>';
$svg .= '<text transform="matrix(1 0 0 1 161.3499 537.7075)" class="st5">Nothern Mariana</text>';
$svg .= '<text transform="matrix(1 0 0 1 161.4499 548.7075)" class="st5">Islands</text>';
$svg .= '<text transform="matrix(1 0 0 1 21.4997 537.1155)" class="st5">Guam</text>';
$svg .= '<text transform="matrix(1 0 0 1 366.0998 537.5034)" class="st5">American Samoa</text>';
$svg .= '<g>';
$svg .= '	<path class="st0 region eastregion" data-linkto="pd-east" d="M936.8,408.8l0.9,1.4l1.2,1.4l1,0.6l-0.3-1.1h1.9c0,0.1,0-0.9,0-0.9l-2.1-0.9l-1.3,0.3l-1.3-0.9L936.8,408.8z';
$svg .= '		 M940,418.2l-0.3-0.6l-1,0.3l-3.4-0.9h-2.2c0,0.1-0.6,0.7-0.6,0.7l-2.1,0.3l-1.9,0.9l-1.5,0.3l0.7,1.4h1.5c0,0.1,1.8-0.4,1.8-0.4';
$svg .= '		h1.5c0,0.1,1.3-0.1,1.3-0.1v-0.4c-0.1,0,1.8-0.1,1.8-0.1l0.9-0.6l0.4,0.4l1.8-0.7L940,418.2L940,418.2z M857.9,403.5v2.7';
$svg .= '		c0.1,0-4.3,2-4.3,2v1.3c-0.1,0,1.3,1.8,1.3,1.8l1.9,0.7l0.7,3.8l-1.2,4.5v3.1c0.1,0-0.9,2.3-0.9,2.3l1.5,1.6l0.3-1.3l3.7,0.7';
$svg .= '		l1.2-1.3l3,0.3l3,1.7l6.1-1.7l0.7-1.3l4.3,1.4h4.5l3.9-1.1l4,1.8l2.2-0.9l4.5,1.6l1.3-1.1l1.8,1.1l3.9-1.6l1.3,0.6l1.2-1h1.6';
$svg .= '		c0,0.1,2.4,0.4,2.4,0.4l2.5-1.7l1.9-2.3h0.9c0-0.1,1.6-3.6,1.6-3.6l1.5-2.1l3.7-0.7l0.6-1.3l0.9,0.9l0.4-1.3l-1.5-1.1v-3.6';
$svg .= '		c0.1,0,0.6-1.7,0.6-1.7l-2.4,0.9l-5.4-2.1l-4.5-1.4l-4.2-0.4l-4.8,0.4l-13-2l-2.2,1l-7.8-0.9l-3.4,0.6l-4-0.6l-4.5,0.3l-5.8-1.3';
$svg .= '		H859c0,0.1-1.6,1.7-1.6,1.7L857.9,403.5z"/>';
$svg .= '	<path class="st0 region eastregion" data-linkto="pd-east" d="M888.1,532.9l1.2,3.1l-0.4,3.3l-1.8,1.4l0.3,1l2.2-1.4h4.8l0.9,0.4l3-1l3.3-0.6l2.5,0.4l4.2-1.6l1.8,0.7l4-1';
$svg .= '		l4.2-2h1.8l3.4-1.4l1.5-1.6h2.5c0,0.1,1.5-1.1,1.5-1.1l-1.5-0.7l-3.4,0.7l-1-0.9l-2.8,0.3l-2.2-0.3h-3.3c0,0.1-1.5,0.9-1.5,0.9';
$svg .= '		l-1,0.7H911c0-0.1-2.5-1.8-2.5-1.8l-1.8-2.3h-0.6c0,0.1-0.4,1-0.4,1l-3.4-1.3l-6.6,3l-2.2-0.3l-2.4-0.9l-1.8,0.7l-0.9,2.4';
$svg .= '		L888.1,532.9z M905.7,502.5l0.9,0.7l-0.3,0.9l-1.2,0.4l-2.5,0.3v0.6c-0.1,0-1-0.9-1-0.9l-0.3,1.6l-1.5,0.7v1.6';
$svg .= '		c0.1,0,2.5,1.6,2.5,1.6l0.9-1l1.9,0.3l1.2-0.7l1.5,0.9l1.6-0.3v1.1c-0.1,0,1.8,0.1,1.8,0.1l0.7,1l0.4-2.6v-1.1';
$svg .= '		c-0.1,0-1.8-1.7-1.8-1.7l1.2,0.4l0.7-0.3l-0.6-1.4h1.2c0-0.1,0.7,1.8,0.7,1.8l1-0.7v1.6h1l1-0.4l0.3-1l-3.3-1.4l-0.6-0.7h-1.8';
$svg .= '		c0-0.1-0.6-0.9-0.6-0.9h-1.5v0.4c-0.1,0-1.8-0.1-1.8-0.1v-0.9c0.1,0-2.1,0.1-2.1,0.1H905.7z M886.9,498.5l1,1.4l-1.2,0.9l-1.3-2.4';
$svg .= '		h1.5V498.5z M871.4,503.9l-0.9,1.3l1.9,1.1l3.7-1.1l1.9,1l-0.7,0.6l3.9,1.7l-1,1.8l0.9,0.3l0.7-1.7l2.1-0.4l-1-1.8h1.6';
$svg .= '		c0,0.1,0.1,2.1,0.1,2.1l2.2,1.1h1.8l1.5,1.1l1.3-0.3l-0.3-1.4l1.8-0.3l1.3,0.9l1-0.7l-1.5-1.1l0.7-0.6l-3.1-2.4l-2.5-0.6v-0.7';
$svg .= '		l-3.4-0.7l-2.8-2l1.9,2.1l-0.4,0.6h-0.7c0-0.1-2.1-2-2.1-2v1c-0.1,0-4.9-0.3-4.9-0.3l-0.4,1.1l-1.2-0.7l-0.6,0.9h-2.8V503.9z"/>';
$svg .= '	<text transform="matrix(1 0 0 1 863.9 390.9)" class="st5">Puerto Rico</text>';
$svg .= '	<text transform="matrix(1 0 0 1 866 476.8)" class="st5">United States</text>';
$svg .= '	<text transform="matrix(1 0 0 1 866.2 487.8)" class="st5">Virgin Islands</text>';
$svg .= '</g>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M667,430.4l0.4-6.7l-0.8-1.1L665,422l-2.3-2.6l0.5-2.7L708,412l-0.6-2l-1.4-1.4l-0.5-1.3l0.6-5.8l-2.2-5.2';
$svg .= '	l0.5-2.4l0.3-3.4l2-3.5l-0.2-1l-1.6-0.9v-2.9l-1.7-1.7l-2.7-5.6l-11.9-42.1l-42,3.7l1.2,1.8l-1.2,61.6l4,30.5l0.8-0.5h1.2l0.6,0.5';
$svg .= '	h0.7l1.8-3.6v-2.1l1-1l1.3,0.5l3.1,5.9v0.8l-3,2l3.2-0.4l4.5-1.5l1-0.6L667,430.4z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M207.7,356.8l2.8-2l0.7-2.2l-0.9-1.5l-1.7-0.2l-1-1.5l1-6.3l1.5-0.3l2.2-2.9l1.5-6.4l2.2-3.3l4.4-1.6l1.2-1.2';
$svg .= '	l-0.4-1.7l-2.1-2.3l-1.1-5.3l-1.3-1.7l-1.2-3.1l0.8-1.9l1.3-2.8l0.5-2.7l-0.5-4.5l0.9-12.5l3.2-0.6l3.4,1.3l1.1,2.5h1.8l2.2-2.7';
$svg .= '	l3.1-16.1l42.4,7.5l36.8,5.5l-16,114l-34.3-5l-59-34.5l0.5-2.7l1.8-1.7h2L207.7,356.8z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M613,338.1l0.8-2l1.1,0.5l0.6-0.9l-0.7-0.6l0.3-1.4l-1-0.8l0.6-0.9v-1.4h-1.1l0.7-0.7l1.2,0.7l0.3-1.3l-0.4-1';
$svg .= '	v-0.6l1.9,0.6l-0.4-1.4l1.5-1.2l-0.5-0.8h-1l-0.6-0.7l0.8-0.8l1.5-0.2l0.5-0.7l1.3-0.2v-0.7l-0.9-0.8v-0.5h1.4l0.4-0.6l-1.3-0.9';
$svg .= '	v-0.6l-10.4,0.7l2.6-4.7l1.6-1.4v-2l-1.5-2.3l-36.6,1.8l-35.9,0.6l3.8,22.4l-0.6,35.8l2.4,2.1l2.6-1.2l2.9,0.7l0.2,10.9l48.1-1.2';
$svg .= '	l1.1-1.4l0.5-2.8l-1.4-2.1l-0.5-2l0.8-0.6v-0.7l-1.6-1v-0.6l1.4-0.8l-1.1-1l1.6-6.5l3.1-1.5v-0.7l-1-1.3l2.7-5h1.7l1.4-1.1l-0.3-4.8';
$svg .= '	l2.8-4.1l1.7-0.6l-0.5-2.8l1.5-0.6L613,338.1z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M165.3,352l-0.9-2.8l0.2-2.8l-0.4-7.3l-1.7-4.4l-1.1-1.3l-0.6-1.4l-6.4-7.9h-3.3l-1.8-1.7l1-1.7l-0.6-3.4';
$svg .= '	l-2-1.1l-3.6-0.6l-2.6-1.2l-1.4-1.7l-4.1-6.1l-2.5-2l-3.4-0.5l-2.8-2.1l-4.3-1.4l-2.6-0.3l-2.3-2.3l0.2-2.6l0.7-4.4l1.7-4.7';
$svg .= '	l-1.3-1.5l-3.7-8.6l-2.5-3.4l-0.4-2.8l-1.5-2.1l0.2-2.3l-1.8-4.6l-2.7-2.5l0.6-6.5l2.2-0.7l1.7-2.8l-0.4-2.9l-0.9-0.8h-2.3l-2.3-3';
$svg .= '	l-1.4-3.2v-6.9l1.1-3.9l0.2-1.9l2.3,0.2v1.5l-0.8,0.6v2.3l3.4,2.9v-4.3l-1.3-3.1l0.5-1l-0.9-1.6l2.6-1.4l-1.7-2.8l-1.3,0.5l-1.4,3.5';
$svg .= '	l0.5,1.2l-0.7,0.9h-0.8l-5-5.7l0.6-5.1l-1-3.6l-6-11.8l0.7-9.8l2.1-3.3l0.2-5.9l-5.1-10.2l0.3-4.8l6.3-6.9l1.6-2.2v-1.3l3.6-8.5';
$svg .= '	v-7.7l0.9-2.3l60.7,17.1l-15.1,58l1,3.2l64.7,96.5l-0.8,1.9l1.2,3.1l1.3,1.7l1.1,5.3l2.1,2.3l0.4,1.7l-1.2,1.2l-4.4,1.6l-2.2,3.3';
$svg .= '	l-1.5,6.4l-2.2,2.9l-1.5,0.3l-1,6.3l1,1.5l1.7,0.2l0.9,1.5l-0.7,2.2l-2.8,2h-2l-40.4-4.8L165.3,352z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M423.7,297.7l-15.2-0.9l-47.5-4.4l-48.3-6l10.6-81.1l41.3,5.2l34.5,3.1l30.4,2.2l-1.3,20.3l-4.4,61.6H423.7z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M878.8,165.3l0.4-1l-2.9-11.3v-0.3l-13.8,3.1v0.6l-0.8,0.3l-0.5-0.6l-9.6,2.2l2.6,15l1.7,1.4l-3.2,3.1l1.6,2';
$svg .= '	l5-4.1l1.6-1.2h0.7l2.2-2.8h1.3l2.7-0.9h1.9l4.9-2.5l2.6-0.8l0.9-0.9l1.4,0.5l-0.4-1.7L878.8,165.3z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M831.6,209.1l-1.5,0.3l-1.4,1l-1.1,1.9l7,24.9l10-2.1l-2-7l-1,0.5l-3-2.4l-0.5-1.6l-1.7-0.9l-0.2-3.4l-1.9-2';
$svg .= '	l-1-0.7l-1.1-1l-0.4-2.9l0.3-1.9l0.9-2l-1.5-0.6L831.6,209.1z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M766.9,409.9l-3.7-0.6l-1.6-0.8l-2,1.3v2.3l1.3,1.9l-0.5,4l-1.9,0.6l-0.9-1l-0.6-2.9l-46,3l-3-5.5l-44.8,4.7';
$svg .= '	l-0.5,2.7l2.3,2.6l1.6,0.6l0.8,1.1l-0.4,6.7l-1,0.6l0.5,0.4l0.9-0.3l0.6-0.7l9.6-2.5l8.5-0.5l7.4,1.7l7.8,4.6l2.2,0.7l2,1.8v2.5h2.1';
$svg .= '	l1.7-0.9h2.3l1.8-0.6l2.7-1.8l2.8-2.7l1-0.4l0.6,0.5h1.3l0.5-0.7l-0.5-1.1l-0.6-0.6l0.2-0.7l1.8-1l4.6-0.4l0.7,0.9h0.9l2.1,1';
$svg .= '	l2.8,1.7l1.1,1.6l1,1.1l2.6,1.3v2.2l2.6,1.7h0.9l1.5,1.4l0.6,1.5l0.9,0.2l0.7,1.9l0.6,0.6l0.9-1h2.7l0.5,1.4l1,0.8v1.2l2.7,2';
$svg .= '	l0.2,8.8l-1.7,5.3l0.9,1.1l-0.2,3.1l-0.7,1.3l0.6,1.1l2.1,2.1l0.3,1.4l0.7,0.9l-0.4-1.7l1.2-0.6l0.7-3.3l-2.8-1.1v-0.6l2.5-0.4';
$svg .= '	l0.8,2.4l1,0.6v-1.8l1.1,0.3l0.6,0.7v0.6l-2.8,3.9l-0.2,1l-1.6,1.7v1l3.4,3.5l4.9,7.3l1.7,1.9v1.7l2.6,4.2l2.1,0.6l0.6-1.1l-1.9,0.3';
$svg .= '	l-2.8-4.1l0.2-1.3l1.4-0.7v-1.4l-0.6-1.2l0.8-0.8l0.4,0.8l0.6,0.5v3.7l-1.1-0.6l-0.7,0.8l1.3,1.5l0.9,2.4l1.1-0.6l2.1,1.1l1.9,2';
$svg .= '	l1.5,4.7l2.8,4.4l0.7-1.2l2.6-0.5l2.9,1.2l0.3,1.6l3,3.5v1l2.1,2.5l-0.6,0.5v2.5l2.5,1.3h1.4l2.5-1.7l1.4,0.3l1,0.4l2.1-1.6l0.2-0.6';
$svg .= '	l1.1,0.3l2.2-1.6l1.2-2.1l-0.6-2.9l-0.2-1.2l1-3.7l0.6-0.2l0.6,1.5l0.7-1.7l-0.7-6.6l-0.4-9.6l-0.9-6.2l-0.6-1.6l-6.1-10.2l-4.8-8.4';
$svg .= '	l-2-3l-1.2-3.3l-0.2-3.1l0.8-0.3v-0.8l-1-2l-3.7-3.7l-7-8.9l-5.2-9.6l-4-9.8l-0.6-3.4l-1.1-0.9l-0.5-3.5l-3.3,0.3L766.9,409.9z';
$svg .= '	 M775.3,533.5h1.6l-0.6-1L775.3,533.5L775.3,533.5z M782,532.5v-0.6l1.5-0.2l3.4-3l1.4-0.6l2.2-0.8l0.3,1.2l1.6,0.7l-2.4,1.1h-2.2';
$svg .= '	l-3.6,2.3h-2.1L782,532.5z M797.8,525.5l-2.8,1.3l-0.9,1.2h1L797.8,525.5z M801.3,522.8l-1,0.3l-1.3,1.8l1-0.2l1.4-1.5v-0.5';
$svg .= '	L801.3,522.8z M808.9,508.4l-1.6,5.1l-0.7,0.9l-0.9,2.4l-1.1,1.5l-0.6,1.6l-1.7,2v0.8l2.5-2.6l2.2-3.2l0.6-1.8l1.9-4.5l-0.5-2.2';
$svg .= '	L808.9,508.4z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M776.1,381.4v1.3l-3.9,5.7l-1.1,0.2l1.4,0.5v1.8l-0.8,1l-0.6,5.5l-2.1,5.7l0.5,1.8l0.6,4.7l-3.3,0.3l-3.7-0.6';
$svg .= '	l-1.6-0.8l-2,1.3v2.3l1.3,1.9l-0.5,4l-1.9,0.6l-0.9-1l-0.6-2.9l-46,3l-3-5.5l-0.6-2l-1.4-1.4l-0.5-1.3l0.6-5.8l-2.2-5.2l0.5-2.4';
$svg .= '	l0.3-3.4l2-3.5l-0.2-1l-1.6-0.9v-2.9l-1.7-1.7l-2.7-5.6L688.5,333l21-2.7l19.7-2.8v1.7l-1.8,0.9l-1.3,2.9l0.2,1.2l5.6,3.5l2.4-0.3';
$svg .= '	l2.8,3.7l0.4,1.6l3.9,4.7l2.4,1.6l1.3,0.2l2,1.5l1,2l1.8,1.5l1.7,0.5l2.5,2.5v1.3l2.5,2.6l4.6,2.1l3.3,6.2l0.3,2.5l3.6,1.9l2.3,4.4';
$svg .= '	l0.7,2.8l3.9,0.4l0.8,0.4V381.4z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M231.3,168.9l-22.4-5l7.8-34.3l2.7-5.3l0.4-1.9l0.7-0.8l-0.8-1.8l-2.7-1.1l0.2-3.9l3.7-5.3l2.3-0.7l1.5-2.1';
$svg .= '	v-1.5l1.6-1.5l2.9-5.1l3.9-4.4l-0.5-2.9l-3.2-2.8l-1.5-3.3l1-4l-0.6-3.7L240,26l13,2.8L248.6,49l3.4,6.8l-1.5,4.4l3.3,4.4l1.7,0.6';
$svg .= '	l3.6,7.6v1.9l2.1,2.8h0.8l1.3,1.9h2.9v1.5l-6.5,15.6l-0.5,3.8l1.3,0.5l1.5,2.4l2.6-1.3l3.3-2.2l1.7,1.7l0.5,2.3l-0.5,2.9l2.3,8.9';
$svg .= '	l2.4,3.2l2.1,1.3l0.4,2.8v3.8l2.1,2.1l1.5-2.1l6.3,1.5l1.9-1.1l8.3,1.6l2.6-3l1.7-0.6l1.1,1.7l1.5,3.8h0.8l-7.8,50.4l-44-7.5';
$svg .= '	l-21.4-4.3L231.3,168.9z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M649.1,245.2l-0.9,4.8v1.8l2.2,3.2v0.6l-0.3,0.8l0.8,1.7l-0.3,2.2l-1.5,1.7l-1.2,3.9l-3.5,4.9v6.4h-1l0.8,1.7';
$svg .= '	v0.8l-2,2.5v1l1.5,2v0.8l-3.5,0.6l-0.6,1.1l-1.1-0.6l-0.9,0.5l-0.4,3l1.6,1.7l-0.4,2.2l-1.4,0.3l-6.3-2.8l-3.7,3.4l0.3,1.7h-2.6';
$svg .= '	l-1.3-1.4l-1.7-3.5v-1.7l0.7-0.6v-1.2l-1.5-1.7l-0.8-2.3l-2.5-3.8l-4.4-1.2l-6.8-6.5L606,271l2.6-7l-0.4-1.7l1.1-1v-1.2l-2.6-1.4';
$svg .= '	l-2.8-0.6l-3.1,1.1l-1.2-2.1l0.6-1.7l-0.6-2.2l-7.9-7.7l-2-1.4l-2.3-5.4l-1.1-5l1.3-3.4l0.6-0.6v-2.1l-0.6-0.8l0.9-1.4l1.7-0.6';
$svg .= '	l0.8-0.3l0.9-1.1v-2.2l1.6-2.2l0.5-0.5v-3.2l-0.7-1.3l-0.9-0.3l-1-1.5l0.9-3.7l2.8-0.7h2.2l3.9-1.7l1.6-2v-2.2l1.1-1.2l1.2-2.9v-2.4';
$svg .= '	l-2.7-3.2h-1.1l-0.8-1l0.2-1.5l-1.6-1.6l-2.3-1.2l0.5-0.6l42.2-2.6v4.2l3.2,4.2l1.1,3.8l1.5,2.9l3.8,46.1L649.1,245.2z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M654.3,198.3l-4.7,2.1l-4.3-1.3l3.8,46.1l-0.9,4.8v1.8l2.2,3.2v0.6l-0.3,0.8l0.8,1.7l-0.3,2.2l-1.5,1.7';
$svg .= '	l-1.2,3.9l-3.5,4.9v6.4h-1l0.8,1.7l1,0.7l0.6-0.9l-0.6-1.6l4.2-0.5l0.2,1.1l1,0.2l0.4-0.8l-0.6-1.2l0.3-0.7l1.2,0.7l1.6-0.4l1.6,0.6';
$svg .= '	l3.1,1.9l1.7-2.6l3.2-2l2.8,3l1.5-1.9l0.3-2.5l3.5-2.1l0.2,1.2l1.7,1.1l2.8-0.2l1.1-0.6v-3.1l2.4-3.4l4.2-4v-1.6l1-3.5l2,0.9';
$svg .= '	l6.2-4.1l-0.4-1.6l-1.4-1.9l0.9-1.7l-6.1-52.6v-1.3l-29.9,3.1l-1.6,1.5V198.3z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M588.2,168.8l1.9,1.5l0.6,1l-1.5,3v2.3l1.7,5.1l2.5,1.4l3,0.6l1.2,2.6l-0.5,0.6l2.3,1.2l1.6,1.6l-0.2,1.5l0.8,1';
$svg .= '	h1.1l2.6,3.2v2.4l-1.1,2.9l-1,1.2v2.2l-1.7,2l-3.9,1.7h-2.2l-2.8,0.7l-0.9,3.7l1,1.5l0.9,0.3l0.8,1.3v3.2l-0.6,0.5l-1.6,2.2v2.2';
$svg .= '	l-0.9,1.1l-0.8,0.3l-1.7,0.6l-0.9,1.4l0.6,0.8v2.1l-0.7,0.6l-1.4-0.7l-1-1l-0.6-1.5l-1.6-1.2l-13.1,0.7l-25,1.1h-23.8l-1.7-4.1';
$svg .= '	l0.6-2l-0.7-3l0.2-2.7l-1.2-0.6l-0.4-5.6l-2.6-4.6l-0.2-3.4l-2-4l-1.2-3.4V193l-0.6-1.6v-2.1l-0.5-0.8l-0.6-1.6l-0.3-1.2l-1.2-1.1';
$svg .= '	l0.9-4l1.6-4.7l-0.6-1.8l-1.2-0.4l-0.4-1.5l0.9-0.5v-1l-1.1-1.4v-1.5h2.1H537l33.4-0.8l17.1-0.6l0.2,2.4L588.2,168.8z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M498,239.4l-40.2-1.1l-33.1-1.8l-4.4,61.6l62.2,2.7h57l-0.5-44.1l-2.9-0.6l-2.4-4.3l-2.3-2.3l0.5-2.1l2.5-2.4';
$svg .= '	v-1.1l-1.3-1.9l-0.8,0.9l-1.8-0.6l-2.7-2.8L498,239.4L498,239.4z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M712.1,297.2l-18.8,1.3l-4.8,0.7l-16,0.9l-2.4,0.7l-20.8,1.8l-0.6-0.6h-3.4l1.1,2.9l-0.6,0.8l-21.4,1.4l0.9-2.5';
$svg .= '	l1.3,0.8l0.6-0.4l1.1-3.8l-0.9-0.9l0.9-1.8l0.2-0.8l-1.2-0.7l-0.3-1.7l3.7-3.4l6.3,2.8l1.4-0.3l0.4-2.2l-1.6-1.7l0.4-3l0.9-0.5';
$svg .= '	l1.1,0.6l0.6-1.1l3.4-0.6v-0.8l-1.3-2v-1l1.9-2.5v-0.8l1,0.7l0.6-0.9l-0.6-1.6l4.2-0.5l0.2,1.1l1,0.2l0.4-0.8l-0.6-1.2l0.3-0.7';
$svg .= '	l1.2,0.7l1.6-0.4l1.6,0.6l3.1,1.9l1.7-2.6l3.2-2l2.8,3l1.5-1.9l0.3-2.5l3.5-2.1l0.2,1.2l1.7,1.1l2.8-0.2l1.1-0.6v-3.1l2.4-3.4l4.2-4';
$svg .= '	v-1.6l1-3.5l2,0.9l6.2-4.1l-0.4-1.6L691,249l0.9-1.7l1.2,0.5h2l1.7-0.6l2.7,1.1l2,3.1v0.9l3.8,0.6l2.1-0.2l1.7,1.9l2,0.2v-0.9';
$svg .= '	l1.7-0.7l2.8,0.7l1.1,0.7l1.2-0.6h0.8l0.6-1.6l3.1-1.7l0.5,0.7l0.7,2.7l3.2,1.3l1.1,1.9v1l0.5,0.9l-0.6,3.3l1.7,1.5l0.7,1l0.9,0.6';
$svg .= '	v0.8l4,5.1h1.3l1.4,1.7l1.1,0.3h1.3l-4.5,6l-2.7,0.9l-2.8,2.8l-0.4,2l-1.9,1.2v1.6l-1.4,1.3l-1.7,0.5l-0.5,1.7l-0.9,0.4l-6.3,3.9';
$svg .= '	l-5.1,1.3L712.1,297.2z M622.1,307.6l-0.6-0.6l0.2-0.9h1l0.6,0.6l-0.3,0.9H622.1z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M629.8,435.3l-1.1-1.7l0.3-1.2l-4.4-6.2l0.8-4.2l0.9-1.3v-1.3l-33,1.8l1.6-10.9l2.2-4.4l5.5-7.7l-1.7-2.3h1.8';
$svg .= '	v-3l-2.2-2.3l0.5-1.6l-1.1-0.9l-1.5-6.5l0.6-1.3l-48.1,1.2l0.5,18.3l0.6,3.1l2.4,2.6l0.6,5l3.5,4.2l0.7,4h0.9v6.7l-3.1,5.9l1.2,2.1';
$svg .= '	l-1.2,1.4l0.6,2.8v4l-2.1,3.2v0.7l-1.7,1.1l0.9,1.7l1.1,1l1.5-1.2l4.9-0.8h5.6l8.8,3.4l7.4,0.9l1.4-1.3l1.7-0.2l4.4,2l1.5-0.4l1-1.4';
$svg .= '	l-3.9-1.7l-2,0.9l-1-0.2l-1.3-1.8l3-2h1.5v1.5h1.4l3.1-0.4l0.4,2.1l1,0.4l0.6,1.7l4.4,0.9l1.6,1.5v0.6h-1.1l-1.4,1.6l1.6,1.1l5,0.9';
$svg .= '	l2.5,2.6l4-0.9l-3.4,0.2v-0.6l2.5-0.6l0.2-1.7l1.1-0.3v-1.3h1v1.6h2.3l0.7-1.7l0.8,0.3l0.2,2.3l1.1,0.2l-1.7,1.8l2.4-0.8l1.8-1';
$svg .= '	l2.7-3h-0.6l-1.2,1.1h-0.4l-0.5-0.8l0.8-1.1v-2.1l1-0.7l0.6,0.6l0.9-0.7h0.9l0.6,1.1l-0.6,1.7h2.2l4.7,1.6l0.5,1.2l1.5,1.3h2.6';
$svg .= '	l1.2,0.7l1.7-0.9l0.8-1.6v-1.6h-1.3l-1.1-1.3l-1-1l-2.9-0.8l-2.4,0.2l-3.9-2.2v-2.1l1.2-0.9l2.2,0.6l-2.8-1.5l0.2-0.7h3.3l2.4-3.2';
$svg .= '	l-2.4-1.7l0.7-1.4l-1.1-0.7h-0.7l-1.8,1.9v1.9l-0.6,0.6h-1l-1.5-1.4h-1.2v-1.4l0.6-0.6l0.7,0.6l1.6-1.5l0.6-1.5l0.7-0.3l-0.6-0.6';
$svg .= '	L629.8,435.3z M620.3,432.9l1.7,0.9l0.7,1h2.3l1.4,0.8l0.2,1.3l-0.4,0.6l-0.8-1.4l-1.3,1.1l-0.8,1.3l-2.6,0.7h-1.5l-3.4-0.8v-1.6';
$svg .= '	l1.9-1.8l1-2.2h1.5L620.3,432.9z M616,434v1l-1.7,1.8h-1.1v-2l1.5-1.4L616,434z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M880.2,119.2l0.6,3.7l2.9,1.8l0.7,2l2.1,1.3l1.3-0.3l0.9-2.8l-0.7-2.7l1.5-0.8l0.5-2.6l-0.6-1.2l3-1.7l-2-2.1';
$svg .= '	l0.8-2.2l1.3-2l0.5,2.9l1.5-1.8l1.2,0.8l1.1-0.7v-1.6l2.9-1.2l0.3-2.7l2.3-0.2l2.5-3.4v-0.6l-0.8-0.5v-3l0.5-1l0.2,1.5l0.9-0.5';
$svg .= '	l-0.2-2.9l-0.8,0.3v1.1l-1.2-1.3l0.8-1.3h0.6l1-0.3l0.5,2.6l1.8-0.3l2.7,0.6v-0.9l-1-1.1h1.2v-2l0.6,0.7l0.3,1.7l1.9,1.4L914,95';
$svg .= '	l0.8-0.2l-0.3-0.7l0.7-0.6V92l-1.6-0.2l-1.8,0.6l1.3-1.5l0.6-0.7l1.2-0.2l0.4,1.2l1.6,1.5l0.4-1.9l2.1-1.1l-0.8-1.2v-1.6l1.1,0.5';
$svg .= '	h0.6l1.6-1.3l0.4-2.1l2,0.3v-0.6l0.3-1.5l0.5,1.3l1.4-0.9l2.1-3.8v-2l-1.4-1.8l-2.8-2.9h-1.7l-0.7,2l-2.7-2.8l0.3-0.7v-1.4l-1.5-4.1';
$svg .= '	l-0.7-0.2l-0.6,0.4h-4.4l-0.3-3.3l-7.4-23.9l-6.7-3.4h-2.7l-6.2,6l-2.5-0.9l-0.9-3.6h-2.5l-6.3,17.9l0.6,5.7l-1.6,2.2l-0.4,4.2';
$svg .= '	l1.2,3.4l0.7,0.2v1.5l-1.5,4.1l-1.4,1.3l-1.2,2l-0.4,7.2l-2.2-0.9l-1.4,0.4L880.2,119.2L880.2,119.2z M911.9,96.5l-0.9,0.7v1.2';
$svg .= '	l0.6-0.7l0.8,0.7l0.4-0.5l1,0.2l-0.9-0.7l0.4-0.7L911.9,96.5L911.9,96.5z M910.4,98.9l-0.9,1l0.5,0.4v0.9h0.9v-1.7';
$svg .= '	C910.9,99.5,910.4,98.9,910.4,98.9z M907.6,97.4l0.8,1.2l0.9,0.5l0.3-0.9v-1.7l-1.2-0.6L908,97l-0.5,0.5L907.6,97.4z M906.7,102';
$svg .= '	l-1.6-1.6l1.5-2.2l0.7,0.3l0.2,1l0.9,0.7v1l-0.9,0.9L906.7,102L906.7,102z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M832.3,248.4v-1.6h-0.7v1.7L832.3,248.4L832.3,248.4z M843.1,244.8l1.1-2v-2.3l-0.5-0.6l-0.6,0.8l-0.2,1.9';
$svg .= '	l-0.7,1.3l-0.3,1l-4.2,1.5l-0.6,0.7l-1.2,0.2l-0.4,0.8l-1.2,0.6l-0.3-2.3l0.4-0.6l-0.7-0.5l0.2-1.4l-1.5,0.9V243l1.1-0.3l-1.7-0.4';
$svg .= '	l-0.6-0.7l0.4-1.2l-0.7-0.6l-0.6,1.5l0.5,0.7l-0.6,0.6l-1,0.5l-1.8-0.9l-0.2-1.1l-0.9-1l-1.3-1.6l1.4-0.7l-0.9-0.6v-0.8l0.6-0.9';
$svg .= '	l1.6-0.3l-1.3-0.6V234h-1.3l-0.4,1l-0.6,0.3v-3.1l1-0.9l0.7,0.6v-1.5l-0.8-0.8l-0.8,1l-0.9,1.3l-0.6-0.9l0.2-2.2l0.8-0.9l0.8,0.8';
$svg .= '	l1.1-0.6l-0.4-1.6l-0.9,0.9l-0.8-1.9l-0.2-1.6l1-2.2l1-1.3l1.3-0.2l-0.5-0.7l0.5-0.6l-0.3-0.6l0.2-1.9l-1.4,0.4l-0.7,1l0.9,1.2';
$svg .= '	l-2.4,3.3l-0.8-0.4l-0.6,0.8l-0.6,2l-1.7,0.5l1.2,0.6l1.2,1.2l-0.2,0.6l0.8,1.1l-1,0.9l0.5,0.3l-0.5,1.2v1.9l-0.5,1.2l0.8,1l0.6,3.1';
$svg .= '	l1.2,1.3l1.5,1.3l0.4,2.6l1.5,1.8l0.4,1.3v0.9h-0.6l-1.4-1.1l-0.4,0.2l-1.1-0.2l-1.6-1.3l-1.3-0.3l-0.9,0.5l-1.1-0.3l-0.4,0.2';
$svg .= '	l-1.6-0.7l-0.9-0.9l-0.9-1.2l-0.6-0.2l-0.7,0.6l-1.5,1.2l-1-0.7l-0.4-2.1l0.7-1.9l-0.3-0.5l0.3-0.4l-0.6-0.9h0.9l0.9-0.9l0.4-1.7';
$svg .= '	l1.6-2.4l-2.4-1.7l-0.9,1.6l-0.6-0.6h-1.5l-0.4-0.5v-0.5l-1.5-0.6l-0.7,0.3h-1.1l-0.6-0.7l-0.5-0.2l-0.2-0.6l0.6-0.7v-0.8l-1.1-0.2';
$svg .= '	l-0.9-0.8H802l-1.5-0.2l-0.8-0.4l0.2-1.5l-0.9-0.5l-0.2-0.6h-0.6l-0.7-1.1l0.2-0.9l-2.4,0.4l-2-1.5l-1.3,0.3l-0.8,1.3H790l-1.6,2.7';
$svg .= '	l-3,0.4l-1.7-0.9l-2.4,3.5l-2-0.3l-2.8,3.6l-0.8,1.5l-1.7,1.5l-1.6-10.5l55.6-10.8l7,24.9l10-2.1v7.7l-1,1.7l-0.6,0.3L843.1,244.8z';
$svg .= '	 M830.8,243.1l-1.2,0.8l0.7,1.7l1.6,0.7l-0.4-1.5L830.8,243.1L830.8,243.1z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M903,161h3.1l0.8-0.6v-1.2l-1.7-1.7l0.4,0.9l-1.4,1.4h-2.1v0.7l0.8,0.4L903,161z M894.8,162.6l-1.1-0.6l0.9-0.7';
$svg .= '	l0.6-1.9l1.1-0.9l0.7-0.2l0.6,0.8l1,0.2l0.6-0.6l0.5,1.7l-1.2,0.3l-2.6,0.6L894.8,162.6L894.8,162.6z M862.7,141.1l16.9-3.5l0.9-1.4';
$svg .= '	l0.3-1.6l1.7-0.6l0.5-1l1.6-1l1.2,0.3l1.6,3l0.9,0.4l1-1.2l0.7,1.2v1l-2.8,2.2l0.2,0.7l-0.8,0.9l0.4,0.7l-1.2,0.3l0.8,1.1l-0.7,0.6';
$svg .= '	l0.6,0.9l0.8-0.2l0.3-0.7l1,0.6h1.7l2.3,2.4l0.2,2.4h1.7l0.7,1.1l0.6,1.8l0.9,0.6h3.5l0.7-0.9l1.5-1.1l1-0.3l-1.1-1.9l-0.3,0.8';
$svg .= '	l-1.4-3.3h-0.7l-0.4,0.8l-1.1-0.9l1.2-1l1.7,0.4l2.1,1.9l1.2,2.5l1.1,3l-0.9,2.6V153l-0.6-0.9l-3.2,2.1l-0.8-0.3l-1.5,0.9v1.1';
$svg .= '	l-2.1,1.1l-1.8,1.9l-1.8,1.7h-1.1l3-3l0.5-1.7l-0.5-0.6l-0.3-1.2h-0.8v1.1l-0.9,1.1h-1.1l-0.3,1l0.4,1.1l-1.1,1l-1-0.2l-0.4,0.9';
$svg .= '	l-1.3-2.8l-1.2-1l-2.4-1.2l-0.6-2h-0.7l-0.6-2.4l-6,1.8v-0.3l-13.8,3.1v0.6l-0.8,0.3l-0.5-0.6l-9.6,2.2l-0.6-0.9l0.5-13.8l11.4-2.5';
$svg .= '	L862.7,141.1z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M685.6,193.7v1.3l19.8-3.2l0.5-1.1l3.6-5.4v-4l0.7-1.9l2-0.7l1.8-7.2l0.9-0.5l0.9,0.6l-0.2,0.6l-1,0.7l0.3,0.8';
$svg .= '	l0.7,0.4l1.7-1.3l0.4-9l-1.5-2.1l-1.1-3.4V156l-2.1-4v-1.7l-1.1-3l-2.1-2.8l-2.7-0.9l-4.4,2.8l-2.3,4.2l-0.2,0.8l-2.8,3.2l-1.4-0.2';
$svg .= '	l-2.7-2.6v-3.1l1.3-1.7l1.8-0.2l1.1-1.6l0.2-3.7l0.7-0.7h1l0.8-1.7l-0.2-8.8l-0.3-1.2l-1.1-1.1l-1.6-0.9v-1.7l0.6-0.6l1.7,0.7';
$svg .= '	l-0.3-1.6l-1.7-2.5l-0.6-1.5l-1-1h-2l-7.4-2.7l-1.3-1.6l-2.8-0.3l-1.1,0.3l-4-2.1h-1.3l0.5,0.9h-2.5v0.5l0.6,0.6l-2.3,1.9v1.7';
$svg .= '	l1.5,2.1l1.4,0.2v0.6l-1.4,0.5h-1.9l-2.6,2.2v2.3l0.5,5.3l-2,3.1l0.7-4.1l-0.7-0.6l-0.8,4.9l-0.9-2.1l0.5-2.1l-0.5-0.9l0.6-1.2';
$svg .= '	l-0.6-1l0.9-0.9v-1.1l-1.2,0.6l-1.2,2.8l-0.6,0.6l-1.2,2.2l-1.6-0.2v1.1h-1.6l0.2,1.4l0.2,1.8l-2.8,1.1v1.2l1,1.6v4.8l-1.3,4';
$svg .= '	l-1.6,2.3l1.1,1.3l0.7,3.2l-0.9,2.3l-0.2,1.9l1.6,3.1l2.3,4.5l1.1,1.7l1.5,6.3v8.1l-0.9,3.6l-1.8,2.9l-0.8,3.4l-1.8,2.8l-1.1,0.9';
$svg .= '	l29.8-3.1L685.6,193.7z M597.6,104.7l2.8,3.5l15.6,3.5l1.3,0.9l3.7,0.7l0.6,0.5l2.6-0.2l4.5,0.7l1.3,1.4l-0.9,0.9l0.7,0.7l3.5,0.6';
$svg .= '	l1.1,1.1v4l-1.1,2.6h1.8l0.9-0.6l0.8,0.7l-1,2.8l0.9,1.5l1.1,0.3l0.7-1.7l2.7-4.2l1.5-5.5l2.1-1.8l-0.5-1.5l0.5-0.8l0.9,1.5l-0.3,2';
$svg .= '	l2.7-2l0.2-2.1l1.9,0.6l0.7-1.5l0.6,0.6l-0.6,1.4l-0.9,0.5l-0.9,1.8l1.3,1.7l1-0.5l-0.5-0.6l0.9-1.4l1.7-1.6h0.7l0.2-2.4l1.8-1.7';
$svg .= '	l7.3-0.5l1.7-2.8l3.5-0.3l3.5,1.1l3.9,2.5l0.6-0.2l-0.2-3.2l0.6-0.2l4.1,1l1.4-0.2l2.7-0.6l1.6,0.4h1.7v-0.9l-0.6-0.8l-1.4-0.2';
$svg .= '	l-1-0.7l0.5-1.3l-0.7-0.3H683v-0.8l1-0.7l0.6,0.7l0.5-1.7l-0.6-0.6l0.6-0.2l-1.3-1.2l0.3-1.2v-1.7H683l-1.4,0.9h-1.7l-0.5,1.7';
$svg .= '	l-1.7,0.2l-0.3-1.1h-2l-0.9,1.2h-0.6l-0.2-0.8l-2.4,0.4v-4.4l0.8-1.8h-0.6l-1.7,0.9h-2l-3.5,2.5l-5.7,0.3l-3.8,0.7l-1.7,1.4';
$svg .= '	l-1.3,1.2l-2.3,1.6l-0.3,0.7l-0.6-1.6l-1.2-0.6v0.6l0.6,0.6v1.2l-1.4-0.6H646l-0.3,1.1l-1.8-1.7l-1.2-0.2l-1.2,1.4h-2.9l-0.5-1.4';
$svg .= '	l-1.8-1.7l-1.2-1.5v-0.6l-1-1.3l-2.4-1.1h-3l-1-0.9h-1.3l-0.6,0.4l-2,2l-0.6,1l-0.9-0.6l0.2-0.9l0.7-1.9l2.9-4.6l0.7-0.2l1.6-1.7';
$svg .= '	l0.6-1.5l2.8-0.6l0.7-0.6v-0.9l-0.6-0.5l-4.1,0.2l-1.8,0.5l-2.4,1.1l-1.1,1.1l-1.6,2l-1.7,0.9l-3,3.1l-0.4,1.5l-6.8,4.2l-3.7,0.5';
$svg .= '	l-1.7,0.4l-2.1,2.8l-1.7,0.6l-4,2.1l1.7,0.6L597.6,104.7z M690.1,108.2h3.5l0.6-0.4L694,106l-1.6-1.7h-1.7v0.6l1,0.4l-1.5,0.7';
$svg .= '	l-0.3,0.9l-0.6-0.6l-0.4,0.7l1.2,1.1V108.2z M621.1,69.7l-2.1,0.2l-2.5,1.7l-6.5,4.9l0.7,0.9l1.7,0.3l2.6-1.8l-1-0.5l2.1-1.5h0.9';
$svg .= '	l2.8-1.7v-0.8l1.4-1.7H621.1z M658.9,127.4v0.9l1.9,1.5l-0.2-2.2C660.6,127.6,658.9,127.4,658.9,127.4z M658.3,130h1v0.9h-0.9';
$svg .= '	L658.3,130L658.3,130z M677.9,110.4v0.8l0.7-0.2v-0.5L677.9,110.4L677.9,110.4z M682.2,113.3v-1l-1.6-0.2l-0.6-0.4h-0.8l-0.4,0.3';
$svg .= '	l0.8,0.4l1,1h1.5L682.2,113.3z M665.7,114.4v1l-0.4,0.6l0.2,2l0.4,0.3h0.6l0.5-0.7v-1.5l-0.2-0.6v-1L665.7,114.4L665.7,114.4z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M504.2,64l-1,2.6l0.7,1.3l-0.3,4.7l-0.5,1l2.5,8.4l1.2,2.3l0.6,12.9l0.9,2.5l-0.4,5.3l2.7,6.8l0.3,5.3v1.9';
$svg .= '	l-0.2,2l-0.8,1.8l-2.8,1.7l-0.3,1.1l1.6,2.3l0.4,1.7l2.4,0.6l1.4,1.7l-0.2,36.3h25.9l33.4-0.8l17.1-0.6l-1-4.1l-0.2-2.8l-2-2.8';
$svg .= '	l-2.6-0.6l-4.8-3.3l-0.6-3l-5.8-2.8l-0.2-1.2h-3l-2-2.4l-1.8-1.2l0.6-4.7l-0.8-1.5l0.5-5l0.9-1.7l-0.3-2.5l-1.1-1.2l-1.7-0.3v-1.6';
$svg .= '	l2.6-5.3l5.4-3.6l-0.4-11.9l0.8,0.4l0.6-0.5v-1l0.9-0.6l1.3,1.1h0.6l-1.1-2.1l4-2.8l2.8-3.4l1.5-0.7l4.3-5.4L592,83l3.6-1.9l5.8-2.5';
$svg .= '	l7-4.1l-0.6-0.4l-3.4,0.6h-2.6l-0.9-1.4l-1.3-0.8l-9,1.1l-0.9-2.6h-1.5l-1.6,0.6l-3.4,2.8h-3.8l-1.9-0.9l-0.3-1.6l-3.6-0.7l-0.6-1.5';
$svg .= '	l-0.6-1.2l-0.9,0.8h-2.4l-9.1-5h-2.7l-0.7-0.6l-2.8,1.2l-0.7,1.2l-3,0.7l-1.2-0.2V65l-0.6-0.8h-5.4l-0.4-1.3h-2.4l-1,0.4l-2.2-1.6';
$svg .= '	l0.3-1.3l-0.6-2.2l-0.6-1l-0.2-2.8l-0.9-2.8l-1.9-1.5h-2.7v7.4L502,57.1l2.2,6.7L504.2,64z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M649.3,431.5h-4.6l-2.2-1.3l-7.3,2.3l-0.8-0.6l-0.5,0.2v1.5h-0.6l-2.4,2.5h-0.6l-0.6-0.7l-1.1-1.7l0.3-1.2';
$svg .= '	l-4.4-6.2l0.8-4.2l0.9-1.3v-1.3l-33,1.8l1.6-10.9l2.2-4.4l5.5-7.7l-1.7-2.3h1.8v-3l-2.2-2.3l0.5-1.6l-1.1-0.9l-1.5-6.5l0.6-1.3';
$svg .= '	l1.1-1.4l0.5-2.8l-1.4-2.1l-0.5-2l0.8-0.6v-0.7l-1.6-1v-0.6l1.4-0.8l-1.1-1l1.6-6.5l3.1-1.5v-0.7l-1-1.3l2.7-5h1.7l1.4-1.1l-0.3-4.8';
$svg .= '	l2.8-4.1l1.7-0.6l-0.5-2.8l35.2-2.4l1.2,1.8l-1.2,61.6l4,30.5l-1.4,1.2L649.3,431.5z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M586.4,229.6l-1-1l-0.6-1.5l-1.6-1.2l-13.1,0.7l-25,1.1h-23.8l1.2,1.1l-0.3,1.3l1.9,3.4l3.6,5.8l2.7,2.8';
$svg .= '	l1.8,0.6l0.8-0.9l1.4,1.9v1.1l-2.6,2.4l-0.5,2.1l2.3,2.3l2.4,4.3l2.9,0.6l0.5,44.2l0.2,9.9l35.9-0.6l36.6-1.8l1.5,2.3v2l-1.6,1.4';
$svg .= '	l-2.6,4.7l10.3-0.7l0.9-1.8l1.1-0.5V315l-1.1-1l-0.6-0.9l1.6,0.2l0.7-0.6l-1.3-1.4l1.3-0.5v-0.9l-0.5-0.9v-1.2l-0.6-0.6l0.2-0.9h1';
$svg .= '	l0.6,0.6l-0.3,0.9l0.7,0.6l0.7-0.9l0.9-2.5l1.3,0.8l0.6-0.4l1.1-3.8l-0.9-0.9l0.9-1.8l0.2-0.8l-1.2-0.7h-2.6l-1.3-1.4l-1.7-3.5v-1.7';
$svg .= '	l0.7-0.6V289l-1.5-1.7l-0.8-2.3l-2.5-3.8l-4.4-1.2l-6.8-6.5l-0.4-2.2l2.6-7l-0.4-1.7l1.1-1v-1.2l-2.6-1.4l-2.8-0.6l-3.1,1.1';
$svg .= '	l-1.2-2.1l0.6-1.7l-0.6-2.2l-7.9-7.7l-2-1.4l-2.3-5.4l-1.1-5l1.3-3.4l-1.4-0.7L586.4,229.6z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M306.4,120.6l52.6,7.3l46.9,4.9l1.8-19l4.8-61.3l-49.2-5.1l-49.9-7.1l-60.5-11.5L248.5,49l3.4,6.8l-1.5,4.4';
$svg .= '	l3.3,4.4l1.7,0.6l3.6,7.6v1.9l2.1,2.8h0.8l1.3,1.9h2.9v1.5l-6.5,15.6l-0.5,3.8l1.3,0.5l1.5,2.4l2.6-1.3l3.3-2.2l1.7,1.7l0.5,2.3';
$svg .= '	l-0.5,2.9l2.3,8.9l2.4,3.2l2.1,1.3l0.4,2.8v3.8l2.1,2.1l1.5-2.1l6.3,1.5l1.9-1.1l8.3,1.6l2.6-3l1.7-0.6l1.1,1.7l1.5,3.8h0.8l1.8-9.7';
$svg .= '	L306.4,120.6z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M449.3,176.2l34.9,1.5l3.1,2.9l1.6,0.2l1.9,1.8h1.7l1.7-1.9l1.4,0.6l0.9-0.6l0.6,0.5l0.8-0.4l0.6,0.4l0.8-0.4';
$svg .= '	l0.9,0.5l1.3-0.6l1.8,0.6l0.6,1l5.6,2l1.1,1.2l0.8,2.4l1.7,0.6l1.4-0.2l0.5,0.8v2.1l0.6,1.6v1.3l1.2,3.4l2,4l0.2,3.4l2.6,4.6';
$svg .= '	l0.4,5.6l1.2,0.6l-0.2,2.7l0.7,3l-0.6,2l1.7,4l1.2,1.2l-0.3,1.3l1.9,3.4l3.6,5.8h-29.8l-40.2-1.1l-33.1-1.8l1.3-20.3l-30.4-2.2';
$svg .= '	l3.4-40.6l47,3.2L449.3,176.2z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M233.5,273.4l-3.1,16.1l-2.2,2.7h-1.8l-1.1-2.5l-3.4-1.3l-3.2,0.6l-0.9,12.5l0.5,4.5l-0.5,2.7l-1.3,2.8';
$svg .= '	L151.8,215l-1-3.2l15.1-58l43.2,10.3l22.4,5l21.4,4.3l-19.3,100.2L233.5,273.4z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M868.8,86.9h-1.2l-0.9-0.9l-1.7,1.3l-0.5,5.6l1.1,2.1l-1,3.2l1.9,2.6l-0.4,1.6v1.2l-0.9,1.9l-1.3,0.4l-0.6,1.2';
$svg .= '	l-1.9,0.9l-0.6,1.4l1.3,3.1l-0.5,2.3l0.5,1.4l-0.9,1.7l0.4,1.7l-1.2,1.7l0.2,2l-0.6,1l0.6,4.1l0.6,1.4l-0.5,2.4l0.8,1.7l-0.2,2.3';
$svg .= '	l-0.5,1.2v1.3l1.8,2.4l16.9-3.5l0.9-1.4l0.3-1.6l1.7-0.6l0.5-1l1.6-1l1.2,0.3l0.7-4.4l-2.1-1.3l-0.7-2l-2.9-1.8l-0.6-3.7l-10.9-33.8';
$svg .= '	l-0.5,1.6L868.8,86.9z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M850.3,180.5l-13.4-4.5l-1.7,2.3v2l-2.7,5l1.4,1.7l-0.6,1.8l-0.9,0.9l0.5,3.3l2.5,0.8l0.9,2.6l1.9,1l3.9,2.9';
$svg .= '	l-3,2.4l-1.5,2.1l-1.7,2.8l-1.5,0.6l-1.3,1.6l-0.9,2l-0.3,1.9l0.7,0.8l0.4,2.1l1.1,0.6l2.2,1.4l1.7,0.7l1.5,0.7v1h0.8l1-1.1l0.7,0.4';
$svg .= '	l1.9,0.2l-0.2,2.7l0.2,2.3l1.7-0.6l1.4-3.6l1.5-4.4l2.7-2.6l0.6-3.2l-0.6-1.1l1.6-2.7v-1.1l-0.6-1l1.1-2.5l-0.3-3.3l-0.6-7.5';
$svg .= '	l-1.1-1.3v1.3l0.5,0.6h-1l-0.6-0.4l-1.2-0.2l-0.8,0.6l-1.1-1.5l0.6-1.6v-0.9l1.6-0.6l0.7-1.9l0.4-5.4L850.3,180.5z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M404.9,306.5h-0.7l-7.3,91.2l-29.2-2.4l-31.6-3.3l-0.3,2.8l1.8,2l-28.3-3.8l-1.3,9.4l-14.4-2l16-114l48.3,6';
$svg .= '	l47.5,4.4l-0.6,9.7L404.9,306.5z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M878.2,167.8H877l-0.5,1L878.2,167.8z M850.1,188.6l0.6,0.6l1.2-0.3l1,0.3l0.8-1.2h1.7l2.2-0.8l4.7-1.9';
$svg .= '	l-0.5-0.5l-1.7,0.7l-1.8,0.8l0.2-0.7l2.4-1l0.7-0.9h1.1l3.8-2v0.6l-3.9,2.8l4.1-2.6l1.6-2h1.4l4.1-2.9l2.9-2.8l2.8-2.1l0.9-1.1h-1.6';
$svg .= '	l-0.9,1l-0.2,0.6l-0.8,0.6l-0.7-1l-1.6,0.9v0.8l-0.9-0.2l0.5-0.8l-1.1-0.6l-0.6,0.8l0.8,0.3l0.2,0.5l-0.3,0.5l-1.3,2.4h-1.7l0.8-1.7';
$svg .= '	l0.8-0.6l0.3-1.6l1.3-1.5l0.8-0.7l1.4-0.6l-1.1-0.2l-0.6,0.8h-0.6l-1,0.7l-0.2,0.9l-2,1.9l-0.4,0.8l-1.3,0.8l-7.1,1.7l0.2,0.8';
$svg .= '	l-0.8,0.6l-1.8,0.3l-0.9-0.6l-0.2,1l-1-0.4v0.9h-1l-1.1,0.5l-0.2,1h-0.9l0.2,0.9h-0.6l0.2,0.9l-1.7,0.4l-1.4,2.1l-0.2,1.9';
$svg .= '	L850.1,188.6z M849.4,188.3l-1.5,0.4v0.9l-0.6,1.5l0.6,0.6l2.2-2.1v-0.8l-0.6-0.5L849.4,188.3z M840.1,100.8l-0.6,1.7l1.3,0.8';
$svg .= '	l-0.4,1.4l0.5,2.9l2,2.1l-0.4,2l0.6,1.8l-0.4,0.9l-0.3,3.5l2.8,6.2l-0.7,1.7l0.8,2l0.8-1.5l1.7,1.4l2.8,13l-0.5,1.8l1,0.9l-0.5,13.8';
$svg .= '	l0.6,0.9l2.6,15l1.7,1.4l-3.2,3.1l1.6,2l-1.2,3l-1.4,1.6l-1.4,2.1l-0.2-0.6l0.4-5.4l-13.4-4.5l-1.5-1l-1.7,0.3l-2.8-2l-2.8-5.3h-1.8';
$svg .= '	l-0.4-1.4l-1.6-1l-64.8,12.8l-0.7-5.5l4-3.6l0.6-1.6l3.6-2.3l0.6-2.2l2.1-1.8l0.7-1l-1.6-3l-1.6-0.5l-1.7-2.8l-0.2-2.9l7-3.6';
$svg .= '	l7.5-1.5h4l2.9,1.5h0.8l1.7-1.6l3.1-0.6h2.8l2.4-1.2l2.3-2.4l2.2-2.8l1.7-0.4l1-0.5l0.4-2.9l-1.3-2.5l-1.1-0.6l1.8-1.2V129h-1.5';
$svg .= '	l-2.1-1.3v-2.8l5.6-5.6l0.6-2.2l3.4-5.8l5.4-5.9l1.9-1.6h2.3l18.9-4.7l0.7,1.6L840.1,100.8z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M837.9,276.7l-26.7,5.6L775,289l-27,3.2v4.8h-1.4l-1.3,1l-2.2,4.8l-2.4-1l-3.2,2.3l-0.6,1.9l-1.4,1.1l-0.7-0.7';
$svg .= '	V305l-0.8-0.2l-3.7,3l-0.6,3.1l-4.3,2.2l-0.5,1.1l-2.9,2.4l-3.3,0.5l-4.2,2.8l-0.7,3.8l-1.2,0.8h-1.4l-1.3,1.1v4.5l19.6-2.8l4-1.7';
$svg .= '	h1.2l6.7-4l21.3-2l0.4,0.5l-0.2,1.3l0.6,0.3l1.1-1.4l3,2.8v2.4l18.2-2.6l22.5,15.7l3.7-2l2.8-0.6h1.6l1,1l0.7-1.8l0.6-4.6l1.6-3.6';
$svg .= '	l5-5.6l3.8-3.2l5-2.1l2.3-0.4l1.2,0.4l0.6,1l3-6.1l3-4.9l-0.6-0.3l-4,6.2l-0.5-0.7l1.8-2l-0.4-1.4l-1.8-0.5l0.9,1.2h-1.1l-1.1-1.6';
$svg .= '	l-1.1,1.8l-1.5,0.2l0.9-2.5l0.6-1.6l-0.2-2.7h-2l0.8-0.9l1,0.3h2.5l0.7-0.4h2.1l1.8-1.7l0.2-2.9l1.2-1.3l1.1-0.2l1.2-0.9l-0.5-3.4';
$svg .= '	l-2-3.5l-2.5-0.2l-0.8,1.5l-0.5-0.9l-2.5,0.2l-1.1,0.4l-1.7,1.1l-0.3-0.4H834l-1.7,1.1l-2.4,0.5v-1.2l0.7-0.9l0.9,0.6h0.9l1.6-1.9';
$svg .= '	l3.4-1.6l1.8-2h2.2l0.7,1.2l1.6,0.7l-0.5-1.4l-0.3-1.5l-2.6-2.8l-0.3-1.3l-0.4,0.9l-0.8-1.2l-1.2-1.5L837.9,276.7z M844.3,305.1';
$svg .= '	l2.5-2.3l4.2-3v-3.4l-0.4-2.8l-1.6-3.9l1.4,1.3l0.9,2.9l0.4,7l-1.6,0.4l-2.8,2.2l-2.9,2.9v-1.3L844.3,305.1z M846.1,287.4l-0.8-0.2';
$svg .= '	v0.9l2.3,2l-0.2-1.3l-1.3-1.5V287.4z M848.7,289.3l-1.3-2.6l-2-3.1l-2.2-2.8l-2-4l-0.7-0.6l2,4l0.3,1.2l3.1,5.1l1.7,1.9L848.7,289.3';
$svg .= '	L848.7,289.3z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M506.4,63.7l-1,2.6l0.7,1.3l-0.3,4.7l-0.5,1l2.5,8.4L509,84l0.6,12.9l0.9,2.5l-0.4,5.3l2.7,6.8l0.3,5.3v1.9';
$svg .= '	l-27.2-0.4l-42.3-1.9l-36-2.7l4.8-61.3l40.9,3.1l50.8,1.5L506.4,63.7L506.4,63.7z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M706.2,192.8l1.7-0.4l2.8,1.2l1.9,0.6l0.6,0.8h0.9l0.9-1.4l1.2,0.7h1.4v0.9l-2.9,0.5l-1.8,1l1.7,0.7l1.5-1.4';
$svg .= '	l2.2-0.4l2,1.4h1.4l2.3-1.7l3.3-1.9l4.8-0.3l4.5-5.4l3.5-2.8l8.5-4.7l4.5,27.5l-2,1.1l1.3,1.9v2l0.5,1.8l-1,3.1v5l-1,3.3l0.5,1';
$svg .= '	l-0.4,2l-1,0.5l-1.8,3l-1.7,1.8h-0.6l-1.7,1.6l-1.2-1.1l-1.4,1.7l-0.3,1.1h-1.2l-1.2,2v1.9l-0.8,0.5l1.3,1v1.7l-0.9,0.2l-0.6,0.7';
$svg .= '	L737,246l-0.6-1.9l-1.5-0.5l-0.9,2.1l-0.3,2l-1,1.2l1.2,3.3l-1.4,0.7l-0.4,3.2h-1.4l-2.9,1.3l-1.1-1.9l-3.2-1.3l-0.7-2.7l-0.5-0.7';
$svg .= '	l-3.1,1.7l-0.6,1.6h-0.8l-1.2,0.6l-1.1-0.7l-2.8-0.7L711,254v0.9l-2-0.2l-1.7-1.9l-2.1,0.2l-3.8-0.6v-0.9l-2-3.1l-2.7-1.1L695,248';
$svg .= '	h-2l-1.2-0.6l-6.1-52.6l19.7-3.2l0.8,1V192.8z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M537,367.2l-4.2-3.5l-2-0.8l-0.5,1.5l-4.7,0.3l-0.6-1.4l-4.6,2.3l-1.5-0.6l-3.4,0.3l-0.6,1.6l-3.3,0.8l-1.2-1.1';
$svg .= '	h-1.1l-1.8-1.6l-1.9,0.6l-1.8-0.5l-1.7-1.8l-2.3,3.9l-1.1,0.7l-0.9-1.7l0.3-1.8l-1.1-0.6l-2.1,2.3l-1.6-1.1v-1.4l-1.3,0.5l-2.4-1.6';
$svg .= '	l-2.8,2.4l-2.1-1l0.6-1.9h-2.1l-1.7-2.7l-3.2-1l-1.8,2.1l-2.1-2l-1.3,0.4h-1.8l-3.2-1.7H466l-1.1-0.6l-0.5-2.7l-2.1-1.6l-1,1.4';
$svg .= '	l-1.3-0.9l-1.1-0.4l-1,0.9l-1.4-0.3l-2.3-2.8l-2.5-1.2l1.3-39.2l-48.3-2.9l0.6-9.7l15.2,0.9l62.2,2.7h57l0.2,10l3.8,22.4l-0.6,35.8';
$svg .= '	l-5.9-1.7L537,367.2z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M165.7,153.6l43.2,10.3l7.8-34.3l2.7-5.3l0.4-1.9l0.7-0.8l-0.8-1.8l-2.7-1.1l0.2-3.9l3.7-5.3l2.3-0.7l1.5-2.1';
$svg .= '	v-1.5l1.6-1.5l2.9-5.1l3.9-4.4l-0.5-2.9l-3.2-2.8l-1.5-3.3l-27.8-6.7l-2.6,0.9l-5-0.8l-1.7-0.8l-1.4,1.1l-3-0.4l-4.1,0.5l-0.8,0.6';
$svg .= '	l-3.9-0.4l-0.7-1.5l-1.1-0.2l-4,1.2l-1.5-1l-2,0.7l-0.2-1.7l-2.1-1.1l-1.4-0.2l-0.9-1l-2.8,0.3l-1.1-0.7h-1.1l-1.1,0.8l-5.1,0.6';
$svg .= '	l-6.1-3.9l1-5.1l-0.4-3.8l-2.9-3.4h-3.4l-0.4-0.9l0.4-1.1l-0.6-0.7h-0.9l-1,1.3l-1.4-0.2l-0.5-1h-0.9l-0.6,0.5l-1.8-1.7v4l-1.2,1.2';
$svg .= '	l-1,3.2v2.1l-4.2,11.3L114.5,106l-2.9,4.2h-1.5v1.8l-4.8,6.5l-0.3,3l0.9,1.2v2.2l-1,1l-1.1,2.8v5.2l1.2,2.7L165.7,153.6L165.7,153.6';
$svg .= '	z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M835.4,174.9l-1.7,0.3l-2.8-2l-2.8-5.3h-1.8l-0.4-1.4l-1.6-1l-64.8,12.8l-0.7-5.5l-3.9,3.1h-0.8l-2.5,2.8';
$svg .= '	l-3,1.6l4.5,27.5l2.9,18.1l16-2.7l55.6-10.8l1.1-1.9l1.4-1l1.5-0.3l1.5,0.6l1.3-1.6l1.5-0.6l1.7-2.8l1.5-2.1l3-2.4l-3.9-2.9l-1.9-1';
$svg .= '	l-0.9-2.6l-2.5-0.8l-0.5-3.3l0.9-0.9l0.6-1.8l-1.4-1.7l2.8-5v-2l1.6-2.3l-1.5-1L835.4,174.9z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M887.7,157.8l-1.2-1l-2.4-1.2l-0.6-2h-0.7l-0.6-2.4l-6,1.8l2.9,11.3l-0.4,1l0.4,1.7l5.1-3.3v-2.8l-0.6-0.7';
$svg .= '	l0.4-0.6v-1.2l-0.9-0.6l1.1-0.4l-0.8-1.5l1.7,0.6l0.3,1.3l0.6,1.1l-1.3-0.7l1,1.6l-0.3,1.1l-0.6-1v2.3l0.6-0.8l0.4,0.8l1.2-1.4';
$svg .= '	l-0.2-2.3l1.3,2.8l0.9-0.8L887.7,157.8L887.7,157.8z M883.4,169h0.8l0.5-0.6l-0.7-1.2l-0.6,0.6V169L883.4,169z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M785.8,322.7l-18.1,2.6v-2.4l-3.1-2.8l-1.1,1.4l-0.6-0.3l0.2-1.3l-0.4-0.5l-21.3,2l-6.7,4h-1.2l-4,1.8v1.7';
$svg .= '	l-1.8,0.9l-1.3,2.9l0.2,1.2l5.6,3.5l2.4-0.3l2.8,3.7l0.4,1.6l3.9,4.7l2.4,1.6l1.3,0.2l2,1.5l1,2l1.8,1.5l1.7,0.5l2.5,2.5v1.3';
$svg .= '	l2.5,2.6l4.6,2.1l3.3,6.2l0.3,2.5l3.6,1.9l2.3,4.4l0.7,2.8l3.9,0.4l0.7-1.4h0.6l1.7-1.4l0.5-1.8l2.9-1.9l0.3-2.2l-1.1-0.8l0.7-0.6';
$svg .= '	l0.7,0.4l1.2-0.4l1.7-1.9l3.5-1.7l1.5-2.2v-0.6l4.5-4v-0.5l-0.9-0.7l1-1.4h0.7l0.4,0.5l0.6-0.7h1.2l0.6-1.4l2.1-1.9l-0.3-5l0.7-2.1';
$svg .= '	l3.3-5.7l2.2-2l2-1L786.1,323L785.8,322.7z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M443.8,116.3l42.3,1.9l27.1,0.4v2l-0.9,1.8l-2.8,1.7l-0.3,1.1l1.6,2.3l0.4,1.7l2.4,0.6l1.4,1.7l-0.2,36.3h-2';
$svg .= '	v1.4l1.2,1.4v1l-1,0.5l0.4,1.5l1.2,0.4l0.6,1.8l-1.6,4.7l-0.9,4l1.2,1.1l0.3,1.2l0.6,1.6l-1.4,0.2l-1.7-0.6l-0.8-2.4l-1.1-1.2';
$svg .= '	l-5.6-2l-0.6-1l-1.8-0.6l-1.3,0.6l-0.9-0.5l-0.8,0.4l-0.6-0.4l-0.8,0.4l-0.6-0.5l-0.9,0.6l-1.4-0.6l-1.7,1.8h-1.7l-1.9-1.7l-1.6-0.2';
$svg .= '	l-3.1-2.9l-34.9-1.5l-47-3.2l3.6-40.3l1.8-19l36,2.7L443.8,116.3z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M646.7,336.4l42-3.7l21-2.7v-4.5l1.4-1.2h1.4l1.2-0.7l0.7-3.8l4.2-2.8l3.3-0.5l2.9-2.4l0.5-1.1l4.3-2.2l0.6-3.1';
$svg .= '	l3.7-3l0.7,0.2v1.4l0.8,0.7l1.4-1.1l0.6-1.9l3.2-2.3l2.4,1l2.2-4.8l1.3-1.1h1.4v-4.7l0.3-0.6L744,292l-0.2,0.9l-26.6,3l-5.1,1.3';
$svg .= '	l-18.8,1.3l-4.8,0.7l-16,0.9l-2.4,0.7l-20.8,1.8l-0.6-0.6h-3.4l1.1,2.9l-0.6,0.8l-21.4,1.4l-0.7,0.9l-0.7-0.6h-0.9v1.2l0.6,0.9v0.9';
$svg .= '	l-1.4,0.5l1.3,1.4l-0.7,0.6l-1.6-0.2l0.6,0.9l1.1,1v0.6l-1.1,0.5l-0.9,1.8v0.6l1.4,0.9l-0.4,0.6h-1.4v0.5l0.8,0.8v0.7l-1.2,0.2';
$svg .= '	l-0.5,0.7l-1.5,0.2l-0.8,0.8l0.6,0.8h1l0.5,0.7l-1.5,1.2l0.4,1.4l-1.8-0.6v0.6l0.3,1l-0.3,1.3l-1.2-0.7l-0.7,0.7h1v1.5l-0.6,0.9';
$svg .= '	l1,0.8l-0.3,1.4l0.7,0.6l-0.6,0.9l-1.1-0.5l-0.8,2l-1.5,0.6l35.2-2.4L646.7,336.4z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M335.6,395.1l0.3-2.8l31.6,3.3l29.2,2.4l7.3-91.2h0.7l48.3,2.9l-1.3,39.2l2.5,1.2l2.3,2.8l1.4,0.3l1-0.9';
$svg .= '	l1.1,0.4l1.3,0.9l1-1.4l2.1,1.6l0.5,2.7l1.1,0.6h2.1l3.2,1.7h1.8l1.3-0.5l2.1,2l1.8-2.1l3.2,1l1.7,2.8h2.1l-0.6,1.8l2.1,1l2.8-2.4';
$svg .= '	l2.4,1.6l1.2-0.5v1.4l1.7,1.1l2.1-2.3l1.1,0.6l-0.3,1.8l0.9,1.7l1.1-0.7l2.3-3.9l1.7,1.8l1.8,0.5l1.9-0.6l1.8,1.7h1.1l1.2,1l3.3-0.8';
$svg .= '	l0.6-1.6l3.4-0.3l1.5,0.6l4.6-2.3l0.6,1.4l4.7-0.3l0.5-1.5l2,0.8l4.2,3.5l5.9,1.7l2.4,2.1l2.6-1.2l2.9,0.7l0.2,10.9l0.5,18.3';
$svg .= '	l0.6,3.1l2.4,2.6l0.6,5l3.5,4.2l0.7,4h0.9v6.7l-3.1,5.9l1.2,2.1l-1.2,1.4l0.6,2.8v4l-2.1,3.2v0.7l-1.7,1.1l0.9,1.7l1.1,1l-3.2,0.3';
$svg .= '	L545,453l-3.2,1.3l-1.7,1.7l-0.6-0.5l1.9-2.1l1.7-0.6l0.5-0.8h-2.7l-0.6-0.8l0.7-1.8l-0.8-1.7h-0.6l-2.2,1.2l-1.7,2.4l0.3,1.6l3,3.1';
$svg .= '	l1.2,0.3v0.7l-2.1,1.5l-4.5,3.7l-3.7,3.6l-2.9,1.3l-4.6,2.8l-3.4,1.8l-4.1,1.7l-3.8,2.3l2.9-2.8v-1l0.6-0.7l-0.2-1.7H513l-1,1.3';
$svg .= '	l-2.4,1.2l-1.7-1.1l-0.3-1.6h-1.4l0.7,2l1.3,0.6l1.1,0.8l1.7,1.5l-0.6,0.7l-3.6,1.6h-1.6l-1.1-1l-0.5,1.9l0.5,1l-2.5,1.8l-1.4,0.2';
$svg .= '	l-0.7,0.6l-0.4,1.6l-1.7,3l-1.5,0.6l-1.5-0.6l-1.7,1l0.3,1.3l1.2,0.7l0.9,0.7l-1.7,3.2l-0.3,2.6l-0.9,1.6l-1.3,0.9l-2.7,0.4l1.7,0.6';
$svg .= '	l1.7-0.6l-0.4,2.9h-1l0.2,1l0.3,1.3l-1.2,0.8v2.8l1.5,1.3l0.6,2.8l-0.4,2l-0.9,0.4l0.4,1.4l1,0.4l0.7,1.6v2.4l1,1.9l2,2.4v0.6';
$svg .= '	l-2.1-0.2l-1.5,1.3l0.2,1.3l-0.8-0.3l-1.3-0.2l-3.1-3.4l-2.1-0.6h-6.5l-2.6-0.7l-3.3-2.8l-1.6-0.9h-1.9l-2.9-2.3l-5-1.5v-1.2';
$svg .= '	l-1.3-1.7l-0.8-4.3l-1-1.6l-1.6-1.3v-1.5l-1.3-0.6l0.6-2.4l-0.3-2l-1.2-1.3l0.6-2.8l-0.7-2.9l-1.6-1.3h-1l-3.7-3.2v-1.7l-0.6-1.6';
$svg .= '	l-0.7-0.2l-0.8-2.2l-1.8-1.5l-2.7-2.3l-0.2-1.9l-0.9-0.6l0.2-1.5l0.5-0.6l-1.3-1.4v-0.6l-1.7-2v-1.9l-2.4-4.5v-1.6l-1.7-2.8';
$svg .= '	l-4.7-4.4v-1l-3-1.6V448l-1.2-0.4V447l-0.7-0.2l-1.9-2.6h-0.7l-0.6-0.6l-1.2,1h-2l-2.4-1h-4.2l-3.9-1.9l-1.2,1.7l-2-0.6l-3,1.1';
$svg .= '	l-1.6,2.6l-1.8,2.9l-1,4l-1.3,1.1h-1l-0.8,1.6l-1.2,0.6v1.7h-2.8l-1.7-1.4h-0.9l-1.8-2.7l-3.3-0.5l-1.6-2.1l-1.2-0.2l-1.9-0.7';
$svg .= '	l-3.1-3.1l0.2-0.7l-1.5-1.1h-0.9l-3.1-2.9v-1.8l-2.2-3.7l0.2-1.5l-0.6-1.2l0.7-1.4v-2.2l-2.5-3.8l-0.6-3.9L362,422v-0.9l-1.1-0.2';
$svg .= '	l-0.6-1l-2.2-1.6h-0.8l-1.7-1.6v-1l-2.7-1.7l-0.6-1.9l-2.4-2.1l-2.9-4l-2.8-1.2l-1.9-1.7l0.2-1.1l-1.2-1.3l-1.6-3.4l-2.2-0.9l-1.8-2';
$svg .= '	L335.6,395.1z M496.3,522.2h0.7l-0.6-4.3l-3.2-11.3l-0.2-7.4l4.5-9.6l5.6-7.5l6.6-4.7v-0.6H509l-2.4,0.9l-3.3,2.1l-0.6,1.4';
$svg .= '	l-7.5,10.7l-2.6,7.3v8.1l3.3,11l0.4,4.1L496.3,522.2z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M293.7,200.9l3-20.1l-44-7.5l-19.3,100.2l42.4,7.5l36.8,5.5l10.6-81.1l-29.5-4.4V200.9z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M865.5,95l-1,3.2l1.9,2.6l-0.4,1.6v1.2l-0.9,1.9l-1.3,0.4l-0.6,1.2l-1.9,0.9l-0.6,1.4l1.3,3.1l-0.5,2.3l0.5,1.4';
$svg .= '	l-0.9,1.7l0.4,1.7l-1.2,1.7l0.2,2l-0.6,1l0.6,4.1l0.6,1.4l-0.5,2.4l0.8,1.7l-0.2,2.3l-0.5,1.2v1.3l1.8,2.4l-11.4,2.5l-1-0.9l0.5-1.8';
$svg .= '	l-2.8-13l-1.7-1.4l-0.8,1.5l-0.8-2l0.7-1.7l-2.8-6.2l0.3-3.5l0.4-0.9l-0.6-1.8l0.4-2l-2-2.1l-0.5-2.9l0.4-1.4l-1.3-0.8l0.6-1.7';
$svg .= '	l-0.7-1.6l25.1-6.3l1.1,2.1L865.5,95z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M843.1,244.8l-1,2.6l0.5,1l0.4-1l0.7-2.8l-0.6,0.3L843.1,244.8z M811.3,238.3l-0.6-0.9h0.9l0.9-0.9l0.4-1.7';
$svg .= '	l-0.2-0.5v-0.5l-0.2-0.6l-0.6-0.5h-0.4l-0.5-0.5l-0.6-0.6h-1.5l-0.4-0.5v-0.5L807,230l-0.7,0.3h-1.1l-0.6-0.7l-0.5-0.2l-0.2-0.6';
$svg .= '	l0.6-0.7v-0.8l-1.1-0.2l-0.9-0.8h-0.8l-1.5-0.2l-0.4,0.6l-0.4,1.5l-0.5,2.1l-9.2-4.8l-0.2,0.8l0.8,1.5l-0.7,2.1v2.7l-1,0.7l-0.5,1.9';
$svg .= '	l-0.8,0.7l-1.3,1.7l-0.8,0.7l-0.9,2.3l-2.2-1l-2.1,7.8l-1.2,1.5l-2.6-0.5l-1.2-1.7l-2.1-0.6v4.3l-1.4,1.6l0.4,1.4l-1.9,2l0.4,1.7';
$svg .= '	l-3.4,5.8l-0.9,3l1.4,1.1l-1.4,1.7v1.3l-2,1.8l-0.6-1l-4,2.8l-1.4-0.9l-0.6,1.3l0.7,0.5l-0.5,0.8l-5.1,2.2l-2.8-1.7l-0.7,1.6';
$svg .= '	l-1.7,1.7h-2.1l-4-2v-1.4l-1.5-0.6l0.7-1.1l-0.6-0.6l-4.5,6.1l-2.7,0.9l-2.8,2.8l-0.4,2l-1.9,1.2v1.6l-1.4,1.3l-1.7,0.5l-0.5,1.7';
$svg .= '	l-0.9,0.4l-6.3,3.9l26.6-3l0.2-0.9l4.2-0.5l-0.3,0.6l27-3.2l36.2-6.7l26.7-5.6l-0.6-1.1h0.4l0.8,0.7v-1.3l-0.4-1.7l1.5,1.1l0.8,1.9';
$svg .= '	v-1.2l-3.1-5.1v-1.1l-0.6-0.7l-1.2,0.6l0.5,1.3h-0.7l-0.4-0.9l-0.6,0.8l-0.8-1h-1.9l-0.2,0.6l1.4,1.9l-1.3-0.6l-0.5-0.9l-0.4,0.7';
$svg .= '	h-0.7l-1.4,1.7l0.3-1.5v-1.3l-1.4-0.6l-1.7-0.5l-0.2-1.6l-0.6-1.2l-0.6,1l-1.6-0.9l-1.8,0.3l0.2-0.8l1.4-0.2l0.8,0.5l1.6-0.7';
$svg .= '	l0.8,0.4l0.5,0.9v0.6l1.7,0.4l0.3,0.8l0.8,0.4l0.8,1.1l1.3-1.5h0.6v-1.9l-1.3,0.9l-0.6-0.8l1.4-0.2l-1.1-0.8l-1.1,0.6V264l-1.7,0.2';
$svg .= '	l-2-1l-1.7-2l3.3,2l0.8,0.3l1.6-0.7l-1.6-0.8l0.6-0.6l-0.9-0.5l0.7-0.2l-0.3-0.8l1,0.8l0.4-0.7l0.4,1.2l1.1,0.7l0.6-0.5l-0.5-0.6';
$svg .= '	v-2.3h-1.1l-1.5-0.7l0.8-1h-1.8l-0.4-0.6l-1.3,0.6l-1.3-0.7l-0.5-1.1l-1.9-1.1l-1.9-1.7l-2-1.7l2.8,1.2l0.8,1.1l1.9,0.6l2.1,2.3';
$svg .= '	l0.2-1.6l0.6,1.2l2.1,0.5v-3.7l-0.7-1l1,0.4V250l-2.8-1.3l-1.5-0.2l-1.2-0.2l0.3-1.1l-1.4-0.3v-0.6h-1.7l-0.2,0.7l-0.6-0.9h-2.5';
$svg .= '	l-0.9-0.4l-0.2-0.9l-1.1-0.6l-0.4-1.4l-0.6-0.4l-0.6,1l-0.8,0.2l-0.8,0.6h-1.4L809,243l0.4-2.8l0.5-2.2l0.6,0.5l0.3-0.4L811.3,238.3';
$svg .= '	z M831.5,249h0.8v-0.6h-0.7L831.5,249L831.5,249z M838.3,262l-0.9,2.5l1.1-1.2L838.3,262L838.3,262z M836.7,248l0.6,0.3l-0.2,1.7';
$svg .= '	l-0.5-0.5l-1.2,0.9l0.9,0.4l-1.7,4v7.4l1.8,2.8l0.5-1.4l0.4-2.5L837,259l0.6-0.8l-0.2-1.3l1.1-0.6l-0.6-0.5l0.5-0.6l0.7,1l-0.2,1';
$svg .= '	l-0.4,3.6l1-2l0.4-2.8v-2.8l-0.2-1.8l0.6-2.1l1-1.7v-2l0.4-0.8l-4.2,1.5l-0.6,0.7l-0.4,0.8L836.7,248z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M153.5,43.1l1.1,1.6l0.5-0.9l0.6,1.7h0.6l0.6-0.7h0.6l1.8-1.8l0.2-1.1l0.7,0.6l0.3,0.8l0.6-0.3v-1.1h1.3';
$svg .= '	l0.2-2.7v-2.5l0.7,0.3l-0.6-1.9l1.3-0.7l0.2-2.2l2.1-2h0.9l0.3-1.2l-1.1-1.3v-3.2l-0.8,0.8l0.6,2.7h-0.6l-0.6-1.7l-0.6-0.5l0.3-2.1';
$svg .= '	h1.7l0.3,0.6l0.3-1.5l-1.5-1.6l-0.6-1.5l-0.2,1.8l0.8,1l-0.6,0.4l-0.9-0.7l-1.7,1.2l1.4,0.5l0.2,2.2l-0.3,1.7l0.8-1.2l1.3,2.1';
$svg .= '	l-0.4,1.7h-1.4v-1.1l-1.4-1.1l0.5-2.8l-1.7-2.4l2.5-2.8l0.6-3.8h0.8l1.3,2.9v-2.4l1.1,0.3v-3l-0.8-0.7l-1.1,2.3l-0.9-2.8h1.2';
$svg .= '	l-1.4-4.6l1.7-0.6l23.3,6.9l29.1,7.4l21.7,5.1l-11.7,51.5l0.6,3.7l-1,4l-27.8-6.7l-2.6,0.9l-5-0.8l-1.7-0.8l-1.4,1.1l-3-0.4';
$svg .= '	l-4.1,0.5l-0.8,0.6l-3.9-0.4l-0.7-1.5l-1.1-0.2l-4,1.2l-1.5-1l-2,0.7l-0.2-1.7l-2.1-1.1l-1.4-0.2l-0.9-1l-2.8,0.3l-1.1-0.7h-1.1';
$svg .= '	L158,76l-5.1,0.6l-6.1-3.9l1-5.1l-0.4-3.8l-2.9-3.4h-3.4l-0.4-0.9l0.4-1.1l-0.6-0.7h-0.9l-1.9-1.3l-1.1,0.4h-1.8l-0.6-1.5l-1.5-0.3';
$svg .= '	l0.8-2.4l0.9,1l0.5,0.5v-1.8l0.7-0.2l1,2.1l-0.5-2l1.1-3.9l1.7,0.4l-1-1.8l-0.9,0.3l-1.4-0.4l0.2-3.9l0.2,1.4l0.8,0.5l0.6-1.5h2.9';
$svg .= '	l-2-1.1l-1.6-1.7l-1.3,1.5l1.1-2.8l-0.3-4.2l-0.2-3.3l0.8-5.6l-0.5-1.8l-1.3-1.9v-3.7l0.5-2.5l1.8-2.1l-0.6-1.3l0.2-0.6h0.8l7.2,7.1';
$svg .= '	l4.3,1.7l4.7,2.3h2.9L157,26l0.9-1.5h0.6l0.6,2.5l0.5-2.4l1.3-0.2l0.5,0.6l-1,0.6v1.5l0.7-1.4h1l-0.4,2.4l-1-0.7l0.4,1.3v1.4';
$svg .= '	l-0.8,0.6l-2.3,2.7l1.1-3.1l-1.5,0.4l-0.4,1.9l-3.5,2.6l-0.4,0.9l-1.9,2V39h1.9l2.2-0.2L156,38l-3.6,0.5v-0.6l2.4-2.6l1.7-0.7';
$svg .= '	l1.7-0.2l0.9-1.5l2.8-2.1v-1.3h1v3.7h-1.3l-0.6,0.7l-1-0.8l0.3,1v1.6l-0.6,0.6l-0.3-1.5l-0.7,0.7l0.6,0.6l-0.8,1h1.2l0.6-0.5v1.8';
$svg .= '	l-0.8,1.7l-0.8,0.9v1.7l-1-0.2l-0.2-1.3l0.8-1l-0.7-0.5l-0.7,0.6l-0.6,2l-0.7,0.8v-1.8l0.6-1l-0.2-1l-1.1,1.1v2l-0.5,0.4l-1.9-0.4';
$svg .= '	l1.9,2.2L153.5,43.1z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M740.9,274.3l-0.7,1.1l1.4,0.6v1.4l4.1,2.1h2.1l1.7-1.7l0.7-1.6l2.8,1.7l5.1-2.2l0.5-0.8l-0.7-0.5l0.6-1.3';
$svg .= '	l1.4,0.9l4-2.8l0.6,1l2.1-1.8v-1.3l1.3-1.7l-1.4-1.1l0.9-3l3.4-5.8l-0.4-1.7l1.9-2l-0.4-1.4l1.3-1.6v-4.3l2.2,0.6l1.2,1.7l2.6,0.5';
$svg .= '	l1.2-1.5l2.1-7.8l2.2,1l0.9-2.3l0.8-0.7l1.3-1.7l0.8-0.7l0.5-1.9l1.1-0.7v-2.7l0.6-2.1l-0.8-1.5l0.2-0.8l9.2,4.8l0.5-2.1l0.4-1.5';
$svg .= '	l0.4-0.6l-0.8-0.4l0.2-1.5l-0.9-0.5l-0.2-0.6h-0.6l-0.7-1.1l0.2-0.9l-2.4,0.4l-2-1.5l-1.3,0.3l-0.8,1.3h-1.2l-1.6,2.7l-3,0.4';
$svg .= '	l-1.7-0.9l-2.4,3.5l-2-0.3l-2.8,3.6l-0.8,1.5l-1.7,1.5l-1.6-10.5l-16,2.7l-2.9-18.1l-2,1.1l1.3,1.9v2l0.5,1.8l-1,3.1v5l-1,3.3l0.5,1';
$svg .= '	l-0.4,2l-1,0.5l-1.8,3l-1.7,1.8h-0.6l-1.7,1.6l-1.2-1.1l-1.4,1.7l-0.3,1.1h-1.2l-1.2,2v1.9l-0.8,0.5l1.3,1v1.7l-0.9,0.2l-0.6,0.7';
$svg .= '	l-0.9,0.5l-0.6-1.9l-1.5-0.5l-0.9,2.1l-0.3,2l-1,1.2l1.2,3.3l-1.4,0.7l-0.4,3.2h-1.4l-2.9,1.3v1l0.5,0.9l-0.6,3.3l1.7,1.5l0.7,1';
$svg .= '	l0.9,0.6v0.8l4,5.1h1.3l1.4,1.7l1.1,0.3h1.3l0.6,0.5L740.9,274.3z"/>';
$svg .= '<path class="st2 region midwest" data-linkto="pd-midwest" d="M637.6,133.2l-2.7,0.7l0.2,2.1l-2.2,3.1l-0.2,2.8l0.6,0.6l0.7-0.6l0.5-1.5l1.8-1l1.5-3.9l3.2-1l0.7-3l0.6-0.8';
$svg .= '	l0.4-1.9l1.7-1v-1.4l0.9-0.8h1.3v1.9h-0.9l0.5,1.2l-0.6,2H645l-1.1,4.2l-0.6,0.5l-2.6,6.6l-0.3,3.9l0.6,1.8v1.2l-2.1,1.7l0.3,1.7';
$svg .= '	l-0.8,2.8l0.3,1.5l0.4,3.4l-1,3.8l-1.4,4.6l0.9,1.4l-0.3,0.3l0.7,1.6l-0.5,1l1,0.8v2.5l1.2,1.4l-0.4,2.8l0.3,3.7l-42.2,2.6l-1.2-2.6';
$svg .= '	l-3-0.6l-2.5-1.4l-1.8-5.1v-2.3l1.6-3l-0.6-1L588,169l-0.2-2.4l-1-4.1l-0.2-2.8l-2-2.8l-2.6-0.6l-4.8-3.3l-0.6-3l-5.8-2.8l-0.2-1.2';
$svg .= '	h-3l-2-2.4l-1.8-1.2l0.6-4.7l-0.8-1.5l0.5-5l0.9-1.7l-0.3-2.5l-1.1-1.2l-1.7-0.3v-1.6l2.6-5.3l5.4-3.6l-0.4-11.9l0.8,0.4l0.6-0.5v-1';
$svg .= '	l0.9-0.6l1.3,1.1h0.6h2.4l6.2-2.4l0.3-0.9h1.1l0.6-1.1l0.4,0.7l1.7-0.8l1.7-1.6l0.3,0.5l0.9-0.9l2,1.5l-0.7,1.5l-1.1,1.3l0.5,1.4';
$svg .= '	l-1.3,1.5l0.4,0.8l2.1-1v-1.3l3,1.7l1.7,0.6l1.7,0.6l2.8,3.5l15.6,3.5l1.3,0.9l3.7,0.7l0.6,0.5l2.6-0.2l4.5,0.7l1.3,1.4l-0.9,0.9';
$svg .= '	l0.7,0.7l3.5,0.6l1.1,1.1v4l-1.1,2.6h1.8l0.9-0.6l0.8,0.7l-1,2.8l0.9,1.5l1.1,0.3l-0.3,2.8L637.6,133.2z M592.1,99h-0.5l-1.4,1.6';
$svg .= '	l0.2,0.5l1.4-0.6v-0.6l0.8-0.3L592.1,99L592.1,99z M593.6,97.9l-0.9,0.3l-0.2,0.6h0.8L593.6,97.9L593.6,97.9z M592.4,96.5l-0.2,0.8';
$svg .= '	h1.6l0.6-0.4V96L592.4,96.5L592.4,96.5z M595,93.7l-0.3,1.7l1.1-0.5v-1.3H595V93.7z M648.5,123l-1.8,0.3l-0.4,1.2l1.2,1.6L648.5,123';
$svg .= '	L648.5,123z"/>';
$svg .= '<path class="st1 region westregion" data-linkto="pd-west" d="M405.9,132.7l-46.9-4.9l-52.6-7.3l-1.8,9.8l-7.8,50.4l-3,20.1l29.5,4.4l41.3,5.2l34.5,3.1l3.4-40.6l3.6-40.3';
$svg .= '	L405.9,132.7z"/>';
$svg .= '<path class="st0 region eastregion" data-linkto="pd-east" d="M814.5,232.5l-2.4-1.7l-0.9,1.6l0.5,0.4h0.4l0.6,0.6l0.3,0.6v0.5v0.5l1.6-2.4L814.5,232.5z"/>';
$svg .= '<circle class="st0 region eastregion" data-linkto="pd-east" cx="812.7" cy="232.6" r="4.6"/>';
$svg .= '<text transform="matrix(1 0 0 1 139.4998 268.5999)" class="st5">CA</text>';
$svg .= '<text transform="matrix(1 0 0 1 159.2998 120.5999)" class="st5">OR</text>';
$svg .= '<text transform="matrix(1 0 0 1 183.4998 55.2999)" class="st5">WA</text>';
$svg .= '<text transform="matrix(1 0 0 1 325.0999 85.4999)" class="st5">MT</text>';
$svg .= '<text transform="matrix(1 0 0 1 251.2998 144.4)" class="st5">ID</text>';
$svg .= '<text transform="matrix(1 0 0 1 196.0998 218.9999)" class="st5">NV</text>';
$svg .= '<text transform="matrix(1 0 0 1 273.5999 240.5999)" class="st5">UT</text>';
$svg .= '<text transform="matrix(1 0 0 1 254.5998 338.7999)" class="st5">AZ</text>';
$svg .= '<text transform="matrix(1 0 0 1 343.8998 351.7)" class="st5">NM</text>';
$svg .= '<text transform="matrix(1 0 0 1 357.9998 260.4)" class="st5">CO</text>';
$svg .= '<text transform="matrix(1 0 0 1 345.2998 173.2)" class="st5">WY</text>';
$svg .= '<text transform="matrix(1 0 0 1 449.2998 91.2999)" class="st5">ND</text>';
$svg .= '<text transform="matrix(1 0 0 1 531.7998 120.5999)" class="st5">MN</text>';
$svg .= '<text transform="matrix(1 0 0 1 552.2998 202.5999)" class="st5">IA</text>';
$svg .= '<text transform="matrix(1 0 0 1 601.7999 153.5999)" class="st5">WI</text>';
$svg .= '<text transform="matrix(1 0 0 1 679.4998 168.7999)" class="st5">MI</text>';
$svg .= '<text transform="matrix(1 0 0 1 620.1998 239.0999)" class="st5">IL</text>';
$svg .= '<text transform="matrix(1 0 0 1 573.9999 412.4999)" class="st5">LA</text>';
$svg .= '<text transform="matrix(1 0 0 1 569.8998 347.7)" class="st5">AR</text>';
$svg .= '<text transform="matrix(1 0 0 1 563.9999 279.4999)" class="st5">MO</text>';
$svg .= '<text transform="matrix(1 0 0 1 662.6998 234.9)" class="st5">IN</text>';
$svg .= '<text transform="matrix(1 0 0 1 713.7999 225.4999)" class="st5">OH</text>';
$svg .= '<text transform="matrix(1 0 0 1 785.2999 198.2)" class="st5">PA</text>';
$svg .= '<text transform="matrix(1 0 0 1 813.8998 151.0999)" class="st5">NY</text>';
$svg .= '<text transform="matrix(1 0 0 1 746.7999 255.0999)" class="st5">WV</text>';
$svg .= '<text transform="matrix(1 0 0 1 686.0999 286.4)" class="st5">KY</text>';
$svg .= '<text transform="matrix(1 0 0 1 670.3998 319.4999)" class="st5">TN</text>';
$svg .= '<text transform="matrix(1 0 0 1 620.7999 383.2999)" class="st5">MS</text>';
$svg .= '<text transform="matrix(1 0 0 1 665.9998 380.2999)" class="st5">AL</text>';
$svg .= '<text transform="matrix(1 0 0 1 721.7999 377.8999)" class="st5">GA</text>';
$svg .= '<text transform="matrix(1 0 0 1 766.2999 461.8999)" class="st5">FL</text>';
$svg .= '<text transform="matrix(1 0 0 1 765.6998 350.2999)" class="st5">SC</text>';
$svg .= '<text transform="matrix(1 0 0 1 787.8998 311.2999)" class="st5">NC</text>';
$svg .= '<text transform="matrix(1 0 0 1 786.2999 272.2)" class="st5">VA</text>';
$svg .= '<text transform="matrix(1 0 0 1 858.9998 267.2)" class="st5">DC</text>';
$svg .= '<text transform="matrix(1 0 0 1 868.5999 248.9999)" class="st5">MD</text>';
$svg .= '<text transform="matrix(1 0 0 1 845.5999 108.9)" class="st5">VT</text>';
$svg .= '<text transform="matrix(1 0 0 1 865.2999 132.0999)" class="st5">NH</text>';
$svg .= '<text transform="matrix(1 0 0 1 863.3998 150.4999)" class="st5">MA</text>';
$svg .= '<text transform="matrix(1 0 0 1 859.6998 166.9999)" class="st5">CT</text>';
$svg .= '<text transform="matrix(1 0 0 1 900.4998 179.7999)" class="st5">RI</text>';
$svg .= '<text transform="matrix(1 0 0 1 862.6998 203.9999)" class="st5">NJ</text>';
$svg .= '<text transform="matrix(1 0 0 1 867.1998 225.9)" class="st5">DE</text>';
$svg .= '<text transform="matrix(1 0 0 1 888.7999 77.7)" class="st5">ME</text>';
$svg .= '<text transform="matrix(1 0 0 1 448.3998 153.5999)" class="st5">SD</text>';
$svg .= '<text transform="matrix(1 0 0 1 453.5999 213.5999)" class="st5">NE</text>';
$svg .= '<text transform="matrix(1 0 0 1 467.8998 273.4)" class="st5">KS</text>';
$svg .= '<text transform="matrix(1 0 0 1 485.8998 330.4999)" class="st5">OK</text>';
$svg .= '<text transform="matrix(1 0 0 1 450.3998 400.3999)" class="st5">TX</text>';
$svg .= '<g>';
$svg .= '	<path class="st1 region westregion" data-linkto="pd-west" d="M21.4,471.2h2.2l0.6,0.6l-0.9,1.1l-1.7,0.2l-2.3,1.2h-3.4l2-0.9l0.3-1l2.3-0.3L21.4,471.2L21.4,471.2z';
$svg .= '		 M29,469.7l1.2,0.5H31l0.5,1.1l0.3-0.6l0.8,0.2l1,1.4v0.5l-3.9,1.7h-2.2l-0.9-0.6l-1,0.6h-1.8l-1-1.3l4.3-0.5L29,469.7L29,469.7z';
$svg .= '		 M34,469.6h0.9l0.6,0.7v0.9h-1.2l-0.8-0.9L34,469.6z M36.3,469.9h1.2v0.8l-1,0.6v-1.4L36.3,469.9z M36.5,471.9h3.1l0.2,0.9h-1.2';
$svg .= '		l-0.3-0.4l-0.7,0.6l-0.4-0.6l-0.8-0.2v-0.4L36.5,471.9z M189.3,478.9h1.9l-0.9,1.8h-1l-0.4-0.8l0.5-1.2 M188.4,476l0.6-1.2';
$svg .= '		l-0.2-2.1l2.2-0.5l4.1,4l1.2,3.1l1.7,1.5l0.3,4.7H197l-1.2-2.1l-2.8-2.2h-0.6l1,2.6l1.6,0.2l0.2,1.9h-0.8l-3.8-4v-0.8l1.7-0.9v-0.9';
$svg .= '		l-0.5-0.7l-1.5-0.6l-1.6-1.2h1.3l0.5-0.3l-0.6-0.8l-0.6,0.5l-1-0.2H188.4z M185.1,467.7h1.2l2.2,2.4l-0.2,0.7h-0.7v1.6l0.5,0.5v1.4';
$svg .= '		l-0.7,0.3l-0.4,1.1l-0.7-0.4l-0.4-2l1-1.3l-1.9-2v-1.1l0.3-1.1L185.1,467.7z M186.5,466.3l1.7,0.2h2.3l3.1,3l-0.2,0.5l-1,0.6';
$svg .= '		l-1-0.2v-0.6l-1.2-1.5l-0.3,0.6l0.9,1.2l-0.2,1.1h-0.7h-1.2v-1.6l-2.5-2.6L186.5,466.3L186.5,466.3z M174.8,458.1l0.8-0.4h1.5';
$svg .= '		l0.6-0.5l3.8,2v1.4l-0.4,0.5h-0.7l-1.3-0.6l1,1.2h1.7l0.5,1.8h-0.8l-2-1.4l-1-0.2l0.6,1.2v0.8l0.8-0.6l1.6,1.1h1.2l-0.2,0.6l1.7,4';
$svg .= '		v3.1l0.4,1.9l-0.7,0.3l-1.1-1.8l-0.5-1.4l-1.5-1.5l-0.2-2.5l-0.6-1.6h-0.6l0.3,1v0.5l-1.3,0.9v-3l-1.4-1.5l-1.2-2.1l-1.1-1.1';
$svg .= '		l0.2-2.3L174.8,458.1z M181.4,456l1,1.7h2.2l0.9,1.8l-0.6,0.6l1.8,2.9v1.2l-1.1,0.7v0.6l-1.8,1.7l-0.5-1.3v-1.2l0.5-0.6v-1';
$svg .= '		l-1.4-1.7l-0.5-3.4l-0.8-1.4l0.2-0.6L181.4,456z M129.3,439.2l-3.7,3.8v1.5l1.9-0.7l0.7-1.7l2-2.2l-1-0.6L129.3,439.2z';
$svg .= '		 M100.3,454.4v0.6l1.7,1.1l0.2-1.3l0.6,0.8h3.2l0.6-0.5l0.2-1.7l-0.5-0.6H105v-0.7l0.4-0.6v-0.4l-1.4-0.3l-3,3.3l-0.6,0.2';
$svg .= '		L100.3,454.4z M92.9,460.1l1.4,5.3h1.9l2.2-2.3l0.3,1.1l5.8-3.7l0.6-0.9l-0.9-1V458l0.5-1.2h-0.8l-1.8,0.8v-1.1l-2.5-0.6l-2.2,0.3';
$svg .= '		l-0.2,3.1l-0.7-1.8h-1.4l-0.9,0.5l-1.2,2.1L92.9,460.1z M90.8,467.7v-0.6l2-1.2l0.6,0.3l1.2,0.2l1.2,1.1l-2-0.2l-0.4-0.6l-0.9,0.6';
$svg .= '		L90.8,467.7L90.8,467.7z M80.3,463.4h1.3L82,464h-1.7C80.3,464,80.3,463.4,80.3,463.4z M67.5,474.4v0.5h0.6v-0.5H67.5z M67.1,471.4';
$svg .= '		l-0.9,0.9v0.5l0.6,1l0.9-0.9h-0.6V471.4z M65.3,470.7l-0.3,0.9h-1.2l-0.4,0.3v1.2l-0.5,0.8h0.6l0.6-0.8h0.7l0.8-1l0.2-1.2l-0.6-0.2';
$svg .= '		H65.3z M61.3,468.8l-0.2,1.7l1.3,0.7l1.1-0.6v-0.9l1.6-0.3v-0.6l-0.9-0.2l-0.6,0.6l-0.8-0.5L61.3,468.8L61.3,468.8z M56.8,468.8';
$svg .= '		l0.9,0.6l-0.3,1.1l-1.3-1L56.8,468.8L56.8,468.8z M52.9,469.9h1.3l-0.6,0.8l-0.6-0.8H52.9z M49.7,472.7l1.7,1h-1.6v-1H49.7z';
$svg .= '		 M48,420.5h1.4l0.8,0.3l1-0.5h1.2l1.5,0.6l0.7,1.7v0.8l-1.2,1.8l-2.2-0.2l-1.9-1.7l-0.9-0.4l-1-1.8l0.6-0.8L48,420.5z M45.4,385.9';
$svg .= '		v1.1l1.7,1.7h2.1l0.6,1v1.5l1.9,1.7l1.7,1.1v0.6l-0.7,1l-1.3-1.1h-1.9l-0.7-0.6L48,392l-1.4-2h-2.4l-0.9-0.7l0.9-1.9l1.3-1.4';
$svg .= '		L45.4,385.9z M75.8,399.8h0.8v1.2l-1.6-0.5L75.8,399.8L75.8,399.8z M193.2,471.2l-1.1,0.4v1h1v-1.4H193.2z M48.4,467.1l-1.2-0.4';
$svg .= '		l-3.8,0.6l-2.6,1.3v1.7l1.7,0.6l1.4-0.8h1.6l4.3,1.2v-1.2l-1.4-1V467.1L48.4,467.1z M50.3,469.2l-0.4-1.3l1.1,0.2v1.3h1.7l0.4-2.3';
$svg .= '		l0.3,2.2h2.3l2.9-3.1h0.7l-0.6,1.3l1.3,0.8l3.9-0.2l2.4-1.1h1.3l0.3,1.3l0.6-0.5l0.4-1.3l5.4,0.2l1.7-1.5l-1.2-1l0.6-1.1l2.4,0.2';
$svg .= '		l-0.2-1.1l2.3,0.2l0.6-1l1,0.2l4.2-1.7l0.2-1.6l5.1-2.2l1.8-1.7l1.1-0.6l1.2,0.7l2.1-0.8l1-1.7l0.5-1.2l1.6-0.8l1.4-0.6l0.4-1.3';
$svg .= '		l-1-1.6l-2-0.2l-0.2-1.2l0.7-1.5l1.3-0.2l1.2-1.4h1.7l3.1-3l0.4-1.3l1.4-2.1l3.5-3.8l2.3-0.8l1.7-0.8l1.9,0.7l1.3,2.4H118l-1.3-1.4';
$svg .= '		l-2.8,1.8h-1.6l-0.2,2.9l-2.8,4.5l0.6,1.8h2.1l-0.6,0.9h-1.3l-2.2,1.7v0.8l1.7,0.9l3.1-0.6l1.3-1.6h1.3l2.8-1.5l0.5-2.1h1.5';
$svg .= '		l5.8,0.6l0.9-1l0.9-4.1l-1.5,1l0.6-2l-1.5-1.3l0.7-1.4v1.4h3.2l0.6-0.9h1.5l-0.3,1.5h1.7l-1.7,1.3l3.8,1l-3.2,0.4l-1.2,1.1l0.8,1.3';
$svg .= '		l4.2-1.6l2.1,1.6l0.6-0.8l0.6,1.3l3.7,2.1h2.7l3.6-0.5l4,1l1.8,1.7l4.1,0.4l1.7-1.4l0.7,2.2l-1.7,0.6l1.1,1.1l6.8,3.5l1.3,2.3';
$svg .= '		l5,3.8l3-1.8l-0.6-2l-3.2-1.8l2.8,1.1l0.5-0.6l0.8,1.2v2.5l1.9-0.6l1.9,1.7l-2.3-9l1.1,1.2l1.3,5.5l2,2.3l2.2-0.4l1.7,3.2h0.8';
$svg .= '		l0.6,5.1l3.1,0.5l1.5,2l1.7,1l0.4,2.6l-1.7,2.4l2.7,1.5l1.1-2.2l-0.2,2.8l-0.7,0.8l1.3,1.6l0.6-2.2l-0.2-1.1l0.7,0.2l0.6,2.1';
$svg .= '		l-0.9,1.3l0.6,2.4l0.5,0.4l0.3-1.5l0.6,0.6l-0.3,1.8l1.1,0.2l-0.4,0.8h1.6v-1h-0.9v-1.6l-0.6-0.6l1.6-0.3l0.5-0.7v-1.5l0.5,1.2';
$svg .= '		l-0.6,1.7l1.1,3.6h1.7l2-3.8v-1.7l-1.1-3.7v-1.1l0.4-1.1l-0.6-0.6h-1.6l-2.3-1.7h-1.6l-1.8-1.3h-1.4l-0.5-1.5l-1.3-0.3l-0.2-1.4';
$svg .= '		l-0.9-0.5v-1.6l-4.6-6.8l-1.7-1.4v-1.1l-4-3.2l-0.6-1l-1.5-1.8l-1.7-0.6v-2l-1.1-1.2l-1.6-0.6l-1.9,1.2l-1.5,1.9l-0.4,2.2h-1.4';
$svg .= '		l-2.3,2.6l-0.7-0.3v-2.3l-2.2-2l-2.1-1.8l-0.5-1.8l-2.3-1.2l0.2-2h-2.6l-0.6,0.9h-1.1l-0.6-0.6l-1.1,0.7l-1.7-1.1v-78.8l-6.3-3.8';
$svg .= '		l-1.7-0.5l-2,1h-2l-2.1-1.4l-4-0.6l-5.3-3.3l-5.2-0.4l-1.8,0.5l-0.2-1.7l-1.7-0.6l1-0.9l-0.2-0.8l-2.9-1h-2.2l-0.4,0.4L117,348';
$svg .= '		v-2.4l-0.6-0.8l-2.3,2.7h-0.7v-0.8l1.6-0.7v-0.7l-1.7-2.2h-1l-4.1,2.8h-3.6l0.4-0.8h-1.7l-4.8,3h-1.7l-0.6-0.7l-2.5,1.4l-3.3,3.4';
$svg .= '		l-2.6,2.5l-1.4,1.1H84l-2-0.3l-2.1-1.2l0,0l-2.6,3.6v2.2l2.3,2.2l1.9,4.1l0.2,4.9l2.7,1.8l3.1,0.4l0.6,0.7l-1.4,2.1l0.6,2.5';
$svg .= '		l-1.6-2.4v-2.2l-1.4-0.3v1.1l0.7,1.9l2.7,3.4h-1.3l-2,1l-5.7-2.3v-1.8l1.2-1.2v-1.3l-1.9-0.5l-2.1,0.2l-4.4,0.2l1.4,2.1l-1.7-1.7';
$svg .= '		l-7.7,1.1l-0.7,1.4l4.5,4.3l-0.7,1.3l-0.3,1.8l-0.6,0.7v1.7l4,3.3l3.8,0.2l4.2,1.7h1.8l0.7-0.6h3.5v-0.6l1.1,1v1.8h-2.2v3l0.6,2.9';
$svg .= '		l-2.7,2.5h-1.7l-1.8-0.8h-0.9l-2.8,2l-1.6,0.2l-1.3-2.6h-2.8l-2,1.8l-0.5,1.7l-3,1.7l-4.9,4l-0.3,2.8l0.6,2l0.9,1.1l0.9-0.4';
$svg .= '		l0.8,0.9l-0.7,0.6l-1.4,0.8l1,1.4l-2.4,1l0.7,2l1.6,2.1l0.7,3.8l3.7,1.4l2.4-0.7l1.6-1l0.5,1.9l0.3,4l-1.7,1.3v4l-0.6,0.8h-1.6';
$svg .= '		l1.6,1.1h1.9l0.4-1l4.2-0.6l1.8,2.4l1.2-0.6l1.2,4.7l0.9,0.5l0.9-0.6V444l0.9-0.9l0.6,1l0.2,1.5l1.5,0.4l4.3-1.1L86,446l-1.8,1';
$svg .= '		l-1.5,1.6l-2.6,6.4l-4,1.8l-1.3,1.4l-0.3,1.3l-0.9-0.6l-8.5,3l-1.7,3.8l-1.2-0.4l0.5-1l-1.4-1.3l-3.2-0.2l-4.9,2.9l-2,1.2h-2.1';
$svg .= '		l-0.5,2.2h1.6L50.3,469.2z"/>';
$svg .= '	<text transform="matrix(1 0 0 1 116.2999 401.8499)" class="st5">AK</text>';
$svg .= '</g>';
$svg .= '<g>';
$svg .= '	<path class="st1 region westregion" data-linkto="pd-west" d="M282,454.5l-0.2,2.9l1.6,1.7v1.1l-4.3,4.1v1.1l1.7,2.9l1.6,3.9v2.4l-0.5,1.1v3.1l3.9,1.9l1,1l1.1-1l1.9-3.3';
$svg .= '		l4.1-2.7l3-0.5l2.3-0.9l1.6-1.1l2.9-3.2l-2.6-1l-1.3-1.3v-1.6l-0.4-0.6h-1.8l0.2-2.3l-0.6-1.1l-2.4-2.1l-4.1-1.7l-2.6-0.2l-3-2.5';
$svg .= '		L284,454l-1.9,0.2L282,454.5z M267.9,438.8l-1,1.4v1.6l2.4,2.2l1.7,0.5l0.6,0.9l0.4,2.8l3.3,0.2l4.9-2.4v-2.3l-1.4-0.5l-3.2-2.4';
$svg .= '		l-1.7-0.3l-2.7,1.2l-1.4-2.5L267.9,438.8L267.9,438.8z M266.6,449.4l0.8-1.3l2.3-0.3l0.6,1.7h-3.7L266.6,449.4z M260.1,441.4';
$svg .= '		l1.6,3.7l2.8-0.6l0.3-1.8l-1.3-1.4L260.1,441.4L260.1,441.4z M256.4,435.3l-1,2.2h4.6l4.4,1.5l2.3-1.5l0.2-1.4l-4.4,0.2';
$svg .= '		L256.4,435.3L256.4,435.3z M241.7,425.5l-1.7,1.9l-2.7,0.6l0.7,2l2,2.6v0.9l2-0.3h2.1l1.6,1.2l3.2-0.7v-0.6l-0.9-0.7l-0.5-1.9';
$svg .= '		l-0.7-0.3l-0.5,0.9l-1.1-1.2l0.2-1.3l-1.7-3l-1-0.6l-1.1,0.6L241.7,425.5z M212.4,414.1l-3.9,2.7l0.2,2.1l2.2,1.1l1.7,1.2l2.5,0.4';
$svg .= '		l2.4-2l-0.2-1.7l0.7-1.6V415l-0.9-0.8h-4.8L212.4,414.1z M202.5,418.5l-0.3,1.1l-1.7,0.8l-0.6,1.7l0.9,0.7l1-1.4l1.7-0.6l0.4-2.4';
$svg .= '		h-1.5L202.5,418.5z"/>';
$svg .= '	<text transform="matrix(1 0 0 1 249.9994 468.1035)" class="st5">HI</text>';
$svg .= '</g>';
$svg .= '<line class="st6" x1="812" y1="232.8" x2="855.7" y2="262"/>';
$svg .= '<line class="st6" x1="811.2" y1="219" x2="866.5" y2="245"/>';
$svg .= '<line class="st6" x1="841.7" y1="228.6" x2="865.9" y2="222.7"/>';
$svg .= '<line class="st6" x1="848.7" y1="205.3" x2="859.9" y2="202.6"/>';
$svg .= '<line class="st6" x1="881.5" y1="162.6" x2="898.8" y2="170.4"/>';

$svg .= '</svg>';

	return $svg;
}