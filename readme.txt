=== ItalyStrap ===
Contributors: overclokk
Donate link: http://www.italystrap.it/
Tags: breadcrumbs, breadcrumb, seo, performance, schema.org, rich snippet, bootstrap, twitter bootstrap, css, carousel, slider, slideshow, responsive, photo, photos, media, gallery, 
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your web site more powerfull.

== Description ==

[ItalyStrap](http://www.italystrap.it/) will add powerful features to your WordPress site.

= Features included: =

* **Breadcrumbs.** Make your WordPress site SEO friendly with Breadcrumbs and Schema.org and get Google rich snippet in SERP, fully customizable for your purpose (ItalyStrap breadcrumbs class is 10 times faster than Yoast Breadcrumbs).
* **Carousel.** (Forked from Agnosia Bootstrap Carousel by AuSoft with my some improvements) Add Bootstrap carousel in `[gallery]` shortcode with attribute `type="carousel"` and more functionality, it works only if you have Bootstrap css and js or ItalyStrap template installed. It will not include the files for you, so if they are not present, the carousel will not work.

== Installation ==

1. Install ItalyStrap either via the WordPress.org plugin directory, or by uploading the files to the `/wp-content/plugins/` directory in your server.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activating ItalyStrap, read the documentation for any kind of customization.
6. That's it.  You're ready to go!

== Frequently Asked Questions ==

= How can I add breadcrumbs in my site? =

Add this code in your template files:

`<?php if ( class_exists('ItalyStrapBreadcrumbs') ) {
	
		new ItalyStrapBreadcrumbs();
	
	} ?>`

= What if haven't I got Bootstrap CSS? =

If you haven't Bootstrap CSS style for breadcrumbs don't worry about it, you have 2 options, firts option you have to develope your own style for breadcrumbs, second option you can add this css code in your css style theme:

`.breadcrumb{padding:8px 15px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb>li{display:inline-block}.breadcrumb>li+li:before{content:"/\00a0";padding:0 5px;color:#ccc}.breadcrumb>.active{color:#777}`

= How can I use Bootstrap Carousel? =

Add attribute `type="carousel"` at gallery shortcode, this will show Bootstrap Carousel based on the selected images and their titles and descriptions, otherwise it will show standard WordPress Gallery.

For more informations read the documentation

== Screenshots ==

1. Default breadcrumbs (With Bootstrap CSS)
2. Custom breadcrumbs (With Bootstrap CSS)
3. Breadcrumbs in page (With Bootstrap CSS)
4. Gallery in admin dashboard
5. Gallery with new Carousel functionality
6. Gallery shortcode with type="carousel" for Bootstrap Carousel
7. Example of Carousel in article page (740x370)


== Changelog ==

= 1.1.0 =
Release Date: February 20th, 2015

Dev time: 80h

* Renders extra controls for image dimension in the new media UI
* Add Bootstrap Carousel functionality forked from Agnosia Bootstrap Carousel by AuSoft
* Renders extra controls in the Gallery Settings section of the new media UI
* Add Mobile_Detect class from https://github.com/serbanghita/Mobile-Detect for responsive functions
* Add Schema.org in Bootstrap Carousel
* Fix some bugs


= 1.0.2 =
Release Date: January 8th, 2015

Dev time: 1h

* Fix documentation link in admin dashboard (Thanks to Stefano Tondi from G+)

= 1.0.1 =
Release Date: January 8th, 2015

Dev time: 1h

* Update Breadcrumbs documentation

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