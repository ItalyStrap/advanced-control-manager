<?php

$wlcmsShortName = "wlcms_o";



global $wp_version, $submenu;

$wlcmsOptions = array(
    array( "name" =>  "White Label CMS Options", "type" => "title"),
    array( "name" => "Branding", "type" => "section"),
    array( "type" => "open"),
    array( "name" => "Admin Bar", "type" => "subtitle")
);
 
if ( version_compare( $wp_version, '3.2.5', '>=' ) )
{
    $wlcmsOptions[] = array(
            "name"  => "Hide WordPress Logos",
            "desc"  => "Hide WordPress logo from the admin bar and home icon",
            "id"    => $wlcmsShortName."_hide_wp_adminbar",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 0);
    $wlcmsOptions[] = 	array(
            "name"  => "Add Your Logo (16px x 16px)",
            "desc"  => "Adds a 16px logo to the admin bar",
            "id"    => $wlcmsShortName."_adminbar_custom_logo",
            "class" => 'upload_image_button',
            "type"  => "file",
            "std"   => '');
    $wlcmsOptions[] = array( "name" => "Add Dashboard Logo", "type" => "subtitle" );
    $wlcmsOptions[] = array(
            "name"  => "Add Dashboard Logo",
            "desc"  => "This will replace the home icon on the dashboard with your own logo.",
            "id"    => $wlcmsShortName."_header_custom_logo",
            "class" =>'upload_image_button',
            "type"  => "file",
            "std"   => '');
    $wlcmsOptions[] = array(
            "name"  => "Replace Dashboard Heading",
            "desc"  => "This will replace the heading \"Dashboard\" on the dashboard page. This combined with a custom logo will help improve the branding experience for your client",
            "id"    => $wlcmsShortName."_dashboard_override",
            "type"  => "text",
            "title" => '',
            "std"   => __('Dashboard'));
}
else
{
    $wlcmsOptions[] = array(
            "name"  => "Classic Header Height",
            "desc"  => "<b>3.2 Only</b> - This will keep Header height to 46px, pre WordPress 3.2 size (better for branding) if its empty",
            "id"    => $wlcmsShortName."_header_height",
            "type"  => "text",
            "unit"  => "px",
            "class" => 'default-text',
            "title" => 'Header',
            "std"   => '0');

    $wlcmsOptions[] = array(
            "name"  => "Custom Header Logo",
            "desc"  => "This is a logo that will appear in the header. It should be a transparent .gif or .png and about 32px by 32px. You can either upload an image, or just type in the image name if you have already placed it in the images folder of your theme or child.",
            "id"    => $wlcmsShortName."_header_custom_logo",
            "class" => 'upload_image_button',
            "type"  => "file",
            "std"   => '');

    $wlcmsOptions[] = array(
            "name"  => "Custom Header Logo Width",
            "desc"  => "Leave blank for default value of 32px.",
            "id"    => $wlcmsShortName."_header_custom_logo_width",
            "type"  => "text",
            "unit"  => "px",
            "class" => 'default-text',
            "title" => '32',
            "std"   => '');
  
	$wlcmsOptions[] = array(
            "name" => "Header Logo As Site Link",
            "desc" => "The logo that appears in the header with be the link to the site. It will remove the text link",
            "id" => $wlcmsShortName."_header_logo_link",
            "type" => "radio",
            "options" => array("1", "0"),
            "std" => 0);
}

