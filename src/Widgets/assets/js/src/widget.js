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
});
