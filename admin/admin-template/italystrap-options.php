<?php
/**
 * Display the options page
 *
 * @package ItalyStrap
 */

?>
<div  id="tabs" class="wrap">
	<div id="poststuff">
		<div id="post-body">
			<div class="postbox-container" style="width:74%">
				<form action='options.php' method='post'>
					<?php
					$this->create_nav_tab();
					/**
					 * Output nonce, action, and option_page fields for a settings page.
					 */
					settings_fields( 'italystrap_options_group' );
					/**
					 * Output settings sections and fields
					 */
					$this->do_settings_sections( 'italystrap_options_group' );
					/**
					 * Output a submit button
					 */
					submit_button();
					?>
					
				</form>
			</div>
			<div class="postbox-container postbox" style="width:25%;margin-left: 10px;margin-top: 45px;">
				<h3 class="hndle">
					<span>
						<?php esc_attr_e( 'ItalyStrap', 'italystrap' ); ?>
					</span>
				</h3>
				<div class="inside">
					<a itemprop="url" rel="home" title="Overclokk.net" href="http://www.overclokk.net/" class="navbar-brand"><img width="100px" class="logo" itemprop="image" heght="100px" data-src="http://www.overclokk.net/wp-content/themes/ItalyStrap-child/img/overclokk-new-logo-2014.svg" src="http://www.overclokk.net/wp-content/themes/ItalyStrap-child/img/overclokk-new-logo-2014.svg" alt="Logo overclokk"></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>
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