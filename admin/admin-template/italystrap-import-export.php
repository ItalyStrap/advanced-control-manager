<div class="wrap">
	<div id="tabs-3" class="metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Export Settings', $this->plugin_slug ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', $this->plugin_slug ); ?></p>
				<form method="post">
					<p><input type="hidden" name="pn_action" value="export_settings" /></p>
					<p>
						<?php wp_nonce_field( 'pn_export_nonce', 'pn_export_nonce' ); ?>
						<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
		
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Import Settings', $this->plugin_slug ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', $this->plugin_slug ); ?></p>
				<form method="post" enctype="multipart/form-data">
					<p>
						<input type="file" name="pn_import_file"/>
					</p>
					<p>
						<input type="hidden" name="pn_action" value="import_settings" />
						<?php wp_nonce_field( 'pn_import_nonce', 'pn_import_nonce' ); ?>
						<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
</div>