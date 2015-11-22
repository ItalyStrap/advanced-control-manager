jQuery(document).ready(function($) {
	"use strict";

	/**
	 * ItalyStrap Post Widget
	 */
	$('#widgets-right').on('click', '.upw-tab-item', function(event) {
		event.preventDefault();
		var widget = $(this).parents('.widget');
		console.log(widget);
		widget.find('.upw-tab-item').removeClass('active');
		$(this).addClass('active');
		widget.find('.upw-tab').addClass('upw-hide');
		widget.find('.' + $(this).data('toggle')).removeClass('upw-hide');
	});

	/**
	 * ItalyStrap vCard Local Business
	 */
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

	/**
	 * Upload media in ItalyStrap Bootstrap Media
	 */
	// http://mikejolley.com/2012/12/21/using-the-new-wordpress-3-5-media-uploader-in-plugins/
	// Uploading files
	var file_frame;
	var images_container;
	var image_container;
	var ul_container = $('#media_carousel_sortable ul');
	var input_ids = $('.ids');

	/**
	 * Function for sortable functionality
	 */
	function runSortable () {

		/**
		 * Sortable works only in a wrapper
		 * {@link https://wordpress.org/support/topic/jquery-ui-sortable-doesnt-seem-to-work-in-admin-in-widget}
		 * Use disableSelection() only for disabling text that is must not to be selectable
		 */
		$( "#media_carousel_sortable ul" ).sortable({
			cursor: 'move',
			stop: function(){
				_update_input_ids ( ul_container, input_ids );
			}
		});

	}
	runSortable();

	/**
	 * Run sortable every time is saved a widget configuration
	 * @link http://wordpress.stackexchange.com/questions/130084/executing-javascript-when-a-widget-is-added-in-the-backend
	 * @see  also https://core.trac.wordpress.org/ticket/19675 for widget events
	 */
	$( document ).ajaxStop( function() {
		runSortable();
	} );

	/**
	 * Delete image on click and update input.ids
	 */
	$(document).on("click", ".dashicons-no", function( event ) {

		event.preventDefault();

		/**
		 * This is the span wrapper of a single image
		 * @type {obj}
		 */
		image_container = $(this).parent().parent();

		/**
		 * Remove the image selected
		 */
		image_container.remove();

		/**
		 * Update the input ids
		 */
		_update_input_ids ( ul_container, input_ids );

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

			images_container.find('.carousel_images').append('<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i><img src="' + url + '" width="150px" height="150px" data-id="' + attachment.id + '" /></div></li>');

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
});

/**
 * Get the IDS from all images in the list
 * @param  {obj} container Object of the container
 * @return {string}        Return the ids like this 1,2,3,
 */
function _get_the_images_id ( container ) {

		var ids = '';

		container.children().each(function(i, el) {

			ids += jQuery(el).find('img').data('id') + ',';

		});

		return ids;
}

/**
 * Update the input ids
 * @param  {obj} ul_container Object of list container
 * @param  {obj} input_ids    Object of input ids
 */
function _update_input_ids ( ul_container, input_ids ) {

	input_ids.val( _get_the_images_id( ul_container ) );

}