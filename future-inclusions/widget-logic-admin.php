<?php
/*
Plugin Name:    Widget Logic
Plugin URI:     http://wordpress.org/extend/plugins/widget-logic/
Description:    Control widgets with WP's conditional tags is_home etc
Version:        0.57
Author:         Alan Trewartha
Author URI:     http://freakytrigger.co.uk/author/alan/

Text Domain:   widget-logic
Domain Path:   /languages/
*/

global $wl_options;

$wl_load_points = array(
	'plugins_loaded'    => __( 'when plugin starts (default)', 'widget-logic' ),
	'after_setup_theme' => __( 'after theme loads', 'widget-logic' ),
	'wp_loaded'         => __( 'when all PHP loaded', 'widget-logic' ),
	'wp_head'           => __( 'during page header', 'widget-logic' ),
);

if ( ( ! $wl_options = get_option( 'widget_logic' )) || ! is_array( $wl_options ) ) {
	$wl_options = array();
}

if ( is_admin() ) {

	add_filter( 'widget_update_callback', 'widget_logic_ajax_update_callback', 10, 3 ); 				// widget changes submitted by ajax method.
	add_action( 'sidebar_admin_setup', 'widget_logic_expand_control' );								// before any HTML output save widget changes and add controls to each widget on the widget admin page.
	add_action( 'sidebar_admin_page', 'widget_logic_options_control' );								// add Widget Logic specific options on the widget admin page.
}

// wp-admin/widgets.php explicitly checks current_user_can('edit_theme_options')
// which is enough security, I believe. If you think otherwise please contact me
// CALLED VIA 'widget_update_callback' FILTER (ajax update of a widget)
function widget_logic_ajax_update_callback( $instance, $new_instance, $this_widget ) {
	global $wl_options;
	$widget_id = $this_widget->id;
	if ( isset( $_POST[ $widget_id.'-widget_logic' ] ) ) {
		$wl_options[ $widget_id ] = trim( $_POST[ $widget_id.'-widget_logic' ] );
		update_option( 'widget_logic', $wl_options );
	}
	return $instance;
}


// CALLED VIA 'sidebar_admin_setup' ACTION
// adds in the admin control per widget, but also processes import/export
function widget_logic_expand_control() {
	global $wp_registered_widgets, $wp_registered_widget_controls, $wl_options;

	// EXPORT ALL OPTIONS
	if ( isset( $_GET['wl-options-export'] ) ) {
		header( 'Content-Disposition: attachment; filename=widget_logic_options.txt' );
		header( 'Content-Type: text/plain; charset=utf-8' );

		echo "[START=WIDGET LOGIC OPTIONS]\n";
		foreach ( $wl_options as $id => $text ) {
			echo "$id\t".json_encode( $text )."\n"; }
		echo '[STOP=WIDGET LOGIC OPTIONS]';
		exit;
	}

	// IMPORT ALL OPTIONS
	if ( isset( $_POST['wl-options-import'] ) ) {	if ( $_FILES['wl-options-import-file']['tmp_name'] ) {	$import = split( "\n",file_get_contents( $_FILES['wl-options-import-file']['tmp_name'], false ) );
			if ( array_shift( $import ) == '[START=WIDGET LOGIC OPTIONS]' && array_pop( $import ) == '[STOP=WIDGET LOGIC OPTIONS]' ) {	foreach ( $import as $import_option ) {	list($key, $value) = split( "\t",$import_option );
					$wl_options[ $key ] = json_decode( $value );
			}
				$wl_options['msg'] = __( 'Success! Options file imported','widget-logic' );
			} else {	$wl_options['msg'] = __( 'Invalid options file','widget-logic' );
			}
	} else { 			$wl_options['msg'] = __( 'No options file provided','widget-logic' ); }

		update_option( 'widget_logic', $wl_options );
		wp_redirect( admin_url( 'widgets.php' ) );
		exit;
	}

	// ADD EXTRA WIDGET LOGIC FIELD TO EACH WIDGET CONTROL
	// pop the widget id on the params array (as it's not in the main params so not provided to the callback)
	foreach ( $wp_registered_widgets as $id => $widget ) {	// controll-less widgets need an empty function so the callback function is called.
		if ( ! isset( $wp_registered_widget_controls[ $id ] ) ) {
			wp_register_widget_control( $id,$widget['name'], 'widget_logic_empty_control' ); }
		$wp_registered_widget_controls[ $id ]['callback_wl_redirect'] = $wp_registered_widget_controls[ $id ]['callback'];
		$wp_registered_widget_controls[ $id ]['callback'] = 'widget_logic_extra_control';
		array_push( $wp_registered_widget_controls[ $id ]['params'],$id );
	}

	// UPDATE WIDGET LOGIC WIDGET OPTIONS (via accessibility mode?)
	if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) ) {	foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id ) {
			if ( isset( $_POST[ $widget_id.'-widget_logic' ] ) ) {
				$wl_options[ $widget_id ] = trim( $_POST[ $widget_id.'-widget_logic' ] ); }
	}

		// clean up empty options (in PHP5 use array_intersect_key)
		$regd_plus_new = array_merge(array_keys( $wp_registered_widgets ),array_values( (array) $_POST['widget-id'] ),
		array( 'widget_logic-options-filter', 'widget_logic-options-wp_reset_query', 'widget_logic-options-load_point' ));
	foreach ( array_keys( $wl_options ) as $key ) {
		if ( ! in_array( $key, $regd_plus_new, true ) ) {
			unset( $wl_options[ $key ] ); }
	}
	}

	// UPDATE OTHER WIDGET LOGIC OPTIONS
	// must update this to use http://codex.wordpress.org/Settings_API
	if ( isset( $_POST['widget_logic-options-submit'] ) ) {	$wl_options['widget_logic-options-filter'] = $_POST['widget_logic-options-filter'];
		$wl_options['widget_logic-options-wp_reset_query'] = $_POST['widget_logic-options-wp_reset_query'];
		$wl_options['widget_logic-options-load_point'] = $_POST['widget_logic-options-load_point'];
	}

	update_option( 'widget_logic', $wl_options );

}




