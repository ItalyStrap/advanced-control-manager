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
	 * Register new fields for add custom css, ID and class in every post/page
	 */
	public function register_style_fields_in_wp_editor() {

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

	/**
	 * Register metaboxes in the widget areas
	 * Per ora lo faccio partire direttamente dal tema, file bootstrap.php
	 */
	public function register_widget_areas_fields() {

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$cmb = new_cmb2_box(
			array(
				'id'            => $this->prefix . '-widget-areas-metabox',
				'title'         => __( 'Widget Area settings', 'italystrap' ),
				'object_types'  => array( 'sidebar' ),
				'context'    	=> 'normal',
				'priority'   	=> 'high',
			)
		);

		$options = array(
			'italystrap_content_header'	=> __( 'In the header', 'italystrap' ),
			'italystrap_before_main'	=> __( 'Before the Main Content', 'italystrap' ),
			'italystrap_before_content'	=> __( 'Before the Content', 'italystrap' ),

			'italystrap_before_loop'	=> __( 'Before the Loop', 'italystrap' ),
			'italystrap_after_loop'		=> __( 'After the Loop', 'italystrap' ),

			'italystrap_after_content'		=> __( 'After the Content', 'italystrap' ),
			'italystrap_after_main'		=> __( 'After the Main Content', 'italystrap' ),
			'italystrap_before_footer'		=> __( 'In the footer open', 'italystrap' ),
			'italystrap_footer'		=> __( 'In the footer', 'italystrap' ),
			'italystrap_after_footer'		=> __( 'In the footer closed', 'italystrap' ),
		);

		$options = apply_filters( 'italystrap_widget_area_position', $options );

		$cmb->add_field(
			array(
				'name'				=> __( 'Display on', 'italystrap' ),
				'desc'				=> __( 'Select the position to display this widget area.', 'italystrap' ),
				'id'				=> $this->_prefix . '_action',
				'type'				=> 'select',
				'show_option_none'	=> true,
				'options'			=> $options,
				'attributes'		=> array( 'placeholder' => '' ),
			)
		);

		$cmb->add_field(
			array(
				'name'				=> __( 'Priority', 'italystrap' ),
				'desc'				=> __( 'Type the priority you want to display this widget area, it must be a number, by default the priority is 10, you can choose a number between 1 and 99999, this is useful when you want to display more than one widget area in the same position but in different order.', 'italystrap' ),
				'id'				=> $this->_prefix . '_priority',
				'type'				=> 'text',
				'default'			=> 10,
				'attributes'		=> array( 'placeholder' => '' ),
			)
		);

		$cmb->add_field(
			array(
				'name'		=> __( 'Background color', 'italystrap' ),
				'desc'		=> __( 'Choose the color for the backgrount of the widget area.', 'italystrap' ),
				'id'		=> $this->_prefix . '_background_color',
				'type'		=> 'colorpicker',
				'default'	=> '',
			)
		);

		$cmb->add_field(
			array(
				'name'				=> __( 'Width', 'italystrap' ),
				'desc'				=> __( 'Select the width of this widget area.', 'italystrap' ),
				'id'				=> $this->_prefix . '_container_width',
				'type'				=> 'select',
				'default'			=> 'container-fluid',
				'options'			=> array(
					'container-fluid'	=> __( 'Full witdh', 'italystrap' ),
					'container'			=> __( 'Standard width', 'italystrap' ),
				),
				'attributes'		=> array( 'placeholder' => '' ),
			)
		);

	}
}
