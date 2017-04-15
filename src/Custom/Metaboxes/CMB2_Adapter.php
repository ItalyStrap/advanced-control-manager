<?php
/**
 * Various metaboxes for this plugin
 *
 * In this class I add some metaboxes to use in various place of the plugin
 * It uses CMB2 for that functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Custom\Metaboxes;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Add some metaboxes in admin area with CMB2
 */
class CMB2_Adapter {

	private $cmb = null;

	/**
	 * CMB prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * CMB _prefix
	 *
	 * @var string
	 */
	private $_prefix;

	/**
	 * The plugin options
	 *
	 * @var array
	 */
	private $options = array();

	protected $configs = array();

	/**
	 * Init the constructor
	 *
	 * @param array $options The plugin options
	 */
	function __construct( array $options = array() ) {

		/**
		 * Start with an underscore to hide fields from custom fields list
		 *
		 * @var string
		 */
		$this->prefix = 'italystrap';

		$this->_prefix = '_' . $this->prefix;

		$this->default = array(
			'name'		=> '',
			'desc'		=> '',
			'id'		=> '',
			'type'		=> 'text',
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
			'show_on'	=> true,
		);
	}

	/**
	 * Merge config in configs
	 *
	 * @param  array $config An array with a configuration for CMB2
	 * @return string        [description]
	 */
	public function merge( array $config ) {
		$this->configs[] = $config;
	}

	/**
	 * Autoload CMB2
	 */
	public function autoload() {

		$this->configs = (array) apply_filters( 'italystrap_cmb2_configurations_array', $this->configs );

		foreach ( $this->configs as $config ) {
			$this->load_cmb2( $config );
		}
	}

	/**
	 * Load CMB2
	 *
	 * @param  array $config Configuration array for CMB2
	 */
	public function load_cmb2( array $config ) {

		/**
		 * This prevents that the CMB2 autoload the fields itself.
		 */
		$fields = $config['fields'];
		unset( $config['fields'] );

		$this->cmb = new_cmb2_box( $config );

		$this->add_fields( $fields );
	}

	/**
	 * Add fields
	 *
	 * @param  array $fields An array with fields configuration.
	 */
	public function add_fields( array $fields ) {

		foreach ( $fields as $field ) {

			$field = array_merge( $this->default, $field );

			if ( ! $field['show_on'] ) {
				continue;
			}

			$this->cmb->add_field( $field );
		}
	}
}
