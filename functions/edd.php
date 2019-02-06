<?php

add_filter( 'italystrap_theme_updater_config', function ( array $edd_config ) {

	/**
	 * EDD configuration for this theme
	 *
	 * @link italystrap.com
	 * @since 4.0.0
	 *
	 * @package ItalyStrap
	 */

	//$item_name = ITALYSTRAP_THEME_NAME;
	$item_name = 'ItalyStrap Theme Framework';
	//$theme_slug = strtolower( $item_name );
//	$theme_slug = 'italystrap';
	$theme_slug = get_template();

	$edd_config[] = [
		'config'	=> [
			'item_name'      => $item_name, // Name of theme
			'theme_slug'     => $theme_slug, // Theme slug
			'version'        => wp_get_theme( $theme_slug )->display( 'Version', false ), // The current version of this theme
			'author'         => wp_get_theme( $theme_slug )->display( 'Author', false ), // The author of this theme
			'download_id'    => '', // Optional, used for generating a license renewal link
			'renew_url'      => '', // Optional, allows for a custom license renewal link
			'beta'           => get_theme_mod( 'beta', true ), // Optional, set to true to opt into beta versions
		],
		'strings'	=> [
			'theme-license'             => sprintf(
				__( '%s License', 'italystrap' ),
				wp_get_theme( $theme_slug )->display( 'Name', false )
			),
		],
	];

	return $edd_config;
} );