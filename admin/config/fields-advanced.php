<?php
declare(strict_types=1);

$desc = __( 'This is the description of the field, you can change it in the configuration array.', 'italystrap' );

return [
	[
		'label'			=> __( 'Custom Title', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'custom',
		'type'			=> 'color',
		'value'			=> true,
		'sanitize'		=> 'sanitize_text_field',
	],
];
