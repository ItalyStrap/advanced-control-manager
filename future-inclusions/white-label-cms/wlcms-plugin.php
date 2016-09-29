<?php
/*
Plugin Name: White Label CMS
Plugin URI: http://www.videousermanuals.com/white-label-cms/?utm_campaign=wlcms&utm_medium=plugin&utm_source=readme-txt
Description:  A plugin that allows you to brand WordPress CMS as your own
Version: 1.5.4
Author: www.videousermanuals.com
Author URI: http://www.videousermanuals.com/?utm_campaign=wlcms&utm_medium=plugin&utm_source=readme-txt
*/

define('WLCMS','1.5.4');

if ( ! defined('ABSPATH') ) {
        die('Please do not load this file directly.');
}


global $wpdbb_content_dir;

$wpdbb_content_dir = ( defined('WP_CONTENT_DIR') ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';

if ( ! defined('WP_BACKUP_DIR') ) {
        define('WP_BACKUP_DIR', $wpdbb_content_dir . '/');
}

//if(!function_exists('wp_get_current_user')) {
//    include(ABSPATH . "wp-includes/pluggable.php");
//}
 
include('includes/conditionals.php');

add_action('init', 'wlcms_check_for_login');
add_action('admin_menu', 'wlcms_add_menu');
add_action('admin_init', 'wlcms_add_init');
add_action('admin_menu', 'wlcms_add_admin'); 
add_action('admin_head', 'wlcms_custom_css');
add_action('login_head', 'wlcms_custom_login_logo');
add_action('admin_head', 'wlcms_hide_switch_theme');
add_action('admin_menu', 'wlcms_remove_default_post_metaboxes');
add_action('admin_init', 'wlcms_remove_admin_menus');
add_action('admin_menu', 'wlcms_remove_default_page_metaboxes');
add_action('admin_head', 'wlcms_dashboard_mod');
add_action('admin_head-media-upload-popup', 'wlcms_iframe_mod');
add_action('wp_before_admin_bar_render', 'wlcms_adminbar', 0);
add_action('wp_before_admin_bar_render', 'wlcms_admin_bar', 0);
add_action('wp_dashboard_setup', 'wlcms_add_dashboard_widget_custom' );

add_filter( 'admin_title', 'wlcms_admin_title', 10, 2);
add_filter( 'mce_css', 'wlcms_custom_editor_stylesheet' );

function wlcms_check_for_login()
{
    if( get_option('wlcms_o_enable_login_redirect') ):
        $segments = explode('/' ,  $_SERVER['REQUEST_URI'] );
        if ( $segments[ (count($segments) - 1) ] == 'login' ):
                wp_redirect( get_bloginfo('url') . '/wp-login.php' );
                exit;
        endif;
    endif;
}


function wlcms_dashboard_mod()
{
    global $current_screen, $wp_version;
    if( isset($current_screen) && ($current_screen->id == 'dashboard' ) ):

        if( get_option('wlcms_o_dashboard_override') || get_option('wlcms_o_dashboard_override') == '' ) :

            if ( get_option('wlcms_o_dashboard_override') != __('Dashboard') ) :

                $val = (get_option('wlcms_o_dashboard_override') == '' ? '&nbsp;' : get_option('wlcms_o_dashboard_override') );
                echo '<style type="text/css">#wpbody-content .wrap h2 { visibility: hidden; }</style>
                        <script type="text/javascript">
                                jQuery(document).ready(function($) {
                                        $("#wpbody-content .wrap h2:eq(0)").html("'.$val.'");
                                        $("#wpbody-content .wrap h2").css("visibility","visible");
                                });
                        </script>';
                        
                endif;
                        
        endif;

        if( get_option('wlcms_o_header_custom_logo') ):

            $background =  get_option('wlcms_o_header_custom_logo');

            if(!preg_match("@^https?://@", $background)){
       			 $background = get_bloginfo('stylesheet_directory').'/images/'.$background;
            }
        
            echo '<style type="text/css">
                            #icon-index {background:transparent;height:auto;width:auto;visibility: hidden;}
                            #dashboard-widgets-wrap {clear:both}
                    </style>';
            echo '<script type="text/javascript">
                            jQuery(document).ready(function($) {';
			if ( version_compare( $wp_version, '3.8-beta', '>=' ) )
                {
					echo '$(".index-php #wpbody-content .wrap h2:eq(0)").prepend("<span id=\"wlcms_dashboard_logo\"><img src=\"'.$background.'\" alt=\"\" /></span>");
						  $("#wlcms_dashboard_logo").css({"visibility":"visible", "display": "block", "float" : "left", "margin": "-2px 8px 0px 0px"});';
				}else{
					echo '$("#icon-index").html("<img src=\"'.$background.'\" alt=\"\" />");
							$("#icon-index").css({"visibility":"visible", "display": "block", "float" : "left", "margin": "7px 8px 0px 0px"});';
				}
				echo'});
                    </script>';
        endif;
    
    endif;
    
}

// set admin screen
function wlcms_add_menu() 
{
    $hook_name = add_options_page('White Label CMS','White Label CMS','manage_options','wlcms-plugin.php','wlcms_admin');
    add_action( "admin_print_scripts-$hook_name", 'wlcms_add_admin_scripts' );
}

function wlcmsUserCompare($needsToBe,$current)
{
    if($needsToBe == '0' || $needsToBe == '')
        return;
 
    $roles = array( 'administrator' => 25 , 'editor' => 20, 'author' => 15, 'contributor' => 10, 'subscriber' => 5 );

    $needsToBe = $roles[$needsToBe];
    $current = $roles[$current];

    if($current >= $needsToBe ):
        return true;
    else:
        return false;
    endif;
}

function wlcms_remove_others() {

if( get_option('wlcms_o_dashboard_admin') && current_user_can('activate_plugins') ) { return; }

global $wp_meta_boxes;
unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
unset($wp_meta_boxes['dashboard']['normal']['core']['custom_help_widget']);
unset($wp_meta_boxes['dashboard']['normal']['core']['my_dashboard_widget']);
 
}

function wlcms_remove_activity_panel()
{
	if( get_option('wlcms_o_dashboard_admin') && current_user_can('activate_plugins') ) { return; }
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
}

function wlcms_remove_right_now()
{
    if( get_option('wlcms_o_dashboard_admin') && current_user_can('activate_plugins') ) { return; }
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
}
 
function wlcms_remove_recent_comments() 
{
    if( get_option('wlcms_o_dashboard_admin') && current_user_can('activate_plugins') ) { return; }
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
}

function wlcms_output()
{
    global $userdata;
    $WP_Roles = new WP_Roles();
    $role_names = $WP_Roles->get_names();
    unset($role_names['administrator']);
    $all_caps = wlcms_get_all_caps_from_wp_roles();

    sort($all_caps);
    $output = "<div id=\"roles_capabilities\">";

    foreach($role_names as $role_id => $role_name)
    {
        $output .= "<div id=\"roles_$role_id\" class=\"edit_role_name\">";
        $output .= "<ul class=\"role_name_editor\">";

        foreach($all_caps as $capability)
        {
            $checked = isset($WP_Roles->roles[$role_id]['capabilities'][$capability])&&$WP_Roles->roles[$role_id]['capabilities']           [$capability]==1?'checked="checked"':'';
            $output .= "<li><input type=\"checkbox\" $checked name=\"ROLES[$role_id][$capability]\" value=1 />&nbsp;".ucfirst(str_replace("_"," ",$capability))."</li>&nbsp; ";
        }

        $output .= "</ul>"; $output .= "</div>";
    }
    $output .= "</div>";
    return $output;
}

function wlcms_get_all_caps_from_wp_roles()
{
    $WP_Roles = new WP_Roles();
    $role_names = $WP_Roles->get_names();
    $all_caps = array();
    if(count($WP_Roles->roles)>0)
    {
        foreach($WP_Roles->roles as $role_id => $row)
        {
            foreach($row['capabilities'] as $capability => $allowed)
            {
                $all_caps[$capability]=$capability;
            }
        }
    }
    return $all_caps;
}

function wlcms_roles_dropdown($params = array())
{
    $wp_roles = new WP_Roles();
    // remove unwanted roles
    if(!empty($params['remove_role']))
            unset($wp_roles->role_names[$params['remove_role']]);

    return $wp_roles->role_names;
}

function wlcms_adminbar() 
{
     global $wp_version;

    if(get_option('wlcms_o_dashboard_border')):
    echo '<style type="text/css"> .postbox-container .meta-box-sortables.empty-container, #side-sortables.empty-container{border:0;} </style>';
    endif;
    if( get_option('wlcms_o_hide_wp_adminbar') ):
        echo " \n\n <style type=\"text/css\">#wp-admin-bar-wp-logo { display:none; } #wpadminbar #wp-admin-bar-site-name > .ab-item:before { content: normal;}</style> \n\n";
    endif;

    if( get_option('wlcms_o_adminbar_custom_logo') ):
        $background = get_option('wlcms_o_adminbar_custom_logo');
        if(!preg_match("@^https?://@", $background))
            $background = get_bloginfo('stylesheet_directory').'/images/'.$background;

        echo '<script type="text/javascript"> jQuery(document).ready(function(){ ';
        echo  'jQuery("#wp-admin-bar-root-default").prepend(" <li id=\"wlcms_admin_logo\"> <span style=\"float:left;height:28px;line-height:28px;vertical-align:middle;text-align:center;width:28px\"><img src=\"'.$background.'\" width=\"16\" height=\"16\" alt=\"Login\" style=\"height:16px;width:16px;vertical-align:middle\" /> </span> </li> "); ';
		echo '  }); ';
        echo '</script> ';

    endif;

   $style = '<style type="text/css">';

        if(get_option('wlcms_o_header_custom_logo') != "")
        {
            $background = get_option('wlcms_o_header_custom_logo');

            if(!preg_match("@^https?://@", $background))
            $background = get_bloginfo('stylesheet_directory').'/images/'.$background;

            $style .= '#header-logo { background-image: url('.$background . ') !important; ';
            $css_width = get_option('wlcms_o_header_custom_logo_width');

            if ($css_width != '')
            {
                $style .= 'width: ' . get_option('wlcms_o_header_custom_logo_width') . 'px; ';
            }
            else
            {
                if (( version_compare( $wp_version, '3.2', '>=' ) ) && (!empty($customHFHeight)))
                {
                    $style .= 'height: '.$customHFHeight.'px; ';
                }
            }
            $style .= '} ';
        }

        if (( version_compare( $wp_version, '3.2', '>=' ) ) && (!empty($customHFHeight))) {
                $style .= '  #wphead { height: '.$customHFHeight.'px; }
                                #wphead h1 { font-size: 22px; padding: 10px 8px 5px; }
                                #header-logo { height: 32px; }
                                #user_info { padding-top: 8px }
                                #user_info_arrow { margin-top: 8px; }
                                #user_info_links { top: 8px; }
                                #footer p { padding-top: 15px; }
                                #wlcms-footer-container { padding-top: 10px; line-height: 30px;} '."\n";
        }

        if (( version_compare( $wp_version, '3.2', '<' ) ) && (empty($customHFHeight))) {
               $style .= '#wlcms-footer-container {     padding-top: 10px;  line-height: 30px; }';
        }

        if (get_option('wlcms_o_header_logo_link') == 1) {
                $style .= '#site-heading { display: none; } ';
        }

    $style .= '</style>';

    echo $style;
    

    if (get_option('wlcms_o_header_logo_link') == 1) {
            echo '<script type="text/javascript">';
            echo "jQuery(function($){ $('#header-logo').wrap('<a href=\"" . site_url() . "\" alt=\"" . get_bloginfo('name') . "\" title=\"" . get_bloginfo('name') . "\">'); });";
            echo '</script>';
    }
}

// add footer logo
function wlcms_remove_footer_admin() {
    $footer_logo = get_option('wlcms_o_footer_custom_logo');
    if($footer_logo)
        if(!preg_match("@^https?://@", $footer_logo))
                $footer_logo = get_bloginfo('stylesheet_directory').'/images/'.$footer_logo;

        echo '<span id="footer-thankyou">';
        if (get_option('wlcms_o_developer_url')) {

                echo '<a target="_blank" href="' . get_option('wlcms_o_developer_url') . '">';

                if (get_option('wlcms_o_footer_custom_logo_width')) {
                echo '<img style="width:' . get_option('wlcms_o_footer_custom_logo_width') . 'px;" ';
            } else {
                echo '<img ';
            }
           echo ' src="'.$footer_logo. '" id="wlcms-footer-logo"> </a> <span> <a target="_blank" href="' . get_option('wlcms_o_developer_url') . '">' . stripslashes(get_option('wlcms_o_developer_name')) . '</a> </span>';
        } else {
            if (get_option('wlcms_o_footer_custom_logo_width')) {
                echo '<img style="width:' . get_option('wlcms_o_footer_custom_logo_width') . 'px;" ';
            } else {
                echo '<img ';
            }        
            echo ' src="'.$footer_logo . '" id="wlcms-footer-logo"> <span>' . stripslashes(get_option('wlcms_o_developer_name')).'</span>';
        }
        echo '</span>';
}

function wlcms_developer_link()
{
    echo '<span id="footer-thankyou">';
    echo ( get_option('wlcms_o_developer_url') ? '<a target="_blank" href="' . get_option('wlcms_o_developer_url') . '">' : '' );
    echo stripslashes(get_option('wlcms_o_developer_name'));
    echo ( get_option('wlcms_o_developer_url') ? '</a>' : '' );
    echo '</span>';
}


// custom logo login
function wlcms_custom_login_logo()
{
    wp_print_scripts( array( 'jquery' ) );

    $login_custom_logo = get_option('wlcms_o_login_custom_logo');

    if(!preg_match("@^https?://@", $login_custom_logo))
            $login_custom_logo = get_bloginfo('stylesheet_directory').'/images/'.$login_custom_logo;

    echo '<style type="text/css">';
    echo stripslashes( get_option('wlcms_o_login_bg_css') );

    if (get_option('wlcms_o_login_custom_logo')):
        echo ' .login h1 a { display:all; background: url('.$login_custom_logo . ') no-repeat bottom center !important; margin-bottom: 10px; background-size: auto; } ';

        if(get_option('wlcms_o_loginbg_white') ):
                echo ' body.login {background: #fff } ';
        endif;
		echo '.login h1 a { width :auto!important;} ';
        echo '</style> ';
			;

        echo '<script type="text/javascript">
                jQuery(document).ready(function()
               {
                    jQuery(\'#login h1 a\').attr(\'title\',\'' . get_bloginfo('name') . '\');
                    jQuery(\'#login h1 a\').attr(\'href\',\'' . get_bloginfo('url') . '\');
               });
        </script>';

    elseif( get_option('wlcms_o_login_bg_css') ):
        
        echo  stripslashes( get_option('wlcms_o_login_bg_css') );
        echo '</style> ';
    else:
        echo '</style> ';
    endif;

    if(get_option('wlcms_o_login_bg_css')):
        echo '<script type="text/javascript"> jQuery(document).ready(function(){ jQuery("#login").wrap("<div id=\'wlcms-login-wrapper\'></div>"); }); </script> ';
    endif;
    
}
 
function wlcms_custom_dashboard_help()
{
    echo stripslashes(get_option('wlcms_o_welcome_text'));
}
function wlcms_custom_dashboard_help_new()
{
    echo stripslashes(get_option('wlcms_o_welcome_text1'));
}

function wlcms_add_dashboard_widget_custom() 
{
    if ( get_option('wlcms_o_show_rss_widget') ):
        $img = ( get_option('wlcms_o_rss_logo') ? get_option('wlcms_o_rss_logo') : '' );
        if($img)
            $img = '<img src="'.$img.'" height="16" width="16" alt="Logo" style="padding-right:5px;vertical-align:bottom;"/>';
        
        wp_add_dashboard_widget('wlcms_rss_box', $img . get_option('wlcms_o_rss_title'), 'wlcms_rss_box');
    endif;

    if ( get_option('wlcms_o_show_welcome') ):
        if ( wlcmsUserCompare( strtolower(get_option('wlcms_o_welcome_visible_to')),  strtolower( wlcms_get_current_user_role() ) ) ):
            wp_add_dashboard_widget('custom_help_widget', get_option('wlcms_o_welcome_title'), 'wlcms_custom_dashboard_help');
        endif;

        if ( wlcmsUserCompare( strtolower(get_option('wlcms_o_welcome_visible_to1')),  strtolower( wlcms_get_current_user_role() ) ) ):
            wp_add_dashboard_widget('my_dashboard_widget', get_option('wlcms_o_welcome_title1'), 'wlcms_custom_dashboard_help_new');
        endif;
    endif;
}

function remove_footer_version()
{
    return '';
}

function wlcms_rss_box()
{
    if(!get_option('wlcms_o_rss_value'))
        return;
    
    include_once(ABSPATH . WPINC . '/feed.php');
    $num_items = get_option('wlcms_o_rss_num_items');
    $rss = fetch_feed( get_option('wlcms_o_rss_value') );

    if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly
        $maxitems = $rss->get_item_quantity($num_items);
        $rss_items = $rss->get_items(0, $maxitems);
    endif;

    if( get_option('wlcms_o_rss_intro_html') ):
        echo wpautop ( stripslashes( get_option('wlcms_o_rss_intro_html') ) );
    endif; 
?>

<ul>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    // Loop through each feed item and display each item as a hyperlink.

    foreach ( $rss_items as $item ) : ?>
    <li>
        <strong> <a href='<?php echo esc_url( $item->get_permalink() ); ?>'
        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>' target='_blank'>
        <?php echo esc_html( $item->get_title() ); ?></a> </strong> <br />

        <?php if(get_option('wlcms_o_rss_show_intro') == 'yes'): 
            echo $item->get_content();
        endif; ?>
        
    </li>
    <?php endforeach; ?>
</ul>

<?php
   
}

function wlcms_hide_wp_version()
{
    echo '<style type="text/css">#wp-version-message { display: none;}</style>';
}
    
function wlcms_get_current_user_role() {
    global $wp_roles;
    $current_user = wp_get_current_user();
    $roles = $current_user->roles;
    $role = array_shift($roles);
    return isset($wp_roles->role_names[$role]) ? $wp_roles->role_names[$role] : false;
}

function wlcms_custom_editor_stylesheet($url)
{
    
    if( get_option('wlcms_o_welcome_stylesheet')):
        $url = get_stylesheet_directory_uri() . '/' ;
        $url .= get_option('wlcms_o_welcome_stylesheet');
    endif;

    return $url;
}

function wlcms_hide_switch_theme()
{
    if(!current_user_can( 'manage_options' ) ):
       echo '<style type="text/css">
                    #dashboard_right_now .versions p, #dashboard_right_now .versions #wp-version-message  { display: none; }
              </style>
       ';
    endif;
}

function wlcms_admin_title($admin_title)
{
    if( get_option('wlcms_o_admin_page_title') ):
        return str_replace (
                "&#8212; WordPress",
                get_option('wlcms_o_admin_page_title'),
                $admin_title
        );
    else:
        return $admin_title;
    endif;
}

function wlcms_admin_bar()
{
    // Show all to admin
    if (current_user_can('activate_plugins'))
        return;

    global $wp_admin_bar;

    if (get_option('wlcms_o_hide_posts'))
        $wp_admin_bar->remove_menu('new-post', 'new-content');
    if (get_option('wlcms_o_hide_comments'))
        $wp_admin_bar->remove_menu('comments');
    if (get_option('wlcms_o_hide_media'))
        $wp_admin_bar->remove_menu('media');

    $wp_admin_bar->remove_menu('appearance');

}

function wlcms_remove_admin_menus () {
    
    global $menu, $submenu;

    $exclude[0] = '';

    if (get_option('wlcms_o_hide_posts'))
        array_push($exclude,__('Posts','default'));
    if (get_option('wlcms_o_hide_media'))
        array_push($exclude,__('Media','default'));
    if (get_option('wlcms_o_hide_comments'))
        array_push($exclude,__('Comments','default'));
    if (get_option('wlcms_o_hide_tools'))
        array_push($exclude,__('Tools','default'));
    if (get_option('wlcms_o_hide_profile'))
        array_push($exclude,__('Profile','default'));


   

    unset($exclude[0]);

//    print_r($exclude);die;

    if (sizeof($exclude) > 0):
        if (!current_user_can('activate_plugins')):
            if( isset($menu) && is_array($menu) ):
                foreach($menu as $mId=>$menuArray):
                    $tmp = explode(' ',$menuArray[0]);
                    if(in_array( $tmp[0] , $exclude )):
                            unset($menu[$mId]);
                    endif;
                endforeach;
            endif;
        endif;
    endif;

   if(isset($submenu['themes.php'])):
        if (!current_user_can('activate_plugins')):
            foreach( $submenu['themes.php'] as $k=>$v):
                if(get_option('wlcms_o_subtemplate_hide_'.$k) ):
                        unset($submenu['themes.php'][$k]);
                endif;
            endforeach;
        endif;
    endif;

}   
 
function wlcms_custom_pages_columns($defaults) 
{
    if (get_option('wlcms_o_page_meta_box_comments'))
            unset($defaults['comments']);
    if (get_option('wlcms_o_page_meta_box_author'))
            unset($defaults['author']);
    return $defaults;
}

function wlcms_remove_default_page_metaboxes()
{
    add_filter('manage_pages_columns', 'wlcms_custom_pages_columns');

    if (get_option('wlcms_o_page_meta_box_custom'))
        remove_meta_box( 'postcustom','page','normal' );
    if (get_option('wlcms_o_page_meta_box_author'))
        remove_meta_box( 'authordiv','page','normal' );
    if (get_option('wlcms_o_page_meta_box_discussions'))
        remove_meta_box( 'commentstatusdiv','page','normal' );
    if (get_option('wlcms_o_page_meta_box_slug'))
        remove_meta_box( 'slugdiv','page','normal' );
    if (get_option('wlcms_o_page_meta_box_revisions'))
        remove_meta_box( 'revisionsdiv','page','normal' );
    if (get_option('wlcms_o_page_meta_box_page'))
        remove_meta_box( 'pageparentdiv','page','normal' );
    if (get_option('wlcms_o_page_meta_box_comments'))
        remove_meta_box( 'commentsdiv','page','normal' );
}

function wlcms_custom_post_columns($defaults) 
{
    if (get_option('wlcms_o_post_meta_box_comments'))
        unset($defaults['comments']);
    if (get_option('wlcms_o_post_meta_box_author'))
        unset($defaults['author']);
    if (get_option('wlcms_o_post_meta_box_categories'))
        unset($defaults['categories']);
    return $defaults;
}

function wlcms_manage_my_category_columns($defaults)
{
    if (get_option('wlcms_o_post_meta_box_slug'))
            unset($defaults['slug']);
    return $defaults;
}

function wlcms_remove_default_post_metaboxes() 
{
    add_filter('manage_posts_columns', 'wlcms_custom_post_columns');
    add_filter('manage_edit-post_tag_columns','wlcms_manage_my_category_columns');
    add_filter('manage_edit-category_columns','wlcms_manage_my_category_columns');

    if (get_option('wlcms_o_post_meta_box_custom'))
        remove_meta_box( 'postcustom','post','normal' );
    if (get_option('wlcms_o_post_meta_box_excerpt'))
        remove_meta_box( 'postexcerpt','post','normal' );
    if (get_option('wlcms_o_post_meta_box_discussions'))
        remove_meta_box( 'commentstatusdiv','post','normal' );
    if (get_option('wlcms_o_post_meta_box_send'))
        remove_meta_box( 'trackbacksdiv','post','normal' );
    if (get_option('wlcms_o_post_meta_box_slug'))
        remove_meta_box( 'slugdiv','post','normal' );
    if (get_option('wlcms_o_post_meta_box_author'))
        remove_meta_box( 'authordiv','post','normal');
    if (get_option('wlcms_o_post_meta_box_revisions'))
        remove_meta_box( 'revisionsdiv','post','normal' );
    if (get_option('wlcms_o_post_meta_box_tags'))
        remove_meta_box( 'tagsdiv-post_tag','post','normal' );
    if (get_option('wlcms_o_post_meta_box_categories'))
        remove_meta_box( 'categorydiv','post','normal' );
    if (get_option('wlcms_o_post_meta_box_comments'))
        remove_meta_box( 'commentsdiv','post','normal' );
}

function wlcms_add_admin() 
{
    
    global $wlcmsThemeName, $wlcmsShortName, $menu, $submenu;

    if ( isset($_GET['page']) && $_GET['page'] == 'wlcms-plugin.php')
     {
        if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] )
        {
            add_action('admin_init', 'wlcmsSave');
        }
        elseif( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] )
        {
            add_action('admin_init', 'wlcmsReset');
        }
        elseif( isset($_REQUEST['action']) && 'export' == $_REQUEST['action'] )
        {
            add_action('admin_init', 'wlcmsExport');
        }
        elseif( isset($_REQUEST['action']) && 'import' == $_REQUEST['action'] )
        {
			// Only run the import function if we pass a nonce
			if( check_admin_referer( 'wlcms_import' ) ) {
            	add_action('admin_init', 'wlcmsImport');
			} else {
				wp_die( 'Failed the WLCMS Import Nonce' );
			}
        }
    }
}

function wlcmsImport()
{
    if ($_FILES['wlcms_import']['error'] == UPLOAD_ERR_OK
            && is_uploaded_file($_FILES['wlcms_import']['tmp_name'])):

        $import = file_get_contents($_FILES['wlcms_import']['tmp_name']);
        $import = unserialize($import);

        if( ! is_array($import) )
        {   
            // Not an array some how? Rather than pressing on, just return.
            header("Location: admin.php?page=wlcms-plugin.php&import=false&error=noArray");
        }

        // Reset all WLCMS settings;
        wlcmsReset(true);

        $site_url = get_bloginfo('url');

		foreach ( $import as $name => $value ) {

			// If the value includes this shortcode, replace it.
			$val = str_replace( '{SITEURL}', $site_url, $value );

			// Check that our option key starts with WLCMS
			if ( strpos( $name, 'wlcms_o' ) === 0 ) {
				add_option( $name, $val );
			} else {
				wp_die( __('<strong>Error!</strong> During the import process we almost imported a non White Label CMS setting - please ensure you uploaded the correct file and try again.' ) );
			}
		}

        header("Location: admin.php?page=wlcms-plugin.php&import=true");
        exit;

    endif;

    header("Location: admin.php?page=wlcms-plugin.php&import=false");
    die;
}

function wlcmsExport()
{
    global $wpdb;
    $export=array();
    $site_url = get_bloginfo('url');
    
    // Get all WLCMS vals from options table
    $results = $wpdb->get_results("select * from $wpdb->options where option_name like 'wlcms_o_%'");

    // Are there any options to grab?
    if($results):
        // Loop through results and prep array.
        foreach($results as $result):

            if($result->option_value=='') continue; // Ignore empty values

            // Does the blog URL exist in this option value?
            if ( strpos( $result->option_value ,$site_url) !== false ):

                // Do we want to keep the full URL?
                if( $_GET['wlcms_export_imgs'] == 'yes' ):
                    $export[$result->option_name] = $result->option_value;

                // If we do a partial - that means we str replace the SITEURL.
                elseif( $_GET['wlcms_export_imgs'] == 'partial' ):
                    $val = str_replace( $site_url, '{SITEURL}', $result->option_value );
                    $export[$result->option_name] = $val;

                endif;

                // Otherwise they will have selected no, in which case, we dont
                // add them to the $export array.

            else:
            
                // Add non-url matched items to our $export array
                $export[$result->option_name] = $result->option_value;

            endif;
           
        endforeach; 
        
    endif;
    
    $export=serialize($export);

    header("Content-Disposition: attachment; filename=\"wlcms-backup-" . date('Y-m-d') . ".txt\"");
    header("Content-Type: application/force-download");
    header("Content-Length: " . strlen($export));
    header("Connection: close");
    echo $export;
    exit;
}

function wlcmsSave()
{
    check_admin_referer( 'wlcms-update_settings' );
    
    include('includes/admin.config.php');

    update_option('wlcms_o_ver', WLCMS);
    foreach ($wlcmsOptions as $value):
        if( isset( $value['id'] ) && isset( $_REQUEST[ $value['id'] ] ) ):
            update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
        elseif( isset( $value['id'] ) && (!isset($_REQUEST[$value['id']])) ):
            delete_option( $value['id'] );
        endif;
    endforeach;

    wlcmsUpdateCaps();

    if($_REQUEST['wlcms_o_editor_template_access'] == "1")
    {
        $role = get_role( 'editor' );
        $role->add_cap( 'edit_theme_options' );
    }
    elseif($_REQUEST['wlcms_o_editor_template_access'] == "0")
    {
        $role = get_role( 'editor' );
        $role->remove_cap( 'switch_themes' ); // Legacy
        $role->remove_cap( 'edit_themes' ); // Legacy
        $role->remove_cap( 'edit_theme_options' );
    }

    header("Location: admin.php?page=wlcms-plugin.php&saved=true");
    die;
}

function wlcmsReset($return=false)
{
    include('includes/admin.config.php');
    foreach ($wlcmsOptions as $value):
        if(isset($value['id'])):
            delete_option( $value['id'] );
        endif;
    endforeach;

    if($return) return; 

    header("Location: admin.php?page=wlcms-plugin.php&reset=true");
    die;
}

function wlcmsUpdateCaps()
{
    $config = array(
      /*  array(
            'key'   =>  'wlcms_o_hide_posts',
            'caps'  =>  array('edit_posts', 'manage_categories')
        ),*/
        array(
            'key'   =>  'wlcms_o_hide_pages',
            'caps'  =>  array('edit_pages')
        ),
        array(
            'key'   =>  'wlcms_o_hide_media',
            'caps'  =>  array('upload_files')
        ),
        array(
            'key'   =>  'wlcms_o_hide_links',
            'caps'  =>  array('manage_links')
        )
    );

    foreach($config as $opts):
        if(isset( $_POST[ $opts['key'] ] )):

            if( $opts['key'] == 'wlcms_o_hide_media')
                continue;

            $role = get_role( 'editor' );
            foreach($opts['caps'] as $cap):
                if(is_object($role) && method_exists($role,'add_cap'))
                {
                    $role->remove_cap( $cap );
                }

            endforeach;
        else:
            $role = get_role( 'editor' );
            foreach($opts['caps'] as $cap):
                if(is_object($role) && method_exists($role,'add_cap'))
                {
                    $role->add_cap( $cap );
                }
                
            endforeach;
        endif;
    endforeach;
}



function wlcms_add_init() 
{
    global $wlcmsThemeName, $wlcmsShortName, $menu, $submenu;

if(! get_option('wlcms_o_welcome_title') )
{
        include('includes/admin.config.php');
        foreach ($wlcmsOptions as $value):
                if ( isset($value['id']) && $value['id'] != '' && isset($value['std']) ):
                        add_option( $value['id'], $value['std']  );
                endif;
        endforeach;
 }

}
function wlcms_add_admin_scripts() {
    wp_enqueue_script('wlcms-script', plugins_url('scripts/wlcms_script.js', __FILE__), array('media-upload'), WLCMS);
    wp_enqueue_style('thickbox');
    wp_enqueue_style('wlcms-style', plugins_url('css/wlcms_style.css', __FILE__), false, WLCMS);
}
function wlcms_admin() 
{
    global $menu, $submenu;
    $i=0;
    include('includes/admin.config.php');

    if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong> Settings saved.</strong></p></div>';
    if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong> Settings reset.</strong></p></div>';
    if ( isset($_REQUEST['import']) && $_REQUEST['import'] == 'true' ) echo '<div id="message" class="updated fade"><p><strong> Your Import has been completed.</strong></p></div>';

    require('includes/admin.view.php');
}

function wlcms_iframe_mod()
{
    if( (isset($_GET['wlcms']) ) ||  isset( $_GET["post_id"] ) && ( $_GET['post_id'] == '0' ) )
    { ?>
    <style type="text/css">
        #media-upload-header #sidemenu li#tab-type_url,
        #media-upload-header #sidemenu li#tab-gallery {display: none;}
        #media-items tr.url,#media-items tr.align,#media-items tr.image_alt,
        #media-items tr.post_title,  #media-items tr.image-size,
        #media-items tr.post_excerpt,#media-items tr.post_content,
        #media-items tr.image_alt p, #media-items table thead input.button,
        #media-items table thead img.imgedit-wait-spin,
        #media-items tr.submit a.wp-post-thumbnail {display: none;}
    </style>
    <script type="text/javascript">
    (function($){
    $(document).ready(function(){
        $('#media-items').bind('DOMNodeInserted',function(){
            $('input[value="Insert into Post"]').each(function(){
                    $(this).attr('value','Use This Image');
            });
        });
    });

    })(jQuery);
    </script>
<?php
    }
}
?>
