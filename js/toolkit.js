
//side
jQuery(document).ready(function($) {
    $('#district-select').select2({
	  tags: true
	});
});

jQuery(document).on('click', '.region', function() {
	var pg = jQuery(this).attr('data-linkto');

	window.location.href = "https://estrellita.com/" + pg;

});
