<?php
/**
 * Various metaboxes for this plugin
 *
 * In this class I add soome metaboxes to use in various place of the plugin
 * It uses CMB2 for that functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Admin;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Add some metaboxes in admin area with CMB2
 */
class Register_Metaboxes {

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
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function register_script_settings() {

		$script_settings_metabox_object_types = apply_filters( 'italystrap_script_settings_metabox_object_types', array( 'post', 'page' ) );

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$cmb = new_cmb2_box(
			array(
				'id'            => $this->prefix . '-script-settings-metabox',
				'title'         => __( 'CSS and JavaScript settings', 'italystrap' ),
				'object_types'  => $script_settings_metabox_object_types,
				'context'    	=> 'normal',
				'priority'   	=> 'high',
			)
		);

		$cmb->add_field(
			array(
				'name'			=> __( 'Custom CSS', 'italystrap' ),
				'desc'			=> __( 'This code will be included verbatim in style tag before </head> tag of your page or post', 'italystrap' ),
				'id'			=> $this->_prefix . '_custom_css_settings',
				'type'			=> 'textarea_code',
				'attributes'	=> array( 'placeholder' => 'body{background-color:#f2f2f2}' ),
			)
		);

		$cmb->add_field(
			array(
				'name'			=> __( 'Body Classes', 'italystrap' ),
				'desc'			=> __( 'These class names will be added to the body_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap' ),
				'id'			=> $this->_prefix . '_custom_body_class_settings',
				'type'			=> 'text',
				'attributes'	=> array( 'placeholder' => 'class1,class2,class3,otherclass' ),
			)
		);

		$cmb->add_field(
			array(
				'name'			=> __( 'Post Classes', 'italystrap' ),
				'desc'			=> __( 'These class names will be added to the post_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap' ),
				'id'			=> $this->_prefix . '_custom_post_class_settings',
				'type'			=> 'text',
				'attributes'	=> array( 'placeholder' => 'class1,class2,class3,otherclass' ),
			)
		);

		/**
		 * @todo Aggiungere selezione di script o stili già registrati che
		 *       si vuole aggiungere al post o pagine e anche globalmente
		 *       global $wp_scripts, $wp_styles;
		 */
	}
}
