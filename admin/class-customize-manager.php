<?php
/**
 * Customize_Manager
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */


namespace ItalyStrap\Admin;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use WP_Customize_Manager;
use \ItalyStrap\Core\Web_Font_loading;

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @todo https://codex.wordpress.org/Function_Reference/header_textcolor
 * @todo https://github.com/overclokk/wordpress-theme-customizer-custom-controls
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 * @since ItalyStrap 1.0
 */
class Customizer_Manager {

	/**
	 * $capability
	 *
	 * @var string
	 */
	private $capability = 'edit_theme_options';

	/**
	 * The plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Init the class
	 */
	function __construct( array $options = array(), Web_Font_loading $web_fonts ) {

		$this->options = $options;

		$this->web_fonts = $web_fonts;

		$this->fonts = $this->web_fonts->get_remote_fonts();

		$this->variants = array(
			'100'		=> __( '100', 'italystrap' ),
			'100italic'	=> __( '100italic', 'italystrap' ),
			'200'		=> __( '200', 'italystrap' ),
			'200italic'	=> __( '200italic', 'italystrap' ),
			'300'		=> __( '300', 'italystrap' ),
			'300italic'	=> __( '300italic', 'italystrap' ),
			'regular'	=> __( 'Regular 400', 'italystrap' ),
			'italic'	=> __( 'Italic 400', 'italystrap' ),
			'500'		=> __( '500', 'italystrap' ),
			'500italic'	=> __( '500italic', 'italystrap' ),
			'600'		=> __( '600', 'italystrap' ),
			'600italic'	=> __( '600italic', 'italystrap' ),
			'700'		=> __( '700', 'italystrap' ),
			'700italic'	=> __( '700italic', 'italystrap' ),
			'800'		=> __( '800', 'italystrap' ),
			'800italic'	=> __( '800italic', 'italystrap' ),
			'900'		=> __( '900', 'italystrap' ),
			'900italic'	=> __( '900italic', 'italystrap' ),
		);

		$this->subsets = array(
			'bengali'		=> __( 'Bengali', 'italystrap' ),
			'cyrillic'		=> __( 'Cyrillic', 'italystrap' ),
			'cyrillic-ext'	=> __( 'Cyrillic Extended', 'italystrap' ),
			'devanagari'	=> __( 'Devanagari', 'italystrap' ),
			'greek'			=> __( 'Greek', 'italystrap' ),
			'greek-ext'		=> __( 'Greek Extended', 'italystrap' ),
			'gujarati'		=> __( 'Gujarati', 'italystrap' ),
			'hebrew'		=> __( 'Hebrew', 'italystrap' ),
			'khmer'			=> __( 'Khmer', 'italystrap' ),
			'latin'			=> __( 'Latin', 'italystrap' ),
			'latin-ext'		=> __( 'Latin Extended', 'italystrap' ),
			'tamil'			=> __( 'Tamil', 'italystrap' ),
			'telugu'		=> __( 'Telugu', 'italystrap' ),
			'thai'			=> __( 'Thai', 'italystrap' ),
			'vietnamese'	=> __( 'Vietnamese', 'italystrap' ),
		);

	}

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * Note: To enable instant preview, we have to actually write a bit of custom
	 * javascript. See live_preview() for more.
	 *
	 * @see add_action('customize_register',$func)
	 * @param  object $wp_customize The cutomizer object.
	 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 * @since ItalyStrap 2.0.0
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
// d( get_theme_mods() );
// remove_theme_mod( 'body' );
// remove_theme_mod( 'body_font' );
// remove_theme_mod( 'heading' );
// d( get_theme_mods() );
		/**
		 * Define a new section for typography
		 */
		$wp_customize->add_section(
			'typography',
			array(
				'title'				=> __( 'Typography', 'italystrap' ),
				'description'		=> __( 'Chose typography style', 'italystrap' ),
				// 'panel' => $this->panel, // Not typically needed.
				'priority'			=> 160,
				'capability'		=> $this->capability,
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'body_font_family',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Web_Fonts_Control(
				$wp_customize,
				'body_font_family',
				array(
					'label'			=> __( 'Body font family', 'italystrap' ),
					'description'	=> __( 'Typography in all body', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'body_font_family',
					'priority'		=> 10,
					'default'		=> '',
					'choices'		=> $this->fonts,
				)
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'body_font_variants',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Multiple_Control(
				$wp_customize,
				'body_font_variants',
				array(
					'label'			=> __( 'Body font family variants', 'italystrap' ),
					'description'	=> __( 'Chose the weight of the font', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'body_font_variants',
					'priority'		=> 10,
					'default'		=> 'regular',
					'choices'		=> $this->variants,
				)
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'body_font_subsets',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Multiple_Control(
				$wp_customize,
				'body_font_subsets',
				array(
					'label'			=> __( 'Body font family subset', 'italystrap' ),
					'description'	=> __( 'Chose ', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'body_font_subsets',
					'priority'		=> 10,
					'default'		=> 'latin',
					'choices'		=> $this->subsets,
				)
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'heading_font_family',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Web_Fonts_Control(
				$wp_customize,
				'heading_font_family',
				array(
					'label'			=> __( 'Headings font family', 'italystrap' ),
					'description'	=> __( 'H1, H2, H3, H4, H5, H6', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'heading_font_family',
					'priority'		=> 10,
					'default'		=> '',
					'choices'		=> $this->fonts,
				)
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'heading_font_variants',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Multiple_Control(
				$wp_customize,
				'heading_font_variants',
				array(
					'label'			=> __( 'Headings font family variants', 'italystrap' ),
					'description'	=> __( 'Chose the weight of the font', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'heading_font_variants',
					'priority'		=> 10,
					'default'		=> 'regular',
					'choices'		=> $this->variants,
				)
			)
		);

		/**
		 * Add a textarea control for typography
		 */
		$wp_customize->add_setting(
			'heading_font_subsets',
			array(
				'default'			=> '',
				'type'				=> 'theme_mod',
				'capability'		=> $this->capability,
				'transport'			=> 'postMessage',
				'sanitize_callback'	=> 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Customize_Select_Multiple_Control(
				$wp_customize,
				'heading_font_subsets',
				array(
					'label'			=> __( 'Headings font family subsets', 'italystrap' ),
					'description'	=> __( 'Chose the weight of the font', 'italystrap' ),
					'section'		=> 'typography',
					'settings'		=> 'heading_font_subsets',
					'priority'		=> 10,
					'default'		=> 'regular',
					'choices'		=> $this->subsets,
				)
			)
		);

	}
}
