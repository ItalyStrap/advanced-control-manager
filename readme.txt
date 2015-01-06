=== ItalyStrap ===
Contributors: overclokk
Donate link: http://www.italystrap.it/
Tags: breadcrumbs, breadcrumb, seo, performance, schema.org, rich snippet, bootstrap, twitter bootstrap, css
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your web site faster.

== Description ==

[ItalyStrap](http://www.italystrap.it/) will add powerful features to your WordPRess site.

**Features include:**

* **Breadcrumbs.** Make your WordPress site SEO friendly with Breadcrumbs and Schema.org and get Google rich snippet in SERP, fully customizable for your purpose (ItalyStrap breadcrumbs class is 10 times faster than Yoast Breadcrumbs).

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
For more informations read the documentation

== Screenshots ==

1. Default breadcrumbs
2. Custom breadcrumbs
3. Breadcrumbs in page

== Changelog ==

= 1.0.0 =
Release Date: December 30th, 2014
Dev time: 100h
* First release

== Upgrade Notice ==

= 1.0.0 =
First release.