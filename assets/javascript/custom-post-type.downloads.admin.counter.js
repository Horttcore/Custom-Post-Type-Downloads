jQuery(document).ready(function() {

	var resetCounter = jQuery('.reset-counter'),
		downloadCount = jQuery('.download-count');

	resetCounter.click(function(event){

		event.preventDefault();

		if ( confirm(CustomPostTypeDownloads.resetCounter) ) {

			resetCounter.addClass('loading');

			jQuery.post(ajaxurl, {
				action: 'reset-download-counter',
				nonce: resetCounter.data('nonce'),
				post_id: jQuery('#post_ID').val(),
			}, function( response ){

				console.log(response);
				downloadCount.html( response.data.message );

				resetCounter
					.hide()
					.removeClass('loading');

			});

		}

	});

});
