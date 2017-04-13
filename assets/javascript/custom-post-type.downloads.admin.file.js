jQuery(document).ready(function() {

	var fileInfo = jQuery('.file-info'),
		fileSelect = jQuery('#file-download'),
		removeFileSelect = jQuery('#remove-file-download'),
		fileID = jQuery('#file-download-id');

	fileSelect.click(function(event) {
		event.preventDefault();

		fileFrame = wp.media({
			title: CustomPostTypeDownloads.selectFile,
			button: {
				text: CustomPostTypeDownloads.selectAsDownload
			}
		});

		fileFrame.on( 'select', function() {

			selection = fileFrame.state().get('selection');

			if ( ! selection )
				return;

			selection.each( function( attachment ) {
				fileID.val(attachment.attributes.id);
			});

			fileID.trigger('change');
		});

		fileFrame.open();
	});

	fileID.change(function(){

		fileSelect.addClass('loading');

		if ( '' === fileID.val() ) {
			fileInfo.html('');
			fileSelect.show();
			removeFileSelect.hide();
			return;
		}

		jQuery.post(ajaxurl, {file_id: fileID.val(), action: 'download-information'}, function(response){
			fileSelect.hide();
			removeFileSelect.show();
			fileInfo.html(response.data.output);
		});

		fileSelect.removeClass('loading');
	});

	removeFileSelect.click(function(event){
		event.preventDefault();
		fileID.val('');
		fileID.trigger('change');
	});

});
