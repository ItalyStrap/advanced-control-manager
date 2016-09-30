jQuery(document).ready(function($) {
	"use strict";

	/************************
	 * ItalyStrap Post Widget
	 ************************/
	$('#widgets-right').on('click', '.upw-tab-item', function(event) {
		event.preventDefault();
		var widget = $(this).parents('.widget');
		// console.log(widget);
		widget.find('.upw-tab-item').removeClass('active');
		$(this).addClass('active');
		widget.find('.upw-tab').addClass('upw-hide');
		widget.find('.' + $(this).data('toggle')).removeClass('upw-hide');
	});

	/**********************************
	 * ItalyStrap vCard Local Business (DEPRECATED)
	 **********************************/
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

	/*************************************
	 * ItalyStrap Bootstrap Media Carousel
	 *************************************/
	/**
	 * Upload media in ItalyStrap Bootstrap Media
	 * @link http://mikejolley.com/2012/12/21/using-the-new-wordpress-3-5-media-uploader-in-plugins/
	 * @link https://coderwall.com/p/vjxfzw/wordpress-advanced-media-upload-usage
	 */
	// Uploading files
	var file_frame;
	var images_container;
	var image_container;
	// var ul_container = $('.media_carousel_sortable ul');
	// var input_ids = $('.ids');

	$(document).on('hover', '.widget-content', function( event ) {

		event.preventDefault();

		var ul_container = $(this).find('.media_carousel_sortable ul');

		var input_ids = $(this).find('.ids');

		runSortable( ul_container, input_ids );

	});

	/**
	 * Run sortable every time is saved a widget configuration
	 * @link http://wordpress.stackexchange.com/questions/130084/executing-javascript-when-a-widget-is-added-in-the-backend
	 * @see  also https://core.trac.wordpress.org/ticket/19675 for widget events
	 */
	// $( document ).ajaxStop( function() {
		// runSortable();
	// } );

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

		var ul_container = image_container.closest('.media_carousel_sortable ul');

		var input_ids = image_container.closest('.widget-content').find('.ids');

		/**
		 * Remove the image selected
		 */
		image_container.remove();

		/**
		 * Update the input ids
		 */
		_update_input_ids( ul_container, input_ids );

	});

	$(document).on('click', '.upload_carousel_image_button', function( event ){

		event.preventDefault();

		var images_container = $(this).offsetParent();

		// If the media frame already exists, reopen it.
		// if ( file_frame ) {
		//		console.log(file_frame);
		//		file_frame.open();
		//		return;
		// }

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			// frame: 'post',
			// frame: 'image',
			// state: 'gallery',
			library: {
					type: 'image'
			},
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: true  // Set to true to allow multiple files to be selected
		});
		// console.log(jQuery( this ).data());
		// console.log(jQuery( this ).data( 'uploader_button_text' ));

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();
// console.log(attachment);
// console.log(attachment.uploadedTo);
			/**
			 * Image URL
			 * @type {string}
			 */
			var url = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url ;

			var ul_container = null;
			var input_ids = null;

			ul_container = images_container.find('.carousel_images');
			ul_container.append('<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i><img src="' + url + '" width="150px" height="150px" data-id="' + attachment.id + '" /></div></li>');

			input_ids = images_container.find('.ids');
			// console.log(input_ids.val());

			if ( '' === input_ids.val() ) {
				input_ids.val( attachment.id );
			} else{
				input_ids.val( input_ids.val() + ',' + attachment.id );
			}

			// console.log( attachment );
			// console.log( attachment.sizes );
			// console.log( attachment.sizes.thumbnail );
			// console.log( attachment.sizes.thumbnail.url );

			// Do something with attachment.id and/or attachment.url here
			ul_container = null;
			input_ids = null;
		});

		// Finally, open the modal
		file_frame.open();
	});

	/***************************************
	 * ItalyStrap fields media for one image
	 ***************************************/

	$(document).on('click', '.upload_single_image_button', function( event ){

		event.preventDefault();

		var images_container = $(this).offsetParent();

		// If the media frame already exists, reopen it.
		// if ( file_frame ) {
		//		console.log(file_frame);
		//		file_frame.open();
		//		return;
		// }

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			// frame: 'post',
			// frame: 'image',
			// state: 'gallery',
			library: {
					type: 'image'
			},
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});
		// console.log(jQuery( this ).data());
		// console.log(jQuery( this ).data( 'uploader_button_text' ));

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();
// console.log(attachment);
// console.log(attachment.uploadedTo);
			/**
			 * Image URL
			 * @type {string}
			 */
			var url = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url ;

			var ul_container = null;
			var input_ids = null;

			ul_container = images_container.find('.carousel_images');
			ul_container.empty();
			ul_container.append('<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i><img src="' + url + '" width="150px" height="150px" data-id="' + attachment.id + '" /></div></li>');

			input_ids = images_container.find('.ids');

			// console.log(attachment.id);

			input_ids.val( attachment.id );

			console.log( input_ids.val() );

			// if ( '' === input_ids.val() ) {
			// 	input_ids.val( attachment.id );
			// }
			// else{
			// 	input_ids.val( input_ids.val() + ',' + attachment.id );
			// }

			// console.log( attachment );
			// console.log( attachment.sizes );
			// console.log( attachment.sizes.thumbnail );
			// console.log( attachment.sizes.thumbnail.url );

			// Do something with attachment.id and/or attachment.url here
			ul_container = null;
			input_ids = null;
		});

		// Finally, open the modal
		file_frame.open();
	});






	$(document).on('click', '.upload_carousel_single_image_button', function( event ){

		event.preventDefault();

		var images_container = $(this).offsetParent();

		// If the media frame already exists, reopen it.
		// if ( file_frame ) {
		//		console.log(file_frame);
		//		file_frame.open();
		//		return;
		// }

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'post',
			state: 'gallery',
			library: {
					type: 'image'
			},
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: true  // Set to true to allow multiple files to be selected
		});
		// console.log(jQuery( this ).data());
		// console.log(jQuery( this ).data( 'uploader_button_text' ));

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();
console.log(attachment);
console.log(attachment.uploadedTo);
			/**
			 * Image URL
			 * @type {string}
			 */
			var url = attachment.sizes.thumbnail.url ? attachment.sizes.thumbnail.url : attachment.url ;

			var ul_container = null;
			var input_ids = null;

			ul_container = images_container.find('.carousel_images');
			ul_container.append('<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i><img src="' + url + '" width="150px" height="150px" data-id="' + attachment.id + '" /></div></li>');

			input_ids = images_container.find('.ids');
			// console.log(input_ids.val());

			if ( '' === input_ids.val() ) {
				input_ids.val( attachment.id );
			} else{
				input_ids.val( input_ids.val() + ',' + attachment.id );
			}

			// console.log( attachment );
			// console.log( attachment.sizes );
			// console.log( attachment.sizes.thumbnail );
			// console.log( attachment.sizes.thumbnail.url );

			// Do something with attachment.id and/or attachment.url here
			ul_container = null;
			input_ids = null;
		});

		// Finally, open the modal
		file_frame.open();
	});
});


/**
 * Function for sortable functionality
 */
function runSortable ( ul_container, input_ids ) {

	/**
	 * Sortable works only in a wrapper
	 * {@link https://wordpress.org/support/topic/jquery-ui-sortable-doesnt-seem-to-work-in-admin-in-widget}
	 * Use disableSelection() only for disabling text that is must not to be selectable
	 */
	ul_container.sortable({
		cursor: 'move',
		stop: function(){
			_update_input_ids ( ul_container, input_ids );
		}
	});
}

/**
 * Get the IDS from all images in the list
 * @param  {obj} container Object of the container
 * @return {string}        Return the ids like this 1,2,3,
 */
function _get_the_images_id ( container ) {

		var ids = '';

		container.children().each(function(i, el) {

			if ( 0 === i ) {
				ids += jQuery(el).find('img').data('id');
			} else{
				ids += ',' + jQuery(el).find('img').data('id');
			}
		});

		return ids;
}

/**
 * Update the input ids
 * @param  {obj} ul_container Object of list container
 * @param  {obj} input_ids    Object of input ids
 */
function _update_input_ids ( container, input_ids ) {

	input_ids.val( _get_the_images_id( container ) );

}