# Advanced Control Manager for WordPress by ItalyStrap #
**Contributors:** [Enea Overclokk](https://profiles.wordpress.org/overclokk/)  
**Donate link:** https://italystrap.com/  
**Tags:** custom post widget, custom post shortcode, image & video lazy loading, twitter bootstrap carousel for gallery, popular posts  
**Requires at least:** 6.0  
**Tested up to:** 6.1  
**Stable tag:** 2.16.0  
**Requires PHP:** 7.4  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Make your web site more powerful with ACM - Advanced Control Manager

## Description ##

You MUST always do a backup before install it and always test it in a dev enviroment.

Make sure you have PHP 7.2 >=, better if you have PHP >= 7.4 (PHP 7.x is awesome ;-) ).

If you have any trouble [open a ticket](http://wordpress.org/support/plugin/advanced-control-manager).

If you have any feature requests or code issue [open a ticket on github](https://github.com/ItalyStrap/advanced-control-manager/issues).

[ItalyStrap](https://italystrap.com/) will add powerful features to your WordPress site.

[Advanced Control Manager](https://github.com/ItalyStrap/advanced-control-manager) is also on github.

### Purpose ###

I developed this plugin with many features because I need them in all sites created for my clients, it also extends the [ItalyStrap Theme Frameworks](https://github.com/ItalyStrap/italystrap) functionality but it works fine with all WordPress themes.

Think about Advanced Control Manager like a Jetpack with many functionality that make your site more powerful and extensible, for example: Lazyload for image with srcset support, widget and shortcode for Twitter Bootstrap Carousel (with lazyload support), widget for displaying lists of posts type with a lot of options like popular posts, last posts, related posts and so on, there are a lot of functionality, see above for the full list of featured.

This plugin is fully developed in OOP. It utilizes [DI Container](https://github.com/rdlowrey/auryn), Dependency Injection, Polymorphism, Inheritance, etc.
If you are a developer you can extend it with his API (Dev docs coming soon).

### Skills required ###

Some functionalities need to be personalized with lines of code, for example if you use the new widget for displaying posts you have to add your own CSS style to make it look like your site (you can add it in ACM > settings > Style > Custom CSS or in your theme style.css), I'm working on building some basic code snippets and documentation, but I need a lot of time to do it, please be patient with me :-).

### Advanced Control Manager will always be free ###

This is my thanks for what WordPress has given to me.

### Get involved ###

If you want to contribute [click here](https://github.com/ItalyStrap/advanced-control-manager) do a fork and do a pull request to the Dev branch :-)

### Want to try the beta functionality? ###
To do so you can add `define( 'ITALYSTRAP_BETA', true );` to your `wp-config.php` file, **REMEMBER** that you have to do this in a development enviroment, **do not do this in a production site** and do always a backup.

[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)

### Features included: ###

## General feature ##

* Show Post Type IDs in edit screen
* Show Post Type thumbnails in edit screen
* Remove Emojis
* Social Sharing Button {Only for Beta tester}
* Web font loading (Google fonts and font lazy loading) {Only for Beta tester}
* ItalyStrap theme hook (Only for ItalyStrap Theme Framework)
* ItalyStrap theme hooked callable (Only for ItalyStrap Theme Framework)

## Widget ##

* Widget Areas custom {Only for Beta tester and for ItalyStrap Theme Framework}
* Widget Visibility
* HTML attributes for widget (You can add a css ID and class to widget)
* HTML in Widget Title
* Remove widget title
* Posts Widget (Display loops of post with many options)
* Grouped Posts Widget (Posts grouped by taxonomy) {Only for Beta tester}
* Carousel Widget (Only for Twitter Bootstrap Carousel with many options. It will not include the files for you, so if they are not present, the carousel will not work.)
* vCard Widget (Display vCard with Schema.org)
* Image Widget {{BETA VERSION}}
* Breadcrumbs Widget with Schema.org markup {{BETA VERSION}}
* Facebook Page Widget {{BETA VERSION}}
* TinyMCE Editor Widget {{BETA VERSION}}
* Monster Widget by ItalyStrap {{Only for debug}}

## Shortcodes ##

* Do ShortCode in Widget Text
* Shortcode for Media Carousel (Only for Twitter Bootstrap Carousel with many options. It will not include the files for you, so if they are not present, the carousel will not work.)
* Posts Shortcode (Display loops of post with many options)

## Style ##

* Activate Custom CSS (This will give you the possibility to add custom css style and css Body/Post class for entire site and for single post type)
* Custom CSS (Textarea for custom css)
* Body Class (css class)
* Post Class (css class)

## Analytics ##

* Activate Google Analytics (Insert the universal google analytics code)
* Add Google Analytics ID
* Anonymize Google Analytics IP
* Google Analytics position

## Content ##

* Activate excerpt more mods
* Read more link text
* Read more CSS class
* Auto-generated Excerpt length
* Excerpt ends with punctuation
* Read more position

## Media ##

* Jpeg quality
* Lazy Loading of images
* Custom Placeholder for image lazyloading
* Lazy Loading of embedded video

* **[Breadcrumbs](http://docs.italystrap.com/breadcrumbs-wordpress-theme/)** Make your WordPress site SEO friendly with Breadcrumbs and Schema.org and get Google rich snippet in SERP, fully customizable for your purpose (ItalyStrap breadcrumbs class is 10 times faster than Yoast Breadcrumbs ;-) ).

* **[LazyLoad](http://docs.italystrap.com/wordpress-image-lazy-load-with-italystrap/)** Lazy load images to improve page load times and server bandwidth. Images are loaded only when visible to the user. This functionality is forked from [lazy-load](https://wordpress.org/plugins/lazy-load/) plugin with my improvements. It also work with ItalyStrap Bootstrap Carousel, srcset and sizes.

* **[Schema.org Local Business](http://docs.italystrap.com/widget-italystrap-vcard-local-business/)** A simple Widget to add [Schema.org Local business](http://schema.org/LocalBusiness) in your widgetized themes (sidebar, footer, ...)
This widget will add microdata information about your  site for SEO purposes.
This widget was present in my [ItalyStrap theme framework](https://italystrap.com/) forked from [Roots theme](https://roots.io/) with some improvements of mine, now I've done more improvements thank to [this tool](https://www.searchcommander.com/seo-tools/structured-data-builder/) shared from [Luca Bove](https://plus.google.com/+LucaBove/posts/iM4aTMgzWAu) on googleplus.

* **Posts Widget** This adds a widget for displaying posts with a lot of options, you can create a related posts, posts from any taxonomies, pages, and mmore, in a future release you have also the power to change the template.

* **Custom Style** Now you can add your custom style in single page or in the entire site, you can also add custom css class and ID attribute in single page or in the site.

* **Google Analytics** You can add Google Analytics snippet to your theme, this is a simple functionality that add the GA snippet to `wp_footer` hook

* **And many more**

## Installation ##

### Upload ###

1. Download the latest [tagged archive](https://github.com/ItalyStrap/advanced-control-manager/releases) (choose the "zip" option) or the [latest from WP.org](https://wordpress.org/plugins/advanced-control-manager/).
2. Go to the Plugins -> Add New screen and click the Upload tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click Activate.

### Manual ###

1. Download the latest [tagged archive](https://github.com/ItalyStrap/advanced-control-manager/releases) (choose the "zip" option) or the [latest from WP.org](https://wordpress.org/plugins/advanced-control-manager/).
2. Unzip the archive.
3. Copy the folder to your /wp-content/plugins/ directory.
4. Go to the Plugins screen and click Activate.

### WP.org ###

1. Install Advanced Control Manager either via the WordPress.org plugin directory, or by uploading the files to the `/wp-content/plugins/` directory in your server.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Activate your desired functionality from ACM option page.
4. After activating Advanced Control Manager, read the documentation for any kind of customization.
5. That's it.  You're ready to go!

### Git ###

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone https://github.com/ItalyStrap/advanced-control-manager.git advanced-control-manager`

`cd advanced-control-manager`

`composer install --no-dev`

Then go to your Plugins screen and click Activate.

## Frequently Asked Questions ##

### How can I add breadcrumbs in my site? ###

Add this code in your template files:

`<?php do_action( 'do_breadcrumbs', [] ); ?>`

### What if haven't I got Bootstrap CSS? ###

If you haven't Bootstrap CSS style for breadcrumbs don't worry about it, you have 2 options, firts option you have to develope your own style for breadcrumbs, second option you can add this css code in your css style theme:

`.breadcrumb{padding:8px 15px;margin-bottom:20px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb>li{display:inline-block}.breadcrumb>li+li:before{content:"/\00a0";padding:0 5px;color:#ccc}.breadcrumb>.active{color:#777}`

### How can I use Bootstrap Carousel? ###

Add attribute `type="carousel"` at gallery shortcode, this will show Bootstrap Carousel based on the selected images and their titles and descriptions, otherwise it will show standard WordPress Gallery.

### How to activate Lazy Load for images ###

For activate Lazy Load there is new page "Option" in ACM panel, in that page there is a checkbox, check on LazyLoad and let the magic begin :-P

### How do I change the placeholder image in Lazy Load functionality ###

```php
add_filter( 'italystrap_lazy_load_placeholder_image', 'my_custom_lazyload_placeholder_image' );
function my_custom_lazyload_placeholder_image( $image ) {
	return 'http://url/to/image';
}
```

### How do I lazy load other images in my theme || plugin? ###

You can use a dedicated event name to filter your content: 'italystrap_lazyload_images_in_this_content'

```php
$your_content_with_images = '<img src="screanshot.png" >';
\apply_filters('italystrap_lazyload_images_in_this_content', $your_content_with_images);
```

### Lazy load uses JavaScript. What about visitors without JS active? ###

No worries. They get the original element in a noscript element. No Lazy Loading for them, though.

### I'm using a CDN. Will this plugin interfere? ###

Lazy loading works just fine. The images will still load from your CDN. If you have any problem please open a ticket :-)

### How can I verify that the plugin is working? ###

Check your HTML source or see the magic at work in Web Inspector, FireBug or similar.

### I'm using an external carousel, will Lazy Load work with it? ###

I tried only with the Bootstrap Carousel integrated, please send me any feedback if have any issue with other carousel, however I can't guarantee to solve the issue.

### Is it compatible with WebP images? ###

Yes, it is compatible with WebP images but you also have to install and activate [EWWW IO plugin](https://wordpress.org/plugins/ewww-image-optimizer/)
to leverage lazy loading for WebP images.
Once you have activate the EWWW IO go to WebP settings and check 'JPG/PNG to WebP' for generating WebP version of your images
'JS WebP Rewriting' option is needed only if your server do no load WebP images correctly
See the [plugin documentation](https://docs.ewww.io/article/16-ewww-io-and-webp-images)

### How can I use Local Business widget ###

Simply activate functionality from Advanced Control Manager option page, add ItalyStrap vCard Local Business in your widgetozed area and then fill in the fields input

For more information read the documentation

If you have any problem please open a ticket :-)

## Screenshots ##

1. Default breadcrumbs (With Bootstrap CSS)
2. Custom breadcrumbs (With Bootstrap CSS)
3. Breadcrumbs in page (With Bootstrap CSS)
4. Gallery in admin dashboard
5. Gallery with new Carousel functionality
6. Gallery shortcode with type="carousel" for Bootstrap Carousel
7. Example of Carousel in article page (740x370)
8. Example of Lazy Loading for image
9. Advanced Control Manager Local Business widget in admin panel
10. Advanced Control Manager Local Business widget in widgetized themes (sidebar, footer, ...)
11. Advanced Control Manager Local Business widget HTML markup example
12. Advanced Control Manager admin settings
13. Advanced Control Manager admin settings
14. Advanced Control Manager admin settings
15. Advanced Control Manager admin settings
16. Advanced Control Manager admin settings

## Changelog ##

### 2.16.0 ###
Release Date: Jul 28th, 2023

Dev time: 24h

### 2.15.1 ###
Release Date: Oct 22nd, 2020

Dev time: 6h

* Fixed bug in Post class for generated post_type string

### 2.15.0 ###
Release Date: Oct 2nd, 2020

Dev time: 30h

* Changes some class names for PSR-2
* Renamed Config_Interface dependency to ConfigInterface {PSR-2}
* Bools functions wrapped with function_exists
* Moved some classes to new namespace

### 2.14.4 ###
Release Date: Feb 27th, 2020

Dev time: 10h

* Refactoring of the Image Lazy Load
* Added support for EWWW IO in Image Lazy Load
* Added custom filter for Lazy Load your custom images

### 2.14.3 ###
Release Date: Feb 20th, 2020

Dev time: 2h

* Reverted CMB2 to 2.6.0

### 2.14.2 ###
Release Date: Feb 20th, 2020

Dev time: 4h

* Fixed issue with int key used in Config

### 2.14.1 ###
Release Date: Feb 19th, 2020

Dev time: 4h

* Fixed BC breaks for older ItalyStrap theme framework

### 2.14.0 ###
Release Date: Feb 19th, 2020

Dev time: 100h

* Some other minor fix
* Refactoring for the image lazy load
* Refactoring for the setting API
* Changed the Fields API

### 2.13.0 ###
Release Date: Mar 4th, 2019

Dev time: 10h

* New Visual editor in terms page under option
* Fixed Google font fallback json
* Some other minor fix

### 2.12.0 ###
Release Date: Feb 6th, 2019

Dev time: 30h

* New API for EDD to update ItalyStrap Framework

### 2.11.3 ###
Release Date: Feb 1st, 2019

Dev time: 3h

* Fixed the Debug API

### 2.11.2 ###
Release Date: Jan 30th, 2019

Dev time: 3h

* Updated the Breadcrumbs API

### 2.11.1 ###
Release Date: Jan 30th, 2019

Dev time: 1h

* Fixed php syntax error
* Fixed undefined index on Term in CPT

### 2.11.0 ###
Release Date: Jan 28th, 2019

Dev time: 100h

* Requires PHP: 5.4
* Updated composer dependency
* Updated CMB2
* Updated lang files
* New API Class for Breadcrumbs loaded from packagist
* New API for Blocks in beta version
* Moved Fields API to packagist
* Moved Config API to packagist
* Moved Debug API to packagist
* Added option for social share button post type selection (only for BETA Version)
* Added new filter 'italystrap_injector' for passing Injector instance
* Fixed issue with texteditor_code and Codemirror
* Bump minimum requirement to WP 4.9
* Removed unused directory from composer


### 2.10.0 ###
Release Date: May 31st, 2018

Dev time: 100h

* New API Class for I18N strings
* New API Class for rendering views files
* New Translator class
* Fix for A non numeric valus encountered src\Query\Posts.php
* Fix excerpt in Carousel
* Better file structure for functions
* Improved hook on injector autoload
* Improved Post_Title shortcode
* Improved Settings API
* Improved Config API
* Improved the Loader API
* Updated the bootstrap files
* Updated the config file
* Updated the options file
* Excluded GA and TM to preview and customizer
* Only for alpha Some Block improvements
* Bumps up minimum requirements
* Security: Delete unsecure files and folder

### 2.9.1 ###
Release Date: October 9th, 2017

Dev time: 30min

* Fixed SyntaxError: missing } after function body for Image Lazy Load

### 2.9.0 ###
Release Date: October 9th, 2017

Dev time: 30h

* Removed other notice on settings page of my plugin
* Added div wrapper for the view of Video Lazy Load
* Improvements for the Bootstrap Carousell API
* Refactoring of the Asset Inline and Minify API
* Refactoring of the Video and Image Lazy Load API
* Fixed PHP notice if the gallery shortcode is empty

### 2.8.0 ###
Release Date: October 1st, 2017

Dev time: 400h

* New Posts Shortcode in beta version (the widget version)
* New Blocks in Alpha version for Gutenberg
* New Shortcode in beta version fo Post_Title
* New Shortcode in beta version fo Button
* New filter for the read_more_link_text
* New Google Tag Manager in beta version for the ItalyStrap theme framework
* New CMB2 adapter OOP PHP Class
* New Shortcake UI integration
* Changes configuration for widget and shortcode to default for Shortcake
* Fix for Google fonts
* Fix for Breadcrumbs::meta()
* Fix for Posts views for cats and tags
* Added example for the Event Manager API Class
* Added Support for post 2 post plugin
* Better plugin botstrapping
* Better action hook for the loader plugin
* Refactoring for the widget area metabox
* Refactoring for the Posts API
* Refactoring for the Breadcrumbs API
* Refactoring for the Video Lazy Load
* Refactoring for the Bootstrap Carousel validate_data method
* Refactoring for the plugin main file
* Bumps up minimum requirements for the ACM
* Alpha functionality are now in dev mode

### 2.7.0 ###
Release Date: June 28th, 2017

Dev time: 200h

* New Plugin Name: Advanced Control Manager formerly ItalyStrap
* New Minify API
* New option for positioning analytics code (wp_footer or wp_header)
* New Widget Nav_Menu only for BETA tester
* Improvement of the Widget Area import
* Improvement of the autoload of Import/Export
* Improvement of the settings array
* Fixed hfeed mcrodata issue

### 2.6.0 ###
Release Date: April 18th, 2017

Dev time: 100h

* Refactoring of the Widget Areas functionality (beta)
* Refactoring of the plugin bootstrapping
* New Adapter for CMB2
* Read more link from options available also for Widget Post
* Fixed some little error
* Fixed empty excerpt for auto-generate excerpt
* Updated test files

### 2.5.0 ###
Release Date: April 1st, 2017

Dev time: 100h

* Added New Shortcodes for column in editor only for beta version (do not use it)
* Added New Shortcode Factory Class
* Added New Config class for internal use
* Added New Widget Editor in alpha version (do not use it)
* Added New Customizer controllers (Multicheckbox)
* Better name for future Grouped Posts Widget
* Improvements of the CSS Class Generator
* Improvements of the Font selector for customizer
* Some refactoring for the Carousel Class
* Some refactoring for the Social Share Class
* Refactoring of the widget loader
* Refactoring of the Web Fonts Lazy Loading
* Refactoring of the entire plugin file structure (Big changes)
* Refactoring of the Widgets and Shortcodes Factory
* Other refactoring for general files
* Fixed array to string conversion in Carousel.php
* Fixed post exclusion on archi page for Post.php (Widget and Shortcode)
* Fixed undefined index in Fonts.php
* Fixed empy value for width and height in Carousel
* Deprecated old Widget namespace, now it is ItalyStrap\Widgets;

### 2.4.2 ###
Release Date: February 25th, 2017

Dev time: 20h

* Added class obj to the 'italystrap_ga_commands_queue_output' filter in Analytics class
* Added 'add_permalink_wrapper' for title and image in Widget Posts
* Better tabs organization in Widget Image
* Fixed widget visibility error on wp-admin/widgets.php (When visibility selected the widget disappeared from the admin.)

### 2.4.1 ###
Release Date: February 25th, 2017

Dev time: 100h

* New Plugin description
* New Widget Monster for internal widgets in ALPHA version
* New Classes for internal debugging
* New internal shortcode for displaying docs
* New directory for docs
* New input for icon name in Widget Image
* New method for forcing remove filters
* Allow HTML in Widget Carousel title
* Allow HTML in Widget Image description
* Refactoring: the bootstrap application
* Refactoring: Widget registering
* Refactoring: Widget Class (method visibility)
* Refactoring: Some internal refactoring
* Fixed PHP error on \Excpetion

### 2.4.0 ###
Release Date: January 23rd, 2017

Dev time: 100h

* New Event Manager API
* Added counts by ID inserted for Posts API
* Added some example for Widget Creation
* Added migration strategy for the ItalyStrap theme framework user
* Added new filter for google analytics output
* Added some example for google analytics API
* Added button for widget area only for the ItalyStrap theme framework user
* Refactoring: Some changes for Taxonomies list API (Only for beta tester)
* Refactoring: Some changes in Widget API
* Refactoring: Some changes in Social Share API (Only for beta tester)
* Fixed issue in share button with EDD actived
* Improved media carousel description


### 2.3.0 ###
Release Date: Dicember 31st, 2016

Dev time: 200h

* New API for FB pages (only for Beta tester)
* New wdget for FB pages (only for Beta tester)
* New widget visibility (only for Beta tester)
* New lazyload for videos (only for Alpha tester)
* New option for hiding widget title
* Added new functionality for the excerpt read more link
* Added some example files
* Added most viewed posts for widget posts (uses Jetpack stats)
* Refactoring: Google Analytics API
* Refactoring: Inline script and css API
* Refactoring: Carousel API: Added 'read more' link for post
* Internal use: new sanitize method
* Internal use: new fields group (Beta version)
* Some code refactoring

### 2.2.1 ###
Release Date: Dicember 4th, 2016

Dev time: 100h

* Added jpeg_quality option
* Improved validation of options on settings saving
* Fixed excerpt more output in Widget Posts
* Fixed a php fatal error on new install
* fixed undefined index on new install
* Some code refactoring

### 2.2.0 ###
Release Date: November 13th, 2016

Dev time: 50h

* New Import Export API for the plugin
* Added hook for breadcrumbs, now you can use `<?php do_action( 'do_breadcrumbs', array() ); ?>` instead of the core function.
* Fixed some issue
* customize_selective_refresh for all widget in this plugin.

### 2.1.1 ###
Release Date: November 10th, 2016

Dev time: 30h

* Fixed [#15](https://github.com/overclokk/italystrap-extended/issues/15)
* psr-4 almost ready for admin classes
* Improvements of the Fields API
* Improvemente of the Settings API
* Class for Social Sharing only for beta tester
* Other fix, improvements and refactoring

### 2.1.0 ###
Release Date: November 7th, 2016

Dev time: 10h

* Better Field Class API improvements
* Added Google Analytics snippet with basic options in ItalyStrap settings
* New function for getting default plugin config
* Added PHP Cache for menu only for beta tester
* Improved functionality for gallery settings (work in progress)
* Some code fix
* Fixed a PHP 5.3 fatal error

### 2.0.2 ###
Release Date: October 28th, 2016

Dev time: 2h

* New options for adding a CSS class to image title for widget image
* Fixed field type for Zipcode in vCard widget
* Temporary added P.IVA for vCard output
* Changed array short notation to extended notation

### 2.0.1 ###
Release Date: October 25th, 2016

Dev time: 4h

* [Fix typo #11](https://github.com/overclokk/italystrap-extended/pull/11) Thanks to [Marco Bianco](https://github.com/cobyan)
* [Improvement to Lazy Load #14](https://github.com/overclokk/italystrap-extended/pull/14) Thanks to [Rocco Aliberti](https://github.com/eri-trabiccolo)
* Added custom placeholder for Lazy Load

### 2.0.0 Breaking Changes ###
Release Date: October 19th, 2016

Dev time: over a year

Make backup first ;-)

**DEPRECATED**

* Deprecated class `ItalyStrapBreadcrumbs()`, use `ItalyStrap\Core\breadcrumbs()` instead.
* Deprecated title attribute (`$atts['title']`) in `gallery` shortcode, use `image_title` instead.
* Deprecated vCard Widget (there's a new version for this widget, see below)
* Deprecated `italystrap_get_apply_lazyload( $content );` and `italystrap_apply_lazyload( $content );` use `ItalyStrap\Core\get_apply_lazyload( $content );` and `ItalyStrap\Core\apply_lazyload( $content );`

**FIX**

* **All filters and actions** start with `italistrap_` **lowercase** (if you find some in CamelCase or some without a prefix please let me know).
* Fix front page and posts page visualization for breadcrumbs.

**ENHANCEMENTS**

* New API for settings options
* New API for settings options
* New API for fields
* New API for sanitization and validation
* New API for exporting and importing still in alpha version not active
* New plugin settings interface for displaying all options.
* New API for templating system in beta version, in future you can override the template used in widgets and shortcodes like WooCommerce does.
* Added some general utilities in plugin settings:
  * Show post type ID
  * Show post type thumb
  * Disable the emoji
  * HTML attributes for widgets
  * Possibility to add some tags to widget title with `{{}}` instead of `<>`
* **Widgets:**
  * New API for building widgets in a easy way (it is possible to create your own widget too but the docs will be available soon)
  * Widget for vCard  with schema.org markup (this is a new version, the old one is deprecated, you can see in the widget description)
  * Widget for Post (a widget with a lot of options for displaying post, page and custom type in a widget area)
  * Widget for Bootstrap Carousel (it works only if you have Twitter Bootstrap CSS loaded by your theme), the settings are the same of shortcode settings.
* **Shortcode:**
  * Option for executing shortcode in the widget text
  * Option for Carousel shortcode, now you have to activate it for making it works.
* **Style:**
 * Text area for custom CSS
 * Input fields for adding custom body class and post class attribute.
* **Script:**
 * There are a new section in plugin settings still in BETA version for adding script like GA adn FB pixel

### 1.3.3 ###
Release Date: September 19th, 2015

Dev time: 8h

* Added logo upload in vCard widget
* Fixed some issue

### 1.3.2 ###
Release Date: August 14th, 2015

Dev time: 20h

* Added a simple HTML sitemaps class for theme (beta version)
* [Fixed Deprecating PHP4 style constructors in WordPress 4.3](https://make.wordpress.org/core/2015/07/02/deprecating-php4-style-constructors-in-wordpress-4-3/)

### 1.3.1 ###
Release Date: June 13th, 2015

Dev time: 1h

* Added taxonomy support for breadcrumbs

### 1.3.0 ###
Release Date: March 4th, 2015

Dev time: 30h

* Added Local Business widget for widgetized themes

### 1.2.1 ###
Release Date: March 1st, 2015

Dev time: 5h

* Added new function for LazyLoad
* Fixed some issue

### 1.2.0 ###
Release Date: February 27th, 2015

Dev time: 40h

* Fixed some bug and issue
* Added static class for appending inline script and print it in footer after wp_print_footer_scripts hook (for performance purpose)
* Added static class for appending inline css and print it in header after wp_head hook  (for performance purpose)
* Added class for Lazy Load image functionality

### 1.1.0 ###
Release Date: February 20th, 2015

Dev time: 80h

* Renders extra controls for image dimension in the new media UI
* Added Bootstrap Carousel functionality forked from Agnosia Bootstrap Carousel by AuSoft
* Renders extra controls in the Gallery Settings section of the new media UI
* Added Mobile_Detect class from https://github.com/serbanghita/Mobile-Detect for responsive functions
* Added Schema.org in Bootstrap Carousel
* Fixed some bugs


### 1.0.2 ###
Release Date: January 8th, 2015

Dev time: 1h

* Fixed documentation link in admin dashboard (Thanks to Stefano Tondi from G+)

### 1.0.1 ###
Release Date: January 8th, 2015

Dev time: 1h

* Updated Breadcrumbs documentation

### 1.0.0 ###
Release Date: January 7th, 2015

Dev time: 100h

* First release

## Upgrade Notice ##

### 2.2.0 ###
Requires PHP: 7.2
If you don't have the latest version of ItalyStrap it may break you site.

### 2.11.0 ###
Requires PHP: 5.4

### 2.0 ###
2.0 is a major update. It is important that you make backups and ensure your WordPress is 2.0 compatible before upgrading, in particular the breadcrumbs, the lazyload and the carousel, please read the changelog for more information or [read more here](http://www.italystrap.com/plugin-version-2-available/).

### 1.0.2 ###
This version fixes a documentation link in admin dashboard.  Upgrade as soon as possible

### 1.0.1 ###
This version updates Breadcrumbs documentation.  Upgrade as soon as possible

### 1.0.0 ###
First release.

## Translations ##

* English: default, always included.
* Italian: Italiano, sempre incluso.
* German: Deutsch - immer dabei!
* French: Français, toujours inclus.

*Note:* This plugins is localized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/).

## Additional Info ##
**Idea Behind / Philosophy:** A plugin for improve and add some powerful improvement to your site. I'll try to add more feautures if it makes some sense. So stay tuned :).

## Credits ##

### Plugin ###
* [Agnosia Bootstrap Carousel](https://wordpress.org/plugins/agnosia-bootstrap-carousel/)
* [Lazy Load](https://wordpress.org/plugins/lazy-load/)
* [WordPress Plugin Boilerplate](https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered)
* [Disable Emojis](https://geek.hellyer.kiwi/)
* [Ultimate Posts Widget](https://wordpress.org/plugins/ultimate-posts-widget/)

### Developers ###
* [Tonya Mork](https://knowthecode.io/)
* [Carl Alexander](https://carlalexander.ca/)
* [Alain Schlesser](https://www.alainschlesser.com/)
* [Luca Tumedei](https://github.com/lucatume)
* [Mattia Migliorini](https://github.com/deshack)
* [Marco Bianco](https://github.com/cobyan)
* [Rocco Aliberti](https://github.com/eri-trabiccolo)
