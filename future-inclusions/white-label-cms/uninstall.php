<?php

if( WP_UNINSTALL_PLUGIN ):

    global $wpdb;

    $x = "DELETE FROM $wpdb->options WHERE option_name LIKE 'wlcms_o_%' ";
    $wpdb->query($x);
  
    $role = get_role( 'editor' );
    $role->remove_cap( 'switch_themes' ); // Legacy
    $role->remove_cap( 'edit_themes' ); // Legacy
    $role->remove_cap( 'edit_theme_options' );

endif;

?>