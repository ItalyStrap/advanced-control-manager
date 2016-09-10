<div class="wrap">
	<h2><?php _e( 'Widget Logic options', 'widget-logic' ); ?></h2>
	<form method="POST" style="float:left; width:45%">
		<ul>
			<li><label for="widget_logic-options-filter" title="<?php _e( 'Adds a new WP filter you can use in your own code. Not needed for main Widget Logic functionality.', 'widget-logic' ); ?>">
				<input id="widget_logic-options-filter" name="widget_logic-options-filter" type="checkbox" value="checked" class="checkbox" <?php if ( isset( $this->options['widget_logic-options-filter'] ) ) { echo 'checked'; } ?>/>
				<?php _e( 'Add \'widget_content\' filter', 'widget-logic' ); ?>
				</label>
			</li>
			<li><label for="widget_logic-options-wp_reset_query" title="<?php _e( 'Resets a theme\'s custom queries before your Widget Logic is checked', 'widget-logic' ); ?>">
				<input id="widget_logic-options-wp_reset_query" name="widget_logic-options-wp_reset_query" type="checkbox" value="checked" class="checkbox" <?php if ( isset( $this->options['widget_logic-options-wp_reset_query'] ) ) { echo 'checked'; } ?> />
				<?php _e( 'Use \'wp_reset_query\' fix', 'widget-logic' ); ?>
				</label>
			</li>
			<li><label for="widget_logic-options-load_point" title="<?php _e( 'Delays widget logic code being evaluated til various points in the WP loading process', 'widget-logic' ); ?>"><?php _e( 'Load logic', 'widget-logic' ); ?>
				<select id="widget_logic-options-load_point" name="widget_logic-options-load_point" ><?php
				foreach ( $this->wl_load_points as $action => $action_desc ) {
					echo "<option value='".$action."'";
					if ( isset( $this->options['widget_logic-options-load_point'] ) && $action === $this->options['widget_logic-options-load_point'] ) {
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