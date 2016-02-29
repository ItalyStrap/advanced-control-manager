<?php

/**
 * Tests for the WP_Customize_Widgets class.
 *
 * @group customize
 */
class Tests_WP_Customize_Widgets extends WP_UnitTestCase {

	/**
	 * @var WP_Customize_Manager
	 */
	protected $manager;

	/**
	 * Stored global variable in setUp for restoration in tearDown.
	 *
	 * @see $wp_registered_sidebars
	 * @var array
	 */
	protected $backup_registered_sidebars;

	function setUp() {
		parent::setUp();
		require_once( ABSPATH . WPINC . '/class-wp-customize-manager.php' );
		$GLOBALS['wp_customize'] = new WP_Customize_Manager();
		$this->manager = $GLOBALS['wp_customize'];

		unset( $GLOBALS['_wp_sidebars_widgets'] ); // clear out cache set by wp_get_sidebars_widgets()
		$sidebars_widgets = wp_get_sidebars_widgets();
		$this->assertEqualSets( array( 'wp_inactive_widgets', 'sidebar-1' ), array_keys( wp_get_sidebars_widgets() ) );
		$this->assertContains( 'search-2', $sidebars_widgets['sidebar-1'] );
		$this->assertContains( 'categories-2', $sidebars_widgets['sidebar-1'] );
		$this->assertArrayHasKey( 2, get_option( 'widget_search' ) );
		$widget_categories = get_option( 'widget_categories' );
		$this->assertArrayHasKey( 2, $widget_categories );
		$this->assertEquals( '', $widget_categories[2]['title'] );

		remove_action( 'after_setup_theme', 'twentyfifteen_setup' ); // @todo We should not be including a theme anyway

		$user_id = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		$this->backup_registered_sidebars = $GLOBALS['wp_registered_sidebars'];
	}

	function tearDown() {
		$this->manager = null;
		unset( $GLOBALS['wp_customize'] );
		unset( $GLOBALS['wp_scripts'] );
		$GLOBALS['wp_registered_sidebars'] = $this->backup_registered_sidebars;
		parent::tearDown();
	}

	function set_customized_post_data( $customized ) {
		$_POST['customized'] = wp_slash( wp_json_encode( $customized ) );
	}

	function do_customize_boot_actions() {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		do_action( 'setup_theme' );
		$_REQUEST['nonce'] = wp_create_nonce( 'preview-customize_' . $this->manager->theme()->get_stylesheet() );
		do_action( 'after_setup_theme' );
		do_action( 'init' );
		do_action( 'wp_loaded' );
		do_action( 'wp', $GLOBALS['wp'] );
	}

	/**
	 * Test WP_Customize_Widgets::__construct()
	 */
	function test_construct() {
		$this->assertInstanceOf( 'WP_Customize_Widgets', $this->manager->widgets );
		$this->assertEquals( $this->manager, $this->manager->widgets->manager );
	}

	/**
	 * Test WP_Customize_Widgets::register_settings()
	 *
	 * @ticket 30988
	 */
	function test_register_settings() {

		$raw_widget_customized = array(
			'widget_categories[2]' => array(
				'title' => 'Taxonomies Brand New Value',
				'count' => 0,
				'hierarchical' => 0,
				'dropdown' => 0,
			),
			'widget_search[3]' => array(
				'title' => 'Not as good as Google!',
			),
		);
		$customized = array();
		foreach ( $raw_widget_customized as $setting_id => $instance ) {
			$customized[ $setting_id ] = $this->manager->widgets->sanitize_widget_js_instance( $instance );
		}

		$this->set_customized_post_data( $customized );
		$this->do_customize_boot_actions();
		$this->assertTrue( is_customize_preview() );

		$this->assertNotEmpty( $this->manager->get_setting( 'widget_categories[2]' ), 'Expected setting for pre-existing widget category-2, being customized.' );
		$this->assertNotEmpty( $this->manager->get_setting( 'widget_search[2]' ), 'Expected setting for pre-existing widget search-2, not being customized.' );
		$this->assertNotEmpty( $this->manager->get_setting( 'widget_search[3]' ), 'Expected dynamic setting for non-existing widget search-3, being customized.' );

		$widget_categories = get_option( 'widget_categories' );
		$this->assertEquals( $raw_widget_customized['widget_categories[2]'], $widget_categories[2], 'Expected $wp_customize->get_setting(widget_categories[2])->preview() to have been called.' );
	}

	/**
	 * Test WP_Customize_Widgets::get_setting_args()
	 */
	function test_get_setting_args() {

		add_filter( 'widget_customizer_setting_args', array( $this, 'filter_widget_customizer_setting_args' ), 10, 2 );

		$default_args = array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'default' => array(),
			'sanitize_callback' => array( $this->manager->widgets, 'sanitize_widget_instance' ),
			'sanitize_js_callback' => array( $this->manager->widgets, 'sanitize_widget_js_instance' ),
		);

