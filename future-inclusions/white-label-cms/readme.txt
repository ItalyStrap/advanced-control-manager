=== White Label CMS ===

Contributors: VideoUserManuals
Plugin Name: White Label CMS
Plugin URI: http://www.videousermanuals.com/white-label-cms/?utm_campaign=wlcms&utm_medium=plugin&utm_source=readme-txt
Tags: cms, custom, admin, branding, dashboard, administration, plugin, login, client, menu, navigation, appearance, menus, widgets
Author URI: http://www.videousermanuals.com/?utm_campaign=wlcms&utm_medium=plugin&utm_source=readme-txt
Author:  Video User Manuals
Requires at least: 3.3 
Tested up to: 4.2
Stable tag: 1.5.4
Version:1.5.4

Allows complete customization of dashboard panels and logos, removal of menus, giving editors access to widgets and menus plus lots more. Import/export your settings and include an RSS panel on the dashboard to feed your clients the latest posts from your blog to keep you top of mind whenever they login to their dashboard.

== Description ==
The White Label CMS plugin is for developers who want to give their clients a more personalised and less confusing content management system

For a video overview of the changes in 1.5 and how this affects WordPress 3.4 please visit the [White Label CMS](http://www.videousermanuals.com/white-label-cms/?utm_campaign=wlcms&utm_medium=plugin&utm_source=description "White Label CMS") home hosted on the [WordPress Manual Plugin](http://www.videousermanuals.com/?utm_campaign=wlcms&utm_medium=plugin&utm_source=description "WordPress Manual Plugin") website.

WordPress 3.3's new admin bar has restricted the type of branding you can have for clients, which is why we have introduced the ability to brand the dashboard.

You also have the ability to choose which menus are visible.  We have 3 CMS profiles available as presets: Website, Blog or Custom so you can modify the menu system to suit the CMS purpose. These only apply to the user role of Editor and below. Admins will see all menus.

You can also give Editors access to the Menu and Widgets, but the switch theme option will not appear.

White Label CMS allows you to remove all the panels from the WordPress dashboard and insert your own panel, which you can use to write 2 personalised messages to your client and link to the important elements in the CMS.

It also allows you to add custom logos to the header and footer as well as the all important login page, giving your client a better branded experience of their new website.

There is also the option to hide the nag update as well.

No longer will you have to tell your clients to ignore the dashboard!

== Installation ==
1. Download the White Label CMS plugin
2. Upload it to your plugins directory
3. Go to the plugins directory and activate the plugin
4. Go to Settings->White Label CMS and use the menu system to change the default values.

Please note:
We have updated the way images now work. There are 3 options:
You can either just add the filename, and this will look inside your theme/child theme's images folder for the file.
You can put the full url of the image, if you want it to load from an external site
You can use the WordPress uploader, but please make sure you click "insert image"

== Upgrade Notice ==
Updates for WordPress 3.3
More Bug Fixes for WLCMS 1.4 and improved support for other languages.
You can now brand the dashboard as well as the admin bar.
!You must save the new settings if you are upgrading from 1.3!

== Screenshots ==
1. An example of a custom login
2. An example of how your clients dashboard could look
3. Customize which menus appear for editors
4. Simple menu options
5. Customize the homepage

== Changelog ==

= 1.5.4 =
Fixed conflict with WP Mandrill plugin
Added option to hide the activity panel in the dashbaord
Renamed Right Now widget to At a Glance

= 1.5.3 =
Security release. Better use of WordPress nonces and enhanced validation on import functionality. Disclosed by g0blin.

= 1.5.2 =
Login Logo width fixed for version wordpress 3.8
fixed 16px logo in admin bar on front end
Fixed advert on wlcms advert on the settings page
Fixed Dashboard logo not appear on dashboard for WP 3.8
Fixed Footer logo does not line up properly WP 3.8
Fixed Hide WordPress Logos from admin bar


= 1.5.1 =
Security patch - added nonce to admin form for better security. Props PC SJJ. 

= 1.5 =
Added Custom RSS Feeds as new option
Added Import/Export of settings feature.
Updated CSS rules for wp-login.
Minor bug fixes

= 1.4.7 =
Minor Bug introduced with WordPress 3.4 and custom login images. 
Also added conditionals to prevent any errors with user capabilities. 

= 1.4.6 =
Patch submitted by Chris @ iThemes. Better support for users of iThemes.

= 1.4.5 =
Changed how media menu is hidden to allow both hiding the menu and still able to add content. (Small bug)

= 1.4.4 =
Better support for other languages, deactivating the plugin does not remove options, only deleting it will run the uninstall script. Other minor fixes.

= 1.4.3 =
Minor Bug Fixes and some improvements on how forms are saved.

= 1.4.2 =
Bug Fixes, hopefully the last ones for this version

= 1.4.1 =
Bug Fixes for radio buttons, media uploader and login style

= 1.4 =
Changes for WordPress 3.3
Ability to brand the dashboard
Lots of new changes and bug fixes

= 1.3 =
Changes for WordPress 3.2
Restructure of menus
Added classic header and footer for 3.2 to improve branding
Header logo as site link
Admin can view dashboards but hide them from editors
Hide browser nag

Bug fixes

= 1.2 =
Ability to show Menu & Widgets menu for Editors
Removed WordPress link and ALT text from login page.
Custom css for forgotten link on login page.
Tested on multi user sites.
Fixed a bug which was stopping the Profile from appearing.

= 1.1 =
Ability to remove menus
Added widths for header and footer logos.

= 1.0.5 =
Updated terminology

= 1.0.4 =
Updated custom login image file height

= 1.0.3 =
Updated logo filename

= 1.0.2 =
Added update log!

== Frequently Asked Questions ==
= Who is this plugin for?=
For developers who handover websites to their clients and use WordPress as a CMS.

=How to I add links to my own panel?=
Your custom panel accepts html, so just write the markup as you normally would in html.

=How do I remove menu items?=
Click on the Remove Menus section and either choose a CMS profile, or manually select which menu items to be removed. Please not this will only effect user roles of Editor and below. So you will need to logout in order to see the difference.

=How do you recommend using this plugin?=
We have been using this for a number of months now, and we have found clients respond best when it is set up in the following fashion:

* Use the clients logo for the login (it gives the CMS a bit of a wow factor!)
* Use the clients logo in the header
* Use your own logo in the footer, and place a link back to your website
* Remove all panels from the dashboard (they have a lot of information that just confuses the client)
* Add your own panel. Personalise the experience for your client by welcoming them to their website. Provide links to the most relevant elements in the CMS. Provide a link back to your support system if you have one.

=Appearance Menu=
With WordPress 3 comes the much need Menus option. However, this sits inside the Appearance menu which is hidden to editors. You can you make the Menus and Widgets menus available to editors, but can keep the switch theme menu removed.

Please note: The plugin works by granting Editor's the 'switch_themes' and 'edit_theme_options' privilege which gives them access to the Appearance menu, but removes the switch themes menu from the Appearance menu and the WordPress 3 Right Now dashboard. However it could still be possible for a Editor to switch themes, if they knew the direct url path. Unlikely, but you should be aware of this before you choose to enable these options.

Menus is only available to WordPress 3 users.

If you see menus like background or header, then you must modify your theme in order to remove them.

When the plugin is uninstalled, the Editor privileges are reset to their original values.

=How do I use it on Multi Site?=
* You must install the plugin network wide in order for it to work on all sites.
* You must save the options on each new site in order for the default options to appear.
* You can have separate login logos for each mini site. Simply change the filename, and upload it to the relevant theme.

=Troubleshooting=
I installed the plugin and the logos disappear?: You need to upload your logos to you current themes images directory.

The menus have not changes?: Make sure you are logged in as the editor

Lost Password CSS not working?: Make sure you use the example format. The color must be the last css style and it must not have a closing ; as !important is appended to the end of the style to overwrite and existing style.