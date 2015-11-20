jQuery(document).ready(function($) {
	"use strict";
	$( "#sortable" ).sortable();
	$(document).on("click", ".upload_image_button", function() {

		jQuery.data(document.body, 'prevElement', $(this).prev());

		window.send_to_editor = function(html) {
			var imgurl = jQuery('img',html).attr('src');
			var inputText = jQuery.data(document.body, 'prevElement');

			if(inputText !== undefined && inputText !== ''){
				inputText.val(imgurl);
			}

			tb_remove();
		};

		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	// Uploading files
	var file_frame;

	var images_container;
	var image_container;

	/**
	 * Delete image on click and update input.ids
	 */
	$(document).on("click", ".carousel-image", function() {

		/**
		 * This is the span wrapper of a single image
		 * @type {obj}
		 */
		image_container = $(this).parent();

		$(this).remove();

		var stringIDS = '';

		image_container.children().each(function(i, el) {

			stringIDS += $(el).children().data('id') + ',';

		});

		var input_ids = image_container.parent().find('.ids');
		input_ids.val( stringIDS );

	});

	jQuery('.upload_carousel_image_button').live('click', function( event ){

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		images_container = $(this).parent();

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();

			/**
			 * Image URL
			 * @type {string}
			 */
			var url = attachment.sizes.thumbnail.url ? attachment.sizes.thumbnail.url : attachment.url ;

			images_container.find('.carousel_images').append('<span class="carousel-image ui-state-default"><img src="' + url + '" width="150px" height="150px" data-id="' + attachment.id + '" /></span>');

			var input_ids = images_container.find('.ids');
			input_ids.val( input_ids.val() + attachment.id + ',' );

			// console.log( attachment );
			// console.log( attachment.sizes );
			// console.log( attachment.sizes.thumbnail );
			// console.log( attachment.sizes.thumbnail.url );

			// Do something with attachment.id and/or attachment.url here
		});

		// Finally, open the modal
		file_frame.open();
	});

	
	$(document).ready(function() {
		$( "#sortable" ).sortable({
			stop: function( event, ui ) {

			}
		});
		// $(function() {
		// 	$( "#sortable" ).sortable();
		// 	$( "#sortable" ).disableSelection();
		// });
	});
});