$wlcmsOptions2 = array(
    array( "name" => "Footer", "type" => "subtitle" ),
    array(
            "name"  => "Custom Footer Logo",
            "desc"  => "This logo will appear in the footer on the left hand side",
            "id"    => $wlcmsShortName."_footer_custom_logo",
            "type"  => "file",
            "class" => 'upload_image_button',
            "std"   => ''),
	
    array(
            "name"  => "Developer Website URL",
            "desc"  => "There will be a link to your website in the footer. Leave it blank if you don't want the link otherwise please include http://",
            "id"    => $wlcmsShortName."_developer_url",
            "type"  => "text",
            "std"   => ''),
	
    array(
            "name"  => "Developer Website Name",
            "desc"  => "The developer's name will appear in the footer",
            "id"    => $wlcmsShortName."_developer_name",
            "type"  => "text",
            "std"   => ''),

    array(
            "name"  => "Hide WP Version",
            "desc"  => "This will hide WordPress Version in right corner of the footer and on the Right Now dashboard panel",
            "id"    => $wlcmsShortName."_hide_wpversion",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 0),

    array(  "name" => "Login", "type" => "subtitle"),
	
    array(
            "name"  => "Make background colour white",
            "desc"  => "This will make the login screen have a white background. Useful if your logo isn't transparent.",
            "id"    => $wlcmsShortName."_loginbg_white",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 0),

    array(
            "name"  => "Custom Login Logo",
            "desc"  => "This logo will appear on the login page.  It should be about 300px by 80px.",
            "id"    => $wlcmsShortName."_login_custom_logo",
            "type"  => "file",
            "class" => 'upload_image_button',
            "std"   => ''),

    array(
            "name"  => "Custom Login CSS",
            "desc"  => "For example:<br /> .login form { background-color: #0013FF } <br />.login #login p#nav a { color: #333 !important }<br /><br /> Or if you want to get fancy:<br /> #wlcms-login-wrapper{ background: url('wp-content/plugins/white-label-cms/images/footergrass.jpg') repeat-x fixed center bottom transparent; display: block; height: 100%; left: 0; overflow: auto; position: absolute; top: 0; width: 100%;} ",
            "id"    => $wlcmsShortName."_login_bg_css",
            "type"  => "textarea",
            "std"   => ''),

    array(  "name" => "Admin Page Title","type" => "subtitle"),

    array(
            "name"  => "Custom Page Titles",
            "desc"  => "This replaces the - WordPress in the admin page titles. If this is set to nothing WordPress will continue to appear in the page titles",
            "id"    => $wlcmsShortName."_admin_page_title",
            "type"  => "text",
            "std"   => ''),

    array( "type" => "close"),
    array( "name" => "Dashboard Panels", "type" => "section"),
    array( "type" => "open"),

    array(
            "name"  => "Hide 'At a Glance'",
            "desc"  => "Hides the at a glance panel",
            "id"    => $wlcmsShortName."_dashboard_remove_right_now",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 0),

    array(
	    "name"  => "Hide 'Activity'",
	    "desc"  => "Hides the activity panel",
	    "id"    => $wlcmsShortName."_dashboard_remove_activity_panel",
	    "type"  => "radio",
	    "options" => array("1", "0"),
	    "std"   => 0),

    array(
            "name"  => "Hide 'Recent Comments'",
            "desc"  => "Hides the comments panel",
            "id"    => $wlcmsShortName."_dashboard_remove_recent_comments",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 1),

    array(
            "name"  => "Hide Other Dashboard Panels",
            "desc"  => "These are all the other default dashboard panels",
            "id"    => $wlcmsShortName."_dashboard_others",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 1),
			
    array(
            "name"  => "Show Dashboard To Admin",
            "desc"  => "All panels will appear for the admin",
            "id"    => $wlcmsShortName."_dashboard_admin",
            "type"  => "radio",
            "options" => array("1", "0"),
            "std"   => 1),

    array(
            "name" => "Remove Empty Dashed Panel",
            "desc" => "If you only have 1 dashboard panel then a empty panel will appear with a dashed border. Selecting yes will remove the border.",
            "id" => $wlcmsShortName."_dashboard_border",
            "type" => "radio",
            "options" => array("1", "0"),
            "std" => 1),

    array(
            "name" => "Add your own Welcome Panel?",
            "desc" => "This will appear on the dashboard. We recommend providing your contact details and links to the help files you have made for your client.",
            "id" => $wlcmsShortName."_show_welcome",
            "type" => "radio",
            "options" => array("1", "0"),
            "std" => "0" ),

    array( "name" => "Welcome Panel Settings", "type" => "subsectionvars"),

    array(
            "name" => "Visible To",
            "desc" => "This means the welcome panel will appear for users with roles higher or equal to the one chosen.",
            "id" => $wlcmsShortName."_welcome_visible_to",
            "type" => "select",
            "options"=>wlcms_roles_dropdown(),
            "std" => 'editor'),

    array(
            "name" => "Title",
            "desc" => "The title of your dashboard panel.",
            "id" => $wlcmsShortName."_welcome_title",
            "type" => "text",
            "std" => 'Welcome To Your New Website'),

    array(
            "name" => "Description",
            "desc" => "Please add the text in html format here.",
            "id" => $wlcmsShortName."_welcome_text",
            "type" => "textarea",
            "std" => ''),

array( 	"name" => "Second Panel Settings","type" => "subtitle"),

array( 	"name" => "Visible To",
		"desc" => "This means the welcome panel will appear for users with roles lesser or equal to the one chosen.",
		"id" => $wlcmsShortName."_welcome_visible_to1",
		"type" => "select",
		"options"=>wlcms_roles_dropdown(),
		"std" => ''),

array( 	"name" => "Title",
		"desc" => "The title of your dashboard panel.",
		"id" => $wlcmsShortName."_welcome_title1",
		"type" => "text",
		"std" => ''),	

array( 	"name" => "Description",
		"desc" => "Please add the text in html format here.",
		"id" => $wlcmsShortName."_welcome_text1",
		"type" => "textarea",
		"std" => ''),

	array( "type" => "closeonce"),
		
    array(
            "name" => "Add an RSS Dashboard panel?",
            "desc" => "This will appear on the dashboard. If you want your client to be kept up to date with what your are doing in the your business, set up your RSS feed.",
            "id" => $wlcmsShortName."_show_rss_widget",
            "type" => "radio",
            "options" => array("1", "0"),
            "std" => "0" ),

    array( "name" => "RSS Settings", "type" => "subsectionvars"),
    
    array(
            "name" => "RSS Title",
            "desc" => "The title of your RSS dashboard panel.",
            "id" => $wlcmsShortName."_rss_title",
            "type" => "text",
            "std" => ''),
    array(
            "name"  => "Add Your Logo (16px x 16px)",
            "desc"  => "Adds a 16px logo before the title",
            "id"    => $wlcmsShortName."_rss_logo",
            "class" => 'upload_image_button',
            "type"  => "file",
            "std"   => ''),
    array(
            "name" => "RSS Feed",
            "desc" => "The RSS feed address. For example feed://www.vitaminseo.com.au/feed/",
            "id" => $wlcmsShortName."_rss_value",
            "type" => "text",
            "std" => ''),
    array(
            "name" => "Number Items",
            "desc" => "Number of RSS items to show",
            "id" => $wlcmsShortName."_rss_num_items",
            "type" => "selectbox",
            "options"=> array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10),
            "std" => 2),
    array(
            "name" => "Show Post Contents",
            "desc" => "Show the content of the RSS item. This will display what is in your RSS feed, if this is not what you want, please modify your RSS feed.",
            "id" => $wlcmsShortName."_rss_show_intro",
            "type" => "selectbox",
            "options"=> array('yes'=>'Yes','no'=>'No'),
            "std" => 'yes'),
    array(
            "name"  => "RSS Intro",
            "desc"  => "If you would like to have some text above the RSS items explaining it. Please add the text in html format here.",
            "id"    => $wlcmsShortName."_rss_intro_html",
            "type"  => "textarea",
            "std"   => ''),
		
	