		$args = $this->manager->widgets->get_setting_args( 'widget_foo[2]' );
		foreach ( $default_args as $key => $default_value ) {
			$this->assertEquals( $default_value, $args[ $key ] );
		}
		$this->assertEquals( 'WIDGET_FOO[2]', $args['uppercase_id_set_by_filter'] );

		$override_args = array(
			'type' => 'theme_mod',
			'capability' => 'edit_posts',
			'transport' => 'postMessage',
			'default' => array( 'title' => 'asd' ),
			'sanitize_callback' => '__return_empty_array',
			'sanitize_js_callback' => '__return_empty_array',
		);
		$args = $this->manager->widgets->get_setting_args( 'widget_bar[3]', $override_args );
		foreach ( $override_args as $key => $override_value ) {
			$this->assertEquals( $override_value, $args[ $key ] );
		}
		$this->assertEquals( 'WIDGET_BAR[3]', $args['uppercase_id_set_by_filter'] );

		$default_args = array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'default' => array(),
			'sanitize_callback' => array( $this->manager->widgets, 'sanitize_sidebar_widgets' ),
			'sanitize_js_callback' => array( $this->manager->widgets, 'sanitize_sidebar_widgets_js_instance' ),
		);
		$args = $this->manager->widgets->get_setting_args( 'sidebars_widgets[sidebar-1]' );
		foreach ( $default_args as $key => $default_value ) {
			$this->assertEquals( $default_value, $args[ $key ] );
		}
		$this->assertEquals( 'SIDEBARS_WIDGETS[SIDEBAR-1]', $args['uppercase_id_set_by_filter'] );

		$override_args = array(
			'type' => 'theme_mod',
			'capability' => 'edit_posts',
			'transport' => 'postMessage',
			'default' => array( 'title' => 'asd' ),
			'sanitize_callback' => '__return_empty_array',
			'sanitize_js_callback' => '__return_empty_array',
		);
		$args = $this->manager->widgets->get_setting_args( 'sidebars_widgets[sidebar-2]', $override_args );
		foreach ( $override_args as $key => $override_value ) {
			$this->assertEquals( $override_value, $args[ $key ] );
		}
		$this->assertEquals( 'SIDEBARS_WIDGETS[SIDEBAR-2]', $args['uppercase_id_set_by_filter'] );
	}

	function filter_widget_customizer_setting_args( $args, $id ) {
		$args['uppercase_id_set_by_filter'] = strtoupper( $id );
		return $args;
	}

	/**
	 * Test WP_Customize_Widgets::sanitize_widget_js_instance() and WP_Customize_Widgets::sanitize_widget_instance()
	 */
	function test_sanitize_widget_js_instance() {
		$this->do_customize_boot_actions();

		$new_categories_instance = array(
			'title' => 'Taxonomies Brand New Value',
			'count' => '1',
			'hierarchical' => '1',
			'dropdown' => '1',
		);

		$sanitized_for_js = $this->manager->widgets->sanitize_widget_js_instance( $new_categories_instance );
		$this->assertArrayHasKey( 'encoded_serialized_instance', $sanitized_for_js );
		$this->assertTrue( is_serialized( base64_decode( $sanitized_for_js['encoded_serialized_instance'] ), true ) );
		$this->assertEquals( $new_categories_instance['title'], $sanitized_for_js['title'] );
		$this->assertTrue( $sanitized_for_js['is_widget_customizer_js_value'] );
		$this->assertArrayHasKey( 'instance_hash_key', $sanitized_for_js );

		$corrupted_sanitized_for_js = $sanitized_for_js;
		$corrupted_sanitized_for_js['encoded_serialized_instance'] = base64_encode( serialize( array( 'title' => 'EVIL' ) ) );
		$this->assertNull( $this->manager->widgets->sanitize_widget_instance( $corrupted_sanitized_for_js ), 'Expected sanitize_widget_instance to reject corrupted data.' );

		$unsanitized_from_js = $this->manager->widgets->sanitize_widget_instance( $sanitized_for_js );
		$this->assertEquals( $unsanitized_from_js, $new_categories_instance );
	}

	/**
	 * Get the widget control args for tests.
	 *
	 * @return array
	 */
	function get_test_widget_control_args() {
		global $wp_registered_widgets;
		require_once ABSPATH . '/wp-admin/includes/widgets.php';
		$widget_id = 'search-2';
		$widget = $wp_registered_widgets[ $widget_id ];
		$args = array(
			'widget_id' => $widget['id'],
			'widget_name' => $widget['name'],
		);
		$args = wp_list_widget_controls_dynamic_sidebar( array( 0 => $args, 1 => $widget['params'][0] ) );
		return $args;
	}

	/**
	 * @see WP_Customize_Widgets::get_widget_control()
	 */
	function test_get_widget_control() {
		$this->do_customize_boot_actions();
		$widget_control = $this->manager->widgets->get_widget_control( $this->get_test_widget_control_args() );

		$this->assertContains( '<div class="form">', $widget_control );
		$this->assertContains( '<div class="widget-content">', $widget_control );
		$this->assertContains( '<input type="hidden" name="id_base" class="id_base" value="search"', $widget_control );
		$this->assertContains( '<input class="widefat"', $widget_control );
	}

	/**
	 * @see WP_Customize_Widgets::get_widget_control_parts()
	 */
	function test_get_widget_control_parts() {
		$this->do_customize_boot_actions();
		$widget_control_parts = $this->manager->widgets->get_widget_control_parts( $this->get_test_widget_control_args() );
		$this->assertArrayHasKey( 'content', $widget_control_parts );
		$this->assertArrayHasKey( 'control', $widget_control_parts );

		$this->assertContains( '<div class="form">', $widget_control_parts['control'] );
		$this->assertContains( '<div class="widget-content">', $widget_control_parts['control'] );
		$this->assertContains( '<input type="hidden" name="id_base" class="id_base" value="search"', $widget_control_parts['control'] );
		$this->assertNotContains( '<input class="widefat"', $widget_control_parts['control'] );
		$this->assertContains( '<input class="widefat"', $widget_control_parts['content'] );
	}

	/**
	 * @see WP_Widget_Form_Customize_Control::json()
	 */
	function test_wp_widget_form_customize_control_json() {
		$this->do_customize_boot_actions();
		$control = $this->manager->get_control( 'widget_search[2]' );
		$params = $control->json();

		$this->assertEquals( 'widget_form', $params['type'] );
		$this->assertRegExp( '#^<li[^>]+>\s+</li>$#', $params['content'] );
		$this->assertRegExp( '#^<div[^>]*class=\'widget\'[^>]*#s', $params['widget_control'] );
		$this->assertContains( '<div class="widget-content"></div>', $params['widget_control'] );
		$this->assertNotContains( '<input class="widefat"', $params['widget_control'] );
		$this->assertContains( '<input class="widefat"', $params['widget_content'] );
		$this->assertEquals( 'search-2', $params['widget_id'] );
		$this->assertEquals( 'search', $params['widget_id_base'] );
		$this->assertArrayHasKey( 'sidebar_id', $params );
		$this->assertArrayHasKey( 'width', $params );
		$this->assertArrayHasKey( 'height', $params );
		$this->assertInternalType( 'bool', $params['is_wide'] );
	}

	/**
	 * @see WP_Customize_Widgets::is_panel_active()
	 */
	function test_is_panel_active() {
		global $wp_registered_sidebars;
		$this->do_customize_boot_actions();

		$this->assertNotEmpty( $wp_registered_sidebars );
		$this->assertTrue( $this->manager->widgets->is_panel_active() );
		$this->assertTrue( $this->manager->get_panel( 'widgets' )->active() );

		$wp_registered_sidebars = array();
		$this->assertFalse( $this->manager->widgets->is_panel_active() );
		$this->assertFalse( $this->manager->get_panel( 'widgets' )->active() );
	}

	/**
	 * @ticket 34738
	 * @see WP_Customize_Widgets::call_widget_update()
	 */
	function test_call_widget_update() {

		$widget_number = 2;
		$widget_id = "search-{$widget_number}";
		$setting_id = "widget_search[{$widget_number}]";
		$instance = array(
			'title' => 'Buscar',
		);

		$_POST = wp_slash( array(
			'action' => 'update-widget',
			'wp_customize' => 'on',
			'nonce' => wp_create_nonce( 'update-widget' ),
			'theme' => $this->manager->get_stylesheet(),
			'customized' => '{}',
			'widget-search' => array(
				2 => $instance,
			),
			'widget-id' => $widget_id,
			'id_base' => 'search',
			'widget-width' => '250',
			'widget-height' => '200',
			'widget_number' => strval( $widget_number ),
			'multi_number' => '',
			'add_new' => '',
		) );

		$this->do_customize_boot_actions();

		$this->assertArrayNotHasKey( $setting_id, $this->manager->unsanitized_post_values() );
		$result = $this->manager->widgets->call_widget_update( $widget_id );

		$this->assertInternalType( 'array', $result );
		$this->assertArrayHasKey( 'instance', $result );
		$this->assertArrayHasKey( 'form', $result );
		$this->assertEquals( $instance, $result['instance'] );
		$this->assertContains( sprintf( 'value="%s"', esc_attr( $instance['title'] ) ), $result['form'] );

		$post_values = $this->manager->unsanitized_post_values();
		$this->assertArrayHasKey( $setting_id, $post_values );
		$post_value = $post_values[ $setting_id ];
		$this->assertInternalType( 'array', $post_value );
		$this->assertArrayHasKey( 'title', $post_value );
		$this->assertArrayHasKey( 'encoded_serialized_instance', $post_value );
		$this->assertArrayHasKey( 'instance_hash_key', $post_value );
		$this->assertArrayHasKey( 'is_widget_customizer_js_value', $post_value );
		$this->assertEquals( $post_value, $this->manager->widgets->sanitize_widget_js_instance( $instance ) );
	}
}
