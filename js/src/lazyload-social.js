// Add a script element as a child of the body
		function socialWidgetsLoad() {

			//Facebook
			// (function(d, s, id) {
			// var js, fjs = d.getElementsByTagName(s)[0];
			// if (d.getElementById(id)) return;
			// js = d.createElement(s); js.id = id;
			// js.src = "//connect.facebook.net/it_IT/all.js#xfbml=1";
			// fjs.parentNode.insertBefore(js, fjs);
			// }(document, 'script', 'facebook-jssdk'));

			//Twitter
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

			//Google+
			(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
		}

		function inViewport(el) {
			var top = el.offsetTop;
			var left = el.offsetLeft;
			var width = el.offsetWidth;
			var height = el.offsetHeight;

			while(el.offsetParent) {
				el = el.offsetParent;
				top += el.offsetTop;
				left += el.offsetLeft;
			}

			return (
				top < (window.pageYOffset + window.innerHeight) &&
				left < (window.pageXOffset + window.innerWidth) &&
				(top + height) > window.pageYOffset &&
				(left + width) > window.pageXOffset
			);
		}

		window.onscroll = function (e) {
			if(inViewport(document.getElementById("socialwidgets"))) {
				socialWidgetsLoad();
				window.onscroll = null;
			}
		}