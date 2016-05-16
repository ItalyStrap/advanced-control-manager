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
