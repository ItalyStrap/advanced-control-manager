<?php

function wlcms_plugin_deactivate() 
{
	
	include('admin.config.php');
	// Delete all the options that are defined in config 
	foreach($wlcmsOptions as $opt):
		if( isset($opt['id']) ): 
			delete_option($opt['id']); 
		endif;
	endforeach;


        wlcmsUpdateCaps(); // Restores other caps back to default
         
	// remove editor changes
	$role = get_role( 'editor');
	$role->remove_cap( 'switch_themes');
	$role->remove_cap( 'edit_theme_options');    
    
} // end :: function :: plugin_cleanup


?>