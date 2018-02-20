(function( $ ) {
	'use strict';
	$(document).ready( function(e) {
		$('button.cw-add-slide').on('click', function(e) {
			var output, index;

			index = $('.cw-slide-instance').length + 1;
			output = '<div class="wrap cw-slide-instance">';
			output += '<p><label><h4>Slide Title</h4>';
			output += '<input type="text" class="widefat" name="cw_slide_title[]" />';
			output += '</label></p>';
			output += '<button class="button cw-attach-image">Attach Image</button>';
			output += '<input type="hidden" value="" name="cw_slide_image[]" class="upload-image" />';
			output += '<textarea class="widefat" class="cw-slide-description" id="test_' + index + '" name="cw_slide_description[]"></textarea>';
			output += '</div>';

			if( index == 1 ) {
				$(this).after(output);
			} else {
				$('.cw-slide-instance').last().after(output);
			}
			var settings = {
				tinymce: true,
				media_buttons: true,
				quicktags: {
					buttons: 'strong,em,link,ul,ol,li',
					media_buttons: true,
				}
			};
			wp.editor.initialize('test_' + index,settings);
		});

	$(document).on('click', '.cw-attach-image', function( event ) {
		var frame, container;
		container = $(this).parent();
		// New media frame
		frame = wp.media({
			title: 'Select or Upload Media',
			button: {
				//text: 'Attach'
			},
			multiple: false,
			frame:    'post',
			state: 'insert',
		});

		frame.on( 'insert', function() {
			var state, selection, attachment, display, imgurl, preview;
			
			state = frame.state();
			selection = state.get('selection');
			attachment = selection.first();
			display = state.display(attachment).toJSON();
			attachment = attachment.toJSON();
			imgurl = attachment.sizes['medium'].url;

			container.find('.upload-image').val( attachment.id );
			container.find('.cw-attach-image').text('Change Image');

			preview = '<div class="cw-slideshow-img-preview"><div class="cw-preview-inner">';
			preview += '<span class="dashicons dashicons-no remove-slide-img"></span>';
			preview += '<img src="' + imgurl + '" alt="" /></div></div>';

			container.find('.cw-slideshow-img-preview').remove();

			container.find('.cw-attach-image').after(preview);

			frame.close();
		});

		frame.open();

		return false;

	 });

	$( document ).on( 'tinymce-editor-setup', function( event, editor ) {
		var el, defaultToolbar;
		el = $(editor.id);
		if( editor.id !== 'content') {
			defaultToolbar = editor.settings.toolbar1;
			editor.settings.height = '250';
			editor.settings.toolbar1 = 'formatselect,bold,italic,bullist,numlist,link,unlink,blockquote,alignleft,aligncenter,alignright';
		}
	});

	$(document).on('click', '.remove-slide-img', function(e) {
		var container;
		container = $(this).parent().parent().parent();
		container.find('.upload-image').val('');
		container.find('.cw-slideshow-img-preview').fadeOut(500, function() {
			$(this).remove();
		});
		container.find('.cw-attach-image').text('Attach Image');
	});

});

})( jQuery );
