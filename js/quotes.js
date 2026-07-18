
//side
jQuery(document).ready(function($) {

		var cartID = 'customCart-64367';
		var quoteID = 'customQuote-64367';
		
		jQuery('body').on('click', '#customCart-64367 .single_add_to_cart_button', function(e) {
			
			var pid = $(this).attr('data-pid');
			var qty = $('#qty-'+pid).val();

          	addToCart(pid, qty);

			if (jQuery('#customCart-64367:contains("View Cart")').length > 0) {
			} else {
				jQuery('#customCart-64367').delay( 2200 ).append('<div class="response_to_cart"><a href="/cart/" class="added_to_cart wc-forward">View Cart</a></div>');
			}

			e.preventDefault();

			return false;

		});
	





			
			jQuery('body').on('click', '#customCart-64367 .single_add_to_cart_button', function(e) {

			var cartID = 'customCart-64367';
			var quoteID = 'customQuote-64367';
				
				var pid = $(this).attr('data-pid');
				var qty = $('#qty-'+pid).val();

				console.log('pid: '+pid);
				console.log(qty);

	          	addToCart(pid, qty);

				if (jQuery('#customCart-64367:contains("View Cart")').length > 0) {
				} else {
					jQuery('#customCart-64367').delay( 2200 ).append('<div class="response_to_cart"><a href="/cart/" class="added_to_cart wc-forward">View Cart</a></div>');
				}

				e.preventDefault();

				return false;

			});
		
		 

			jQuery('body').on('click', '#customQuote-64367 .single_adq_button', function(e) {

				var qid = $(this).attr('data-product-id');

				if (jQuery('#customQuote-64367 .reponse_to_quote').length){

				} else {
					console.log('show quote cart link');
				}

				if (jQuery('#customQuote-64367:contains("View Quote")').length > 0) {
				
				} else {

					jQuery('#customQuote-64367').delay( 2000 ).append('<div class="reponse_to_quote" style="display:block!important;"><a href="/quote-list/" class="added_to_quote wc-forward">View Quote</a></div>');
				
				}

				console.log('-end: '+quoteID);
				
				e.preventDefault();

			});

		
		jQuery("#date-"+64367+"").change(function () {
			var datechoice = $(this).find('option:selected').val();

			console.log(datechoice);
			if (jQuery.isNumeric(datechoice)) {
				if (datechoice != '') {
					displayVarient('64367', datechoice);
				} else {
					jQuery('.selectDate').hide();
				}
			} else {
				jQuery('.selectDate').hide();
			}




});
		});