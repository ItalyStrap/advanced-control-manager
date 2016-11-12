<div class="wrap">
	<div class="metabox-holder">
		<div class="postbox">
			<h3 class="hndle">
				<span>
					<?php _e( 'Export Settings', 'italystrap' ); ?>
				</span>
			</h3>
			<div class="inside">
				<form method="post">
					<?php

					$this->do_fields( 'export_settings' );
					wp_nonce_field( $this->args['name_action'], $this->args[ "export_nonce" ] );
					submit_button( __( 'Export' ), 'secondary', 'submit', false );

					?>
				</form>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<span>
					<?php _e( 'Import Settings', 'italystrap' ); ?>
				</span>
			</h3>
			<div class="inside">
				<form method="post" enctype="multipart/form-data">
					<?php
					$this->do_fields( 'import_file' );
					$this->do_fields( 'import_settings' );
					?>
					<!-- <p> -->
						<!-- <input type="file" name="italystrap_import_file"/> -->
					<!-- </p> -->
					<!-- <p> -->
						<!-- <input type="hidden" name="italystrap_action" value="import_settings" /> -->
					<!-- </p> -->
					<?php wp_nonce_field( $this->args['name_action'], $this->args[ "import_nonce" ] ); ?>
					<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>
		</div>
	</div>
</div>