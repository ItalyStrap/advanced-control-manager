/**
 * italystrap Gallery Settings
 * @see JetPack
 * @see http://codex.wordpress.org/Javascript_Reference/wp.media [Non completa per jquery]
 */
(function($) {
	var media = wp.media;

	// Wrap the render() function to append controls.
	media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
		render: function() {
			var $el = this.$el;

			// console.log(this.$el);

			media.view.Settings.prototype.render.apply( this, arguments );

			/**
			 * Append the type template and update the settings.
			 */
			$el.append( media.template( 'italystrap-gallery-settings' ) );
			// media.gallery.defaults.type = 'default'; // lil hack that lets media know there's a type attribute.
			
			// console.log(gallery_fields);
			// {"ids":false,"type":"","orderby":"","name":"italystrap-bootstrap-carousel","width":"","height":"","indicators":"before-inner","control":"true","interval":0,"pause":"hover","titletag":"h4","image_title":"true","link":"","text":"true","wpautop":"true","containerclass":"","itemclass":"","captionclass":"","size":"full","responsive":false,"sizetablet":"large","sizephone":"medium"}
			// console.log(JSON.parse(gallery_fields));
			// console.log(gallery_fields.length);
			defaults_fields = JSON.parse( gallery_fields );
			// var key;
			// for (var key in gallery_fields) {
			//		console.log(key, gallery_fields[key]);
			//		media.gallery.defaults.key = gallery_fields[key];
			//		console.log(media.gallery.defaults.key);
			//		// this.update.apply( this, [key] );
			//	}
			// console.log(media.gallery.defaults);

			/**
			 * Faccio il merge dei field di default con quelli di WordPress
			 */
			// $.extend( media.gallery.defaults, defaults_fields );

			media.gallery.defaults.type = 'default'; // lil hack that lets media know there's a type attribute.
			media.gallery.defaults.orderby = '';
			media.gallery.defaults.name = 'italystrap-bootstrap-carousel';
			media.gallery.defaults.width = '';
			media.gallery.defaults.height = '';
			media.gallery.defaults.indicators = 'before-inner';
			media.gallery.defaults.control = 'true';
			media.gallery.defaults.interval = 0;
			media.gallery.defaults.pause = 'hover';
			media.gallery.defaults.titletag = 'h4';
			media.gallery.defaults.image_title = 'true';
			media.gallery.defaults.link = 'none';
			media.gallery.defaults.text = 'true';
			media.gallery.defaults.wpautop = 'true';
			media.gallery.defaults.containerclass = '';
			media.gallery.defaults.itemclass = '';
			media.gallery.defaults.captionclass = '';
			media.gallery.defaults.responsive = 'false';
			media.gallery.defaults.sizetablet = 'large';
			media.gallery.defaults.sizephone = 'medium';

			this.update.apply( this, ['type'] );
			this.update.apply( this, ['orderby'] );
			this.update.apply( this, ['name'] );
			this.update.apply( this, ['width'] );
			this.update.apply( this, ['height'] );
			this.update.apply( this, ['indicators'] );
			this.update.apply( this, ['control'] );
			this.update.apply( this, ['interval'] );
			this.update.apply( this, ['pause'] );
			this.update.apply( this, ['titletag'] );
			this.update.apply( this, ['image_title'] );
			this.update.apply( this, ['link'] );
			this.update.apply( this, ['text'] );
			this.update.apply( this, ['wpautop'] );
			this.update.apply( this, ['containerclass'] );
			this.update.apply( this, ['itemclass'] );
			this.update.apply( this, ['captionclass'] );
			this.update.apply( this, ['responsive'] );
			this.update.apply( this, ['sizetablet'] );
			this.update.apply( this, ['sizephone'] );

			// Hide the Columns setting for all types except Default
			$el.find( 'select[name=type]' ).on( 'change', function () {
				var columnSetting = $el.find( 'select[name=columns]' ).closest( 'label.setting' );
				var carouselOption = $el.find( '#italystrap-carousel-option' );

				if ( 'default' === $( this ).val() ) {
					columnSetting.show();
					carouselOption.hide();
				} else {
					columnSetting.hide();
					carouselOption.show();
				}
			} ).change();

			return this;
		}
	});
})(jQuery);