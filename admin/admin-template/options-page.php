<div class="wrap">
	<?php
	// if ( defined('ITALYSTRAP_THEME') ) {
	// 	echo "<p>string</p>";
	// }
	?>
		<?php settings_errors(); ?>

	<form action='options.php' method='post'>
		
		<?php
		settings_fields( 'italystrap_options_page' );
		do_settings_sections( 'italystrap_options_page' );
		submit_button();
		?>
		
	</form>
</div>