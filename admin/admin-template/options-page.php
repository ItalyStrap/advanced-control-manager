<?php
/**
 * Display the options page
 *
 * @package ItalyStrap
 */

?>
<div  id="tabs" class="wrap">
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