/**
 * // CALLED VIA 'sidebar_admin_page' ACTION
 * // output extra HTML
 * // To update using http://codex.wordpress.org/Settings_API asap.
 */
function widget_logic_options_control() {
	global $wp_registered_widget_controls, $wl_options, $wl_load_points;

	if ( isset( $wl_options['msg'] ) ) {	if ( substr( $wl_options['msg'],0,2 ) == 'OK' ) {
			echo '<div id="message" class="updated">';
	} else { 			echo '<div id="message" class="error">'; }
		echo '<p>Widget Logic – '.$wl_options['msg'].'</p></div>';
		unset( $wl_options['msg'] );
		update_option( 'widget_logic', $wl_options );
	}

	?><div class="wrap">
		
		<h2><?php _e( 'Widget Logic options', 'widget-logic' ); ?></h2>
		<form method="POST" style="float:left; width:45%">
			<ul>
				<li><label for="widget_logic-options-filter" title="<?php _e( 'Adds a new WP filter you can use in your own code. Not needed for main Widget Logic functionality.', 'widget-logic' ); ?>">
					<input id="widget_logic-options-filter" name="widget_logic-options-filter" type="checkbox" value="checked" class="checkbox" <?php if ( isset( $wl_options['widget_logic-options-filter'] ) ) { echo 'checked'; } ?>/>
					<?php _e( 'Add \'widget_content\' filter', 'widget-logic' ); ?>
					</label>
				</li>
				<li><label for="widget_logic-options-wp_reset_query" title="<?php _e( 'Resets a theme\'s custom queries before your Widget Logic is checked', 'widget-logic' ); ?>">
					<input id="widget_logic-options-wp_reset_query" name="widget_logic-options-wp_reset_query" type="checkbox" value="checked" class="checkbox" <?php if ( isset( $wl_options['widget_logic-options-wp_reset_query'] ) ) { echo 'checked'; } ?> />
					<?php _e( 'Use \'wp_reset_query\' fix', 'widget-logic' ); ?>
					</label>
				</li>
				<li><label for="widget_logic-options-load_point" title="<?php _e( 'Delays widget logic code being evaluated til various points in the WP loading process', 'widget-logic' ); ?>"><?php _e( 'Load logic', 'widget-logic' ); ?>
					<select id="widget_logic-options-load_point" name="widget_logic-options-load_point" ><?php
					foreach ( $wl_load_points as $action => $action_desc ) {
						echo "<option value='".$action."'";
						if ( isset( $wl_options['widget_logic-options-load_point'] ) && $action === $wl_options['widget_logic-options-load_point'] ) {
							echo ' selected '; }
						echo '>'.$action_desc.'</option>';
					}
						?>
					</select>
					</label>
				</li>
			</ul>
			<?php submit_button( __( 'Save WL options', 'widget-logic' ), 'button-primary', 'widget_logic-options-submit', false ); ?>

		</form>
		<form method="POST" enctype="multipart/form-data" style="float:left; width:45%">
			<a class="submit button" href="?wl-options-export" title="<?php _e( 'Save all WL options to a plain text config file', 'widget-logic' ); ?>"><?php _e( 'Export options', 'widget-logic' ); ?></a><p>
			<?php submit_button( __( 'Import options', 'widget-logic' ), 'button', 'wl-options-import', false, array( 'title' => __( 'Load all WL options from a plain text config file', 'widget-logic' ) ) ); ?>
			<input type="file" name="wl-options-import-file" id="wl-options-import-file" title="<?php _e( 'Select file for importing', 'widget-logic' ); ?>" /></p>
		</form>

	</div>

	<?php
}

// Added to widget functionality in 'widget_logic_expand_control' (above).
function widget_logic_empty_control() {}



// Added to widget functionality in 'widget_logic_expand_control' (above).
function widget_logic_extra_control() {
	global $wp_registered_widget_controls, $wl_options;

	$params = func_get_args();
	$id = array_pop( $params );

	// Go to the original control function.
	$callback = $wp_registered_widget_controls[ $id ]['callback_wl_redirect'];
	if ( is_callable( $callback ) ) {
		call_user_func_array( $callback, $params ); }

	$value = ! empty( $wl_options[ $id ] ) ? htmlspecialchars( stripslashes( $wl_options[ $id ] ),ENT_QUOTES ) : '';

	// Dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface.
	$id_disp = $id;
	if ( ! empty( $params ) && isset( $params[0]['number'] ) ) {	$number = $params[0]['number'];
		if ( -1 === $number ) {
			$number = '__i__';
			$value = '';}
		$id_disp = $wp_registered_widget_controls[ $id ]['id_base'].'-'.$number;
	}

	// Output our extra widget logic field.
	echo "<p><label for='" . $id_disp . "-widget_logic'>" . __( 'Widget logic:','widget-logic' ). " <textarea class='widefat' type='text' name='" . $id_disp . "-widget_logic' id='" . $id_disp . "-widget_logic' >" . $value . '</textarea></label></p>';
}
