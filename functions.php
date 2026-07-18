<?php



function silibas_toolkit_enqueue_style() {
    //wp_enqueue_style( 'core', 'style.css', false ); 
}

function silibas_toolkit_enqueue_script() {
    //if (is_page('5170')) {
        wp_enqueue_script( 'quotes-js', '/wp-content/plugins/estrellita-toolkit/js/quotes.js', false, true, true );
   // }
}

//add_action( 'wp_enqueue_scripts', 'silibas_toolkit_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'silibas_toolkit_enqueue_script' );




add_action('wp_footer', 'silibas_overrides');

function silibas_overrides() {

	?>
<style>
/*.page-id-5170 .adq-shipping {display: block!important;}
.page-id-5170 #ship-to-different-address {
	display: none!important;
}*/
.page-id-5170 .return-to-shop {
	display: none!important;
}
.page-id-5170 #quote_place_order {
	float: right;
	margin: 10px auto;
	padding: 15px 30px;
 	color: #fff;
}
.woocommerce .post-7081 .quantity input.qty {
	border:0;
	border-bottom:1px solid #ccc;
	border-radius: 0;
}
.woocommerce .post-7081 input[type=number]::-webkit-outer-spin-button,
.woocommerce .post-7081 input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.woocommerce .post-7081 input[type=number] {
    -moz-appearance:textfield;
    appearance:textfield;
}

</style>
<script>
	jQuery( document ).ready(function() {

		/* translation */
		let curUrl = window.location.href;
		let baseUrl = 'https://estrellita.com';
					
		if(curUrl.indexOf("/es/") > -1) {
			jQuery('.translateSite a').text('English');
		}

		jQuery('.translateSite a').click(function(e) {	
			if(curUrl.indexOf("/es/") > -1) {
				curUrl = curUrl.replace('/es/', '/');
				window.location.href = curUrl;
				jQuery('.translateSite a').text('English');
			} else {
				curUrl = curUrl.replace(baseUrl, baseUrl + '/es');
				window.location.href = curUrl;
				jQuery('.translateSite a').text('Español');
			}
			e.stopPropagation();
		});




		<?php if (is_page('5170')) { ?>


		<?php } elseif (is_single('7081')) { ?>
			jQuery('.quantity input.qty').prop('readonly');

			jQuery(document).on('click','.gfield_list_icons img', {} ,function(e){
				var modIcon = this.className;
				
				console.log(modIcon);

				var curQty = parseInt(jQuery('.quantity .qty').val());
				if (modIcon === 'add_list_item ') {
					jQuery('.quantity .qty').val(curQty + 1);
					jQuery('.quantity .qty').attr('value', curQty + 1);
				} else {
					console.log('remove');
					jQuery('.quantity .qty').val(curQty - 1);
					jQuery('.quantity .qty').attr('value', curQty - 1);
				}
			});
			//.woocommerce #product-7081 .single_add_to_cart_button.button.alt.gform_button
			jQuery(document).on('click','.woocommerce #product-7081 .single_add_to_cart_button.button.alt.gform_button', {} ,function(e){
				
				var validOrder = true;

				///validate the correct numbers have been filled out prior to click add to cart
				jQuery('.gfield_list_container tbody tr td input').each(function(index, element) {
					console.log(jQuery(element).val());
					//gfield_error
					if (jQuery(element).val().length == 0) {
						validOrder = false;
						return validOrder;
					}
				});

				if (validOrder) {

				} else {
					alert('Please check that every attendee field is completed.');
					e.preventDefault();
					return false;
				}


			});
			function add_error_classes(index) {
				//gform_body
				// jQuery('.gfield_list_container tbody tr').each(function(index, element) {

				// }
			}

		<?php } ?>

	});
</script>


	<?php
}

add_action( 'pre_get_posts', 'tribe_remove_wpseo_title_rewrite', 20 );
function tribe_remove_wpseo_title_rewrite() {
    if ( class_exists( 'Tribe__Events__Main' ) && class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() || tribe_is_map() || tribe_is_photo() || tribe_is_week() ) {
            $wpseo_front = WPSEO_Frontend::get_instance();
            remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
            remove_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
        }
    } elseif ( class_exists( 'Tribe__Events__Main' ) && !class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() ) {
            $wpseo_front = WPSEO_Frontend::get_instance();
            remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
            remove_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
        }
    }
};


add_action('wp_footer', 'estrellita_flip_script');

function estrellita_flip_script() {
    ?>
    <!-- flip it -->
    <script>
    jQuery(document).ready(function($) {
        if ( jQuery('.flipbooks').length){
            var checkExist = setInterval(function() {
               if(jQuery('.flipbook-overlay').length > 1) {
                  jQuery('.flipbook-overlay:last').hide();
               }
            }, 500);
        }
    });
    </script>
    <?php

}