array( "type" => "closeonce"),

array( "type" => "close"),

array( 	"name" => "Admin Settings", "type" => "section"),
array( "type" => "open"),
	
array( 	"name" => "Enable Login URL Redirect",
		"desc" => "Clients can go to /login as well as /wp-login.php only when permalinks are enabled",
		"id" => $wlcmsShortName."_enable_login_redirect",
		"type" => "radio",
		"options" => array("1", "0"),
		"std" => 1),
			
array( 	"name" => "Hide Nag Update",
		"desc" => "This will hide the Nag Update for out of date versions of WordPress",
		"id" => $wlcmsShortName."_dashboard_remove_nag_update",
		"type" => "radio",
		"options" => array("1", "0"),
		"std" => 1),
			
array( 	"name" => "Hide Help Box",
		"desc" => "This will hide the Help Box dropdown",
		"id" => $wlcmsShortName."_dashboard_remove_help_box",
		"type" => "radio",
		"options" => array("1", "0"),
		"std" => 0),
			
array( 	"name" => "Hide Screen Options",
		"desc" => "This will hide the Screen Options dropdown",
		"id" => $wlcmsShortName."_dashboard_remove_screen_options",
		"type" => "radio",
		"options" => array("1", "0"),
		"std" => 0),

array( 	"name" => "Hide Meta Boxes","type" => "subtitle"),
	
array("id"=>'',"type"=>"divopen",'class'=>"wlcms_input"),

array(	"heading" => "Hide Post Meta Boxes",
		"desc" => "Choose meta boxes that you want to remove from the Edit Post panel",
		"type"=>"headings"),
			
