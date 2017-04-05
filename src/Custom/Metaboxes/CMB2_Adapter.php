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
			$this->cmb2( $config );
		}
	}

	/**
	 * Load CMB2
	 *
	 * @param  array $config Configuration array for CMB2
	 */
	public function cmb2( array $config ) {
		$cmb = new_cmb2_box( $config );

		foreach ( $config['fields'] as $field ) {
			$cmb->add_field( $field );
		}
	}
}
