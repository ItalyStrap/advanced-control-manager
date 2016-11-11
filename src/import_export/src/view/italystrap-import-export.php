<div class="wrap">
	<div class="metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Export Settings', 'italystrap' ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'italystrap' ); ?></p>
				<form method="post">
					<p><input type="hidden" name="italystrap_action" value="export_settings" /></p>
					<p>
						<?php wp_nonce_field( 'italystrap_export_nonce', 'italystrap_export_nonce' ); ?>
						<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Import Settings', 'italystrap' ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'italystrap' ); ?></p>
				<form method="post" enctype="multipart/form-data">
					<p>
						<input type="file" name="italystrap_import_file"/>
					</p>
					<p>
						<input type="hidden" name="italystrap_action" value="import_settings" />
						<?php wp_nonce_field( 'italystrap_import_nonce', 'italystrap_import_nonce' ); ?>
						<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
</div>