array( 
		"id" => $wlcmsShortName."_hide_post_div",
		"type" => "divopen",
		'class'=>$wlcmsShortName."_hide_post_divclass"),
array( 
		"id" => $wlcmsShortName."_post_meta_box_excerpt",
		"type" => "checkbox",
		"label"=>"Excerpt",
		"std" => 0),
array( 
		"id" => $wlcmsShortName."_post_meta_box_slug",
		"type" => "checkbox",
		"label"=>"Slug",
		"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_post_meta_box_tags",
	"type" => "checkbox",
	"label"=>"Tags",
	"std" => 0),
		
array( 
		"id" => $wlcmsShortName."_post_meta_box_author",
		"type" => "checkbox",
		"label"=>"Author",
		"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_post_meta_box_comments",
	"type" => "checkbox",
	"label"=>"Comments",
	"std" => 0),
array( "type" => "divclose"),

array( 
		"id" => $wlcmsShortName."_hide_post_divnew",
		"type" => "divopen",
		'class'=>$wlcmsShortName."_hide_post_divclassnew"),

array( 
	
	"id" => $wlcmsShortName."_post_meta_box_revisions",
	"type" => "checkbox",
	"label"=>"Revisions",
	"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_post_meta_box_discussions",
	"type" => "checkbox",
	"label"=>"Discussion",
	"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_post_meta_box_categories",
	"type" => "checkbox",
	"label"=>"Categories",
	"std" => 0),

array( 
		"id" => $wlcmsShortName."_post_meta_box_custom",
		"type" => "checkbox",
		"label"=>"Custom Fields",
		"std" => 0),
			
array( 
		"id" => $wlcmsShortName."_post_meta_box_send",
		"type" => "checkbox",	
		"label"=>"Send Trackbacks",
		"std" => 0),
			
			
				
array("type" => "divclose"),
array("type" => "divclose"),
array('type'=>'clear'),
array('type'=>'space'),

array( 

"id" => '',
"type" => "divopen",
'class'=>"wlcms_input"),
	
/**/
array( "heading" => "Hide Page Meta Boxes",
	"desc" => "Choose meta boxes that you want to remove from the Edit Page panel",
	"type"=>"headings"),
array( 
	
	"id" => $wlcmsShortName."_hide_page_divmain",
	"type" => "divopen",
	'class'=>$wlcmsShortName."_hide_page_divclassmain"),

array( 
	
	"id" => $wlcmsShortName."_hide_page_div",
	"type" => "divopen",
	'class'=>$wlcmsShortName."_hide_page_divclass"),
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_custom",
	"type" => "checkbox",
	"label"=>"Custom Fields",
	"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_author",
	"type" => "checkbox",
	"label"=>"Author",
	"std" => 0),
		
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_discussions",
	"type" => "checkbox",
	"label"=>"Discussion",
	"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_revisions",
	"type" => "checkbox",
	"label"=>"Revisions",
	"std" => 0),
array( 
	
	
	"type" => "divclose"
	),
array( 
	
	"id" => $wlcmsShortName."_hide_page_divnew",
	"type" => "divopen",
	'class'=>$wlcmsShortName."_hide_page_divclassnew"),
 
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_page",
	"type" => "checkbox",
	"label"=>"Page Attributes",
	"std" => 0),
array( 
	
	"id" => $wlcmsShortName."_page_meta_box_slug",
	"type" => "checkbox",
	"label"=>"Slug",
	"std" => 0),
array("type" => "divclose"),
array("type" => "divclose"),
array("type" => "divclose"),
	
array( "name" => "Custom CSS for Admin",
	"desc" => "Override or add to any of the styles in the WordPress admin enter your own custom css here",
	"id" => $wlcmsShortName."_custom_css",
	"type" => "textarea",
	"std" => ''),
array( "name" => "Custom Editor Stylesheet",
	"desc" => "Create and upload a custom stylesheet with all style rules  prefixed with .mceContentBody to your themes directory and enter the filename",
	"id" => $wlcmsShortName."_welcome_stylesheet",
	"type" => "textcustom",
	"std" => ''),	
array( "type" => "close"),

//*///////////////////////////////////

array( "name" => "Modify Menus",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "These changes will only effect people with the user role of <strong>Editor</strong>.  You are currently logged is as the admin, so you will not see any changes in the menus until you login with a different user role.",
	"type" => "message"),

