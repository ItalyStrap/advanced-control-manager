<?php
/**
 * Translator API Class
 *
 * This is an adapter class for translating strings in themes and plugins.
 *
 * @forked from:
 * @link https://github.com/Mte90/WordPress-Plugin-Boilerplate-Powered
 * @link https://gist.github.com/Mte90/fe687ceed408ab743238
 *
 * @link italystrap.com
 * @since 2.9.0
 *
 * @package ItalyStrap\I18N
 */

namespace ItalyStrap\I18N;

/**
 * Translator Class
 */
class Translator implements Translatable {

	/**
	 * The name of the plugin to use as a string group
	 *
	 * @var string
	 */
	private $plugin_name = '';

	/**
	 * Constructor
	 *
	 * @param  string $plugin_name The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * Return the language 2-4 letters code
	 *
	 * @since   1.0.0
	 *
	 * @return     string 4 letters cod of the locale
	 */
	public function get_language() {

		switch ( true ) {
			case defined( 'ICL_LANGUAGE_CODE' ):
				return ICL_LANGUAGE_CODE;
				break;

			case function_exists( 'cml_get_browser_lang' ):
				return cml_get_browser_lang();
				break;

			case function_exists( 'pll_current_language' ):
				return pll_current_language();
				break;

			default:
				/**
				 * @link https://codex.wordpress.org/Function_Reference/get_locale
				 */
				return get_locale();
				break;
		}
	}

	/**
	 * Add registration for multilanguage string
	 *
	 * @since 1.0.0
	 *
	 * @param string $string_name The name of the string.
	 * @param string $value       The value.
	 */
	public function register_string( $string_name, $value ) {

		switch ( true ) {

			case function_exists( 'icl_register_string' ):
				icl_register_string( $this->plugin_name, $string_name, $value );
				break;

			case  class_exists( 'CMLTranslations' ):
				CMLTranslations::add(
					$string_name,
					$value,
					str_replace( ' ', '-', $this->plugin_name )
				);
				break;

			case  function_exists( 'pll_register_string' ):
				pll_register_string(
					str_replace( ' ', '-', $this->plugin_name ),
					$string_name
				);
				break;
			
			default:
				return;
				break;
		}
	}

	/**
	 * Unregister multilanguage string, Polylang missing support of this feature
	 * For deleting Pulylang string go to the Polylang string translation admin page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string_name The name of the string.
	 */
	public function deregister_string( $string_name ) {

		switch ( true ) {

			case function_exists( 'icl_unregister_string' ):
				icl_unregister_string( $this->plugin_name, $string_name );
				break;

			case has_filter( 'cml_my_translations' ):
				CMLTranslations::delete( str_replace( ' ', '-', $this->plugin_name ) );
				break;

			default:
				return;
				break;
		}
	}

	/**
	 * Get multilanguage string
	 *
	 * @since 1.0.0
	 *
	 * @param string $string_name The name of the string.
	 * @param string $value The value.
	 */
	function get_string( $string_name, $value ) {

		switch ( true ) {

			case function_exists( 'icl_t' ):
				return icl_t( $this->plugin_name, $string_name, $value );
				break;

			case has_filter( 'cml_my_translations' ):
				return CMLTranslations::get(
					CMLLanguage::get_current_id(),
					strtolower( $string_name ),
					str_replace( ' ', '-', $this->plugin_name, true )
				);
				break;

			case function_exists( 'pll__' ):
				return pll__( $string_name );
				break;

			default:
				return $value;
				break;
		}
	}

	// d( get_plugins() );
	// d( plugin_basename( dirname( __FILE__ ) ) );
	// d( get_plugin_data( plugin_basename( __FILE__ ) ) );
	/**
	 * Add a groups for string translations in Ceceppa multilanguiage
	 *
	 * @param  array $groups Array of groups string.
	 * @return array         New array
	 */
	// function cml_icc_strings( $groups ) {

	// 	$plugin_name_human_format = 'Italy Cookie Choices';

	// 	$plugin_name_human_format_replaced = str_replace( ' ', '-', $plugin_name_human_format );

	// 	$groups[ $plugin_name_human_format_replaced ] = $plugin_name_human_format;

	// 	return $groups;

	// }
	// add_filter( 'cml_my_translations', 'cml_icc_strings' );
}
