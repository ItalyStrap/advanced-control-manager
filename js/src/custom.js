jQuery.noConflict()(function($){
	"use strict";
	$(document).ready(function() {
		$('.link').click(function() {
				if ( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
					var $target = $(this.hash);
					$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
					if ($target.length) {
						var targetOffset = $target.offset().top;
						$('html,body').animate({scrollTop: targetOffset}, 500);
						return false;}
				}
			});//http://css-tricks.com/snippets/jquery/smooth-scrolling/ http://www.sycha.com/jquery-smooth-scrolling-internal-anchor-links http://www.electrictoolbox.com/jquery-scroll-top/
		// $('#tooltip').tooltip();

		// LazyLoad ShareThis on mouseover
		// http://codepen.io/svinkle/pen/sgjtC
		// http://codepen.io/svinkle/details/nluvs
		// http://blog.akademy.co.uk/2013/05/controlling-javascript-with-lazyload-a-sharethis-example/
		$('#share').on('mouseover', function () {

			// https://github.com/rgrove/lazyload
			LazyLoad.js('http://w.sharethis.com/button/buttons.js', function () {
				// ShareThis Options
				stLight.options({publisher: "27d14cdb-c536-4124-8e5f-c55daf92d1d1", doNotHash: true, doNotCopy: false, hashAddressBar: true});        
			});  

			// Remove background
			$(this).css('background', 'none');      

			// Remove event listener
			$(this).unbind('mouseover');

		});

	});
});