array("name" => "Choose A CMS Profile","desc" => "Which profile best fits your clients needs?","id" => $wlcmsShortName."_cms_profile","type" => "radioprofile","options" => array("1", "2","3"),	"std" => '1'),
//array("name" => "Menus", "type" => "menus"),	
array( 
	
	"id" => $wlcmsShortName."_modify_menus",
	"type" => "divopen",
	'class'=>$wlcmsShortName."_modify_menu"),
array( 
	"id" => $wlcmsShortName."_modify_menu_post",
	"type" => "divopen",
	'class'=>$wlcmsShortName."_modify_menu"),
/***/
array( "name" => "Hide Posts Menu",
	"desc" => "Hides Posts &amp; Comments sections",
	"id" => $wlcmsShortName."_hide_posts",
	"type" => "checkbox",
	"class"=>$wlcmsShortName."_child_label",
	"std" => 0),	
 
array( "name" => "Hide Media Menu",
	"desc" => "Hides Media from left menu",
	"id" => $wlcmsShortName."_hide_media",
	"type" => "checkbox",
	"class"=>$wlcmsShortName."_child_label",
	"std" => 0),	
  
array( "name" => "Hide Links Menu",
	"desc" => "Hides Links from left menu",
	"id" => $wlcmsShortName."_hide_links",
	"type" => "checkbox",
	"class"=>$wlcmsShortName."_child_label",
	"std" => 0),
 

array( "name" => "Hide Pages Menu",
	"desc" => "Hides Pages from left menu",
	"id" => $wlcmsShortName."_hide_pages",
	"type" => "checkbox",
	"class"=>$wlcmsShortName."_child_label",
	"std" => 0),
 
array( "name" => "Hide Comments Menu",
	"desc" => "Hides Comments from left menu",
	"id" => $wlcmsShortName."_hide_comments",
	"type" => "checkbox",
	"class" => $wlcmsShortName."_child_label",
	"std" => 0),

array( "name" => "Hide Profile",
	"desc" => "Hides Profile menu item from left menu",
	"id" => $wlcmsShortName."_hide_profile",
	"type" => "checkbox",
	"class" => $wlcmsShortName."_child_label",
	"std" => 0),

array( "name" => "Hide Tools",
	"desc" => "Hides Tools from left menu",
	"id" => $wlcmsShortName."_hide_tools",
	"type" => "checkboxlast",
	"class" => $wlcmsShortName."_child_label",
	"std" => 0),


    

    array(

	"type" => "divclose"
	),
	
	
    array(

	"type" => "divclose"
	) ,
		
	);


$wlcms_show_appearance_legacy = '0';
$wlcms_show_menus_legacy = '1';
$wlcms_show_widgets_legacy = '1';
  
$wlcmsOptions3[] =  array( "name" => "The following change will display the Widgets or Menus or Background or Header option in Appearance to users with the role of <strong>Editor</strong>. Please refer to the help tab to understand the consequences of enabling this option. <br /><br />",
	"type" => "message2") ;
 	 
$wlcmsOptions3[] = array( 	"name" => "Access To Appearance Menu",
	 	"desc" => "This will give the editor access to the appearance section. You can customise what they have access to.",
		"id" => $wlcmsShortName."_editor_template_access",
		"type" => "radio",
		"options" => array("1", "0"),
		"std" => '0' );
 
$wlcmsOptions3[] = array( "name" => "The Appearance Menu", "type" => "subsectionvars");

foreach($submenu['themes.php'] as $optId=> $optArray) // Build Dynamically the sub options for Appearance tab
{
	if ( $optArray[2] != 'theme-editor.php' ) { // Never want to allow Theme Editor Access

            $std='1';

            if($optArray[2] == 'widgets.php') { $std = $wlcms_show_widgets_legacy; }
            if($optArray[2] == 'nav-menus.php') { $std = $wlcms_show_menus_legacy; }

		$wlcmsOptions3[] = array( "name" => "Hide {$optArray[0]}",
			"desc" => "Removes the {$optArray[0]} option under the Appearance tab.",
			"id" => $wlcmsShortName."_subtemplate_hide_".$optId,
			"type" => "checkboxlast",
			"std" => $std);
	}
}
 

$wlcmsOptions3[] = array( "type" => "divclose" );
$wlcmsOptions3[] = array( "type" => "close" );



// To improve the config builder.
// $wlcmsOptions3 = array_merge($wlcmsOptions3);
$wlcmsOptions = array_merge( $wlcmsOptions, $wlcmsOptions2, $wlcmsOptions3 );
 
?>