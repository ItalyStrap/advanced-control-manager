=== ItalyStrap ===
Contributors: overclokk
Donate link: http://www.italystrap.it/
Tags: italystrap, bandwidth, bing, breadcrumbs, breadcrumb, bootstrap, carousel, css, front-end optimization, gallery, google, images, lazy load, lazy loading, local business seo, local business, local seo, media, microdata, optimize, performance, photo, responsive, responsive design, rich snippet, schema, schema.org, seo, slider, slideshow, twitter bootstrap, widget, widgets, yahoo
Requires at least: 4.0
Tested up to: 4.5
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your web site more powerfull

Bootstrap, Performance and Schema.org

== Description ==

[ItalyStrap](http://www.italystrap.it/) will add powerful features to your WordPress site.
[ItalyStrap](https://github.com/overclokk/italystrap-extended) Also on github.

May the force be with you!

= Features included: =

* **Breadcrumbs** Make your WordPress site SEO friendly with Breadcrumbs and Schema.org and get Google rich snippet in SERP, fully customizable for your purpose (ItalyStrap breadcrumbs class is 10 times faster than Yoast Breadcrumbs).

* **Carousel** Forked from [Agnosia Bootstrap Carousel by AuSoft](https://wordpress.org/plugins/agnosia-bootstrap-carousel/) with my some improvements Add Bootstrap carousel in `[gallery]` shortcode with attribute `type="carousel"` and more functionality, it works only if you have Bootstrap css and js or ItalyStrap template installed. It will not include the files for you, so if they are not present, the carousel will not work.

* **LazyLoad** Lazy load images to improve page load times and server bandwidth. Images are loaded only when visible to the user. This functionality is forked from [lazy-load](https://wordpress.org/plugins/lazy-load/) plugin with my improvements. It work also with my Bootstrap Carousel.

* **Schema.org Local Business** A simple Widget to add [Schema.org Local business](http://schema.org/LocalBusiness) in your widgetized themes (sidebar, footer, ...)
This widget will add microdata information about your  site for SEO purposes.
This widget was present in my [ItalyStrap starter theme](http://www.italystrap.it/) forked from [Roots theme](https://roots.io/) with some improvements of mine, now I've done more improvements thank to [this tool](https://www.searchcommander.com/seo-tools/structured-data-builder/) shared from [Luca Bove](https://plus.google.com/+LucaBove/posts/iM4aTMgzWAu) on googleplus

== Installation ==

1. Install ItalyStrap either via the WordPress.org plugin directory, or by uploading the files to the `/wp-content/plugins/` directory in your server.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Activate your desired functionality from ItalyStrap option page.
4. After activating ItalyStrap, read the documentation for any kind of customization.
5. That's it.  You're ready to go!

== Frequently Asked Questions ==

= How can I add breadcrumbs in my site? =

Add this code in your template files:

`
<?php if ( class_exists('ItalyStrapBreadcrumbs') ) {
		new ItalyStrapBreadcrumbs();
	} ?>
`

= What if haven't I got Bootstrap CSS? =

If you haven't Bootstrap CSS style for breadcrumbs don't worry about it, you have 2 options, firts option you have to develope your own style for breadcrumbs, second option you can add this css code in your css style theme:

`.breadcrumb{padding:8px 15px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb>li{display:inline-block}.breadcrumb>li+li:before{content:"/\00a0";padding:0 5px;color:#ccc}.breadcrumb>.active{color:#777}`

= How can I use Bootstrap Carousel? =

Add attribute `type="carousel"` at gallery shortcode, this will show Bootstrap Carousel based on the selected images and their titles and descriptions, otherwise it will show standard WordPress Gallery.

= How to activate Lazy Load for images =

For activate Lazy Load there is new page "Option" in ItalyStrap panel, in that page there is a checkbox, check on LazyLoad and let the magic begin :-P

= How do I change the placeholder image in Lazy Load functionality =

`
add_filter( 'ItalyStrapLazyload_placeholder_image', 'my_custom_lazyload_placeholder_image' );
function my_custom_lazyload_placeholder_image( $image ) {
	return 'http://url/to/image';
}
`

= How do I lazy load other images in my theme? =

You can use the italystrap_get_apply_lazyload helper function:

`
if ( function_exists( 'italystrap_get_apply_lazyload' ) )
	$content = italystrap_get_apply_lazyload( $content );
`

Or, you can add an attribute called "data-src" with the source of the image URL and set the actual image URL to a transparent 1x1 pixel.

You can also use italystrap_apply_lazyload helper function for print content:

`
if ( function_exists( 'italystrap_apply_lazyload' ) )
	italystrap_apply_lazyload( $content );
`

Otherwise you can also use output buffering, though this isn't recommended:

`
if ( function_exists( 'italystrap_get_apply_lazyload' ) )
	ob_start( 'italystrap_get_apply_lazyload' );
`

This will lazy load <em>all</em> your images.

= This plugin is using JavaScript. What about visitors without JS? =

No worries. They get the original element in a noscript element. No Lazy Loading for them, though.

= I'm using a CDN. Will this plugin interfere? =

Lazy loading works just fine. The images will still load from your CDN. If you have any problem please open a ticket :-)

= How can I verify that the plugin is working? =

Check your HTML source or see the magic at work in Web Inspector, FireBug or similar.

= I'm using my custom Bootstrap Carousel, why doesn't the second image appear? =

Put the code below in your file js and type your Bootstrap Carousell ID in place of "#YOURCAROUSELID"

`var cHeight = 0;$("#YOURCAROUSELID").on("slide.bs.carousel", function(){var $nextImage = $(".active.item", this).next(".item").find("img");var src = $nextImage.data("src");if (typeof src !== "undefined" && src !== ""){$nextImage.attr("src", src);$nextImage.data("src", "");}});`

= I'm using an external carousel, will Lazy Load work with it? = 

I tried only with ItalyStrap Bootstrap Carousel, please send me any feedback if have any issue with other carousel, however I can't guarantee to solve the issue.

= How can I use Local Business widget =

Simply activate functionality from ItalyStrap option page, add ItalyStrap vCard Local Business in your widgetozed area and then fill in the fields input

For more informations read the documentation

If you have any problem please open a ticket :-)

== Screenshots ==

1. Default breadcrumbs (With Bootstrap CSS)
2. Custom breadcrumbs (With Bootstrap CSS)
3. Breadcrumbs in page (With Bootstrap CSS)
4. Gallery in admin dashboard
5. Gallery with new Carousel functionality
6. Gallery shortcode with type="carousel" for Bootstrap Carousel
7. Example of Carousel in article page (740x370)
8. Example of Lazy Loading for image
9. ItalyStrap Local Business widget in admin panel
10. ItalyStrap Local Business widget in widgetized themes (sidebar, footer, ...)
11. ItalyStrap Local Business widget HTML markup example


== Changelog ==

= 1.3.4 =
Release Date: September 19th, 2015

Dev time: 1h

* Fix posts page (not home page) visualization
Promemoria prima di fare il deploy
	Verificare questo ( is_home() && is_front_page() ) || is_front_page() nei breadcrumbs
	Verificare home e post statici o di default
* Added new widget for post display

= 1.3.3 =
Release Date: September 19th, 2015

Dev time: 8h

* Added logo upload in vCard widget
* Fixed some issue

= 1.3.2 =
Release Date: August 14th, 2015

Dev time: 20h

* Added a simple HTML sitemaps class for theme (beta version)
* [Fixed Deprecating PHP4 style constructors in WordPress 4.3](https://make.wordpress.org/core/2015/07/02/deprecating-php4-style-constructors-in-wordpress-4-3/)

= 1.3.1 =
Release Date: June 13th, 2015

Dev time: 1h

* Added taxonomy support for breadcrumbs

= 1.3.0 =
Release Date: March 4th, 2015

Dev time: 30h

* Added Local Business widget for widgetized themes

= 1.2.1 =
Release Date: March 1st, 2015

Dev time: 5h

* Added new function for LazyLoad
* Fixed some issue

= 1.2.0 =
Release Date: February 27th, 2015

Dev time: 40h

* Fixed some bug and issue
* Added static class for appending inline script and print it in footer after wp_print_footer_scripts hook (for performance purpose)
* Added static class for appending inline css and print it in header after wp_head hook  (for performance purpose)
* Added class for Lazy Load image functionality

= 1.1.0 =
Release Date: February 20th, 2015

Dev time: 80h

* Renders extra controls for image dimension in the new media UI
* Added Bootstrap Carousel functionality forked from Agnosia Bootstrap Carousel by AuSoft
* Renders extra controls in the Gallery Settings section of the new media UI
* Added Mobile_Detect class from https://github.com/serbanghita/Mobile-Detect for responsive functions
* Added Schema.org in Bootstrap Carousel
* Fixed some bugs


= 1.0.2 =
Release Date: January 8th, 2015

Dev time: 1h

* Fixed documentation link in admin dashboard (Thanks to Stefano Tondi from G+)

= 1.0.1 =
Release Date: January 8th, 2015

Dev time: 1h

* Updated Breadcrumbs documentation

= 1.0.0 =
Release Date: January 7th, 2015

Dev time: 100h

* First release

== Upgrade Notice ==

= 1.0.2 =
This version fixes a documentation link in admin dashboard.  Upgrade as soon as possible

= 1.0.1 =
This version updates Breadcrumbs documentation.  Upgrade as soon as possible

= 1.0.0 =
First release.

== Translations ==
 
* English: default, always included.
* Italian: Italiano, sempre incluso.
* German: Deutsch - immer dabei!
* French: Français, toujours inclus.
 
*Note:* This plugins is localized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/).
 
== Additional Info ==
**Idea Behind / Philosophy:** A plugin for improve and add some powerful improvement to your site. I'll try to add more feautures if it makes some sense. So stay tuned :).
 
== Credits ==

* [Agnosia Bootstrap Carousel](https://wordpress.org/plugins/agnosia-bootstrap-carousel/)
* [Lazy Load](https://wordpress.org/plugins/lazy-load/)