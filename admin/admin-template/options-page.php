<?php
/**
 * Display the options page
 * @package ItalyStrap
 */

?>
<div class="wrap">
	<form action='options.php' method='post'>
		
		<?php
		settings_fields( 'italystrap_options_group' );
		do_settings_sections( 'italystrap_options_group' );
		submit_button();
		?>
		
	</form>
</div>
