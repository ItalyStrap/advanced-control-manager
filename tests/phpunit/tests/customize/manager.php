<?php
/**
 * WP_Customize_Manager tests.
 *
 * @package WordPress
 */

/**
 * Tests for the WP_Customize_Manager class.
 *
 * @group customize
 */
class Tests_WP_Customize_Manager extends WP_UnitTestCase {

	/**
	 * Customize manager instance re-instantiated with each test.
	 *
	 * @var WP_Customize_Manager
	 */
	public $manager;

	/**
	 * Symbol.
	 *
	 * @var stdClass
	 */
	public $undefined;

	/**
	 * Set up test.
	 */
	function setUp() {
		parent::setUp();
		require_once( ABSPATH . WPINC . '/class-wp-customize-manager.php' );
		$this->manager = $this->instantiate();
		$this->undefined = new stdClass();
	}

	/**
	 * Tear down test.
	 */
	function tearDown() {
		$this->manager = null;
		unset( $GLOBALS['wp_customize'] );
		parent::tearDown();
	}

	/**
	 * Instantiate class, set global $wp_customize, and return instance.
	 *
	 * @return WP_Customize_Manager
	 */
	function instantiate() {
		$GLOBALS['wp_customize'] = new WP_Customize_Manager();
		return $GLOBALS['wp_customize'];
	}

	/**
	 * Test WP_Customize_Manager::doing_ajax().
	 *
	 * @group ajax
	 */
	function test_doing_ajax() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$manager = $this->manager;
		$this->assertTrue( $manager->doing_ajax() );

		$_REQUEST['action'] = 'customize_save';
		$this->assertTrue( $manager->doing_ajax( 'customize_save' ) );
		$this->assertFalse( $manager->doing_ajax( 'update-widget' ) );
	}

	/**
	 * Test ! WP_Customize_Manager::doing_ajax().
	 */
	function test_not_doing_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->markTestSkipped( 'Cannot test when DOING_AJAX' );
		}

		$manager = $this->manager;
		$this->assertFalse( $manager->doing_ajax() );
	}

	/**
	 * Test WP_Customize_Manager::unsanitized_post_values().
	 *
	 * @ticket 30988
	 */
	function test_unsanitized_post_values() {
		$manager = $this->manager;

		$customized = array(
			'foo' => 'bar',
			'baz[quux]' => 123,
		);
		$_POST['customized'] = wp_slash( wp_json_encode( $customized ) );
		$post_values = $manager->unsanitized_post_values();
		$this->assertEquals( $customized, $post_values );
	}

	/**
	 * Test the WP_Customize_Manager::post_value() method.
	 *
	 * @ticket 30988
	 */
	function test_post_value() {
		$posted_settings = array(
			'foo' => 'OOF',
		);
		$_POST['customized'] = wp_slash( wp_json_encode( $posted_settings ) );

		$manager = $this->manager;

		$manager->add_setting( 'foo', array( 'default' => 'foo_default' ) );
		$foo_setting = $manager->get_setting( 'foo' );
		$this->assertEquals( 'foo_default', $manager->get_setting( 'foo' )->value(), 'Expected non-previewed setting to return default when value() method called.' );
		$this->assertEquals( $posted_settings['foo'], $manager->post_value( $foo_setting, 'post_value_foo_default' ), 'Expected post_value($foo_setting) to return value supplied in $_POST[customized][foo]' );

		$manager->add_setting( 'bar', array( 'default' => 'bar_default' ) );
		$bar_setting = $manager->get_setting( 'bar' );
		$this->assertEquals( 'post_value_bar_default', $manager->post_value( $bar_setting, 'post_value_bar_default' ), 'Expected post_value($bar_setting, $default) to return $default since no value supplied in $_POST[customized][bar]' );
	}

	/**
	 * Test WP_Customize_Manager::set_post_value().
	 *
	 * @see WP_Customize_Manager::set_post_value()
	 */
	function test_set_post_value() {
		$this->manager->add_setting( 'foo', array(
			'sanitize_callback' => array( $this, 'sanitize_foo_for_test_set_post_value' ),
		) );
		$setting = $this->manager->get_setting( 'foo' );

		$this->assertEmpty( $this->captured_customize_post_value_set_actions );
		add_action( 'customize_post_value_set', array( $this, 'capture_customize_post_value_set_actions' ), 10, 3 );
		add_action( 'customize_post_value_set_foo', array( $this, 'capture_customize_post_value_set_actions' ), 10, 2 );
		$this->manager->set_post_value( $setting->id, '123abc' );
		$this->assertCount( 2, $this->captured_customize_post_value_set_actions );
		$this->assertEquals( 'customize_post_value_set_foo', $this->captured_customize_post_value_set_actions[0]['action'] );
		$this->assertEquals( 'customize_post_value_set', $this->captured_customize_post_value_set_actions[1]['action'] );
		$this->assertEquals( array( '123abc', $this->manager ), $this->captured_customize_post_value_set_actions[0]['args'] );
		$this->assertEquals( array( $setting->id, '123abc', $this->manager ), $this->captured_customize_post_value_set_actions[1]['args'] );

		$unsanitized = $this->manager->unsanitized_post_values();
		$this->assertArrayHasKey( $setting->id, $unsanitized );

		$this->assertEquals( '123abc', $unsanitized[ $setting->id ] );
		$this->assertEquals( 123, $setting->post_value() );
	}

	/**
	 * Sanitize a value for Tests_WP_Customize_Manager::test_set_post_value().
	 *
	 * @see Tests_WP_Customize_Manager::test_set_post_value()
	 *
	 * @param mixed $value Value.
	 * @return int Value.
	 */
	function sanitize_foo_for_test_set_post_value( $value ) {
		return intval( $value );
	}

	/**
	 * Store data coming from customize_post_value_set action calls.
	 *
	 * @see Tests_WP_Customize_Manager::capture_customize_post_value_set_actions()
	 * @var array
	 */
	protected $captured_customize_post_value_set_actions = array();

	/**
	 * Capture the actions fired when calling WP_Customize_Manager::set_post_value().
	 *
	 * @see Tests_WP_Customize_Manager::test_set_post_value()
	 */
	function capture_customize_post_value_set_actions() {
		$action = current_action();
		$args = func_get_args();
		$this->captured_customize_post_value_set_actions[] = compact( 'action', 'args' );
	}

	/**
	 * Test the WP_Customize_Manager::add_dynamic_settings() method.
	 *
	 * @ticket 30936
	 */
	function test_add_dynamic_settings() {
		$manager = $this->manager;
		$setting_ids = array( 'foo', 'bar' );
		$manager->add_setting( 'foo', array( 'default' => 'foo_default' ) );
		$this->assertEmpty( $manager->get_setting( 'bar' ), 'Expected there to not be a bar setting up front.' );
		$manager->add_dynamic_settings( $setting_ids );
		$this->assertEmpty( $manager->get_setting( 'bar' ), 'Expected the bar setting to remain absent since filters not added.' );

		$this->action_customize_register_for_dynamic_settings();
		$manager->add_dynamic_settings( $setting_ids );
		$this->assertNotEmpty( $manager->get_setting( 'bar' ), 'Expected bar setting to be created since filters were added.' );
		$this->assertEquals( 'foo_default', $manager->get_setting( 'foo' )->default, 'Expected static foo setting to not get overridden by dynamic setting.' );
		$this->assertEquals( 'dynamic_bar_default', $manager->get_setting( 'bar' )->default, 'Expected dynamic setting bar to have default providd by filter.' );
	}

	/**
	 * Test the WP_Customize_Manager::register_dynamic_settings() method.
	 *
	 * This is similar to test_add_dynamic_settings, except the settings are passed via $_POST['customized'].
	 *
	 * @ticket 30936
	 */
	function test_register_dynamic_settings() {
		$posted_settings = array(
			'foo' => 'OOF',
			'bar' => 'RAB',
		);
		$_POST['customized'] = wp_slash( wp_json_encode( $posted_settings ) );

		add_action( 'customize_register', array( $this, 'action_customize_register_for_dynamic_settings' ) );

		$manager = $this->manager;
		$manager->add_setting( 'foo', array( 'default' => 'foo_default' ) );

		$this->assertEmpty( $manager->get_setting( 'bar' ), 'Expected dynamic setting "bar" to not be registered.' );
		do_action( 'customize_register', $manager );
		$this->assertNotEmpty( $manager->get_setting( 'bar' ), 'Expected dynamic setting "bar" to be automatically registered after customize_register action.' );
		$this->assertEmpty( $manager->get_setting( 'baz' ), 'Expected unrecognized dynamic setting "baz" to remain unregistered.' );
	}

	/**
	 * In lieu of closures, callback for customize_register action added in test_register_dynamic_settings().
	 */
	function action_customize_register_for_dynamic_settings() {
		add_filter( 'customize_dynamic_setting_args', array( $this, 'filter_customize_dynamic_setting_args_for_test_dynamic_settings' ), 10, 2 );
		add_filter( 'customize_dynamic_setting_class', array( $this, 'filter_customize_dynamic_setting_class_for_test_dynamic_settings' ), 10, 3 );
	}

	/**
	 * In lieu of closures, callback for customize_dynamic_setting_args filter added for test_register_dynamic_settings().
	 *
	 * @param array  $setting_args Setting args.
	 * @param string $setting_id   Setting ID.
	 * @return array
	 */
	function filter_customize_dynamic_setting_args_for_test_dynamic_settings( $setting_args, $setting_id ) {
		$this->assertEquals( false, $setting_args, 'Expected $setting_args to be false by default.' );
		$this->assertInternalType( 'string', $setting_id );
		if ( in_array( $setting_id, array( 'foo', 'bar' ) ) ) {
			$setting_args = array( 'default' => "dynamic_{$setting_id}_default" );
		}
		return $setting_args;
	}

	/**
	 * In lieu of closures, callback for customize_dynamic_setting_class filter added for test_register_dynamic_settings().
	 *
	 * @param string $setting_class Setting class.
	 * @param string $setting_id    Setting ID.
	 * @param array  $setting_args  Setting args.
	 * @return string
	 */
	function filter_customize_dynamic_setting_class_for_test_dynamic_settings( $setting_class, $setting_id, $setting_args ) {
		$this->assertEquals( 'WP_Customize_Setting', $setting_class );
		$this->assertInternalType( 'string', $setting_id );
		$this->assertInternalType( 'array', $setting_args );
		return $setting_class;
	}

	/**
	 * Test is_ios() method.
	 *
	 * @see WP_Customize_Manager::is_ios()
	 */
	function test_is_ios() {
		$this->markTestSkipped( 'WP_Customize_Manager::is_ios() cannot be tested because it uses wp_is_mobile() which contains a static var.' );
	}

	/**
	 * Test get_document_title_template() method.
	 *
	 * @see WP_Customize_Manager::get_document_title_template()
	 */
	function test_get_document_title_template() {
		$tpl = $this->manager->get_document_title_template();
		$this->assertContains( '%s', $tpl );
	}

	/**
	 * Test get_preview_url()/set_preview_url methods.
	 *
	 * @see WP_Customize_Manager::get_preview_url()
	 * @see WP_Customize_Manager::set_preview_url()
	 */
	function test_preview_url() {
		$this->assertEquals( home_url( '/' ), $this->manager->get_preview_url() );
		$preview_url = home_url( '/foo/bar/baz/' );
		$this->manager->set_preview_url( $preview_url );
		$this->assertEquals( $preview_url, $this->manager->get_preview_url() );
		$this->manager->set_preview_url( 'http://illegalsite.example.com/food/' );
		$this->assertEquals( home_url( '/' ), $this->manager->get_preview_url() );
	}

	/**
	 * Test get_return_url()/set_return_url() methods.
	 *
	 * @see WP_Customize_Manager::get_return_url()
	 * @see WP_Customize_Manager::set_return_url()
	 */
	function test_return_url() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'author' ) ) );
		$this->assertEquals( home_url( '/' ), $this->manager->get_return_url() );

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$this->assertTrue( current_user_can( 'edit_theme_options' ) );
		$this->assertEquals( home_url( '/' ), $this->manager->get_return_url() );

		$preview_url = home_url( '/foo/' );
		$this->manager->set_preview_url( $preview_url );
		$this->assertEquals( $preview_url, $this->manager->get_return_url() );

		$_SERVER['HTTP_REFERER'] = wp_slash( admin_url( 'customize.php' ) );
		$this->assertEquals( $preview_url, $this->manager->get_return_url() );

		$url = home_url( '/referred/' );
		$_SERVER['HTTP_REFERER'] = wp_slash( $url );
		$this->assertEquals( $url, $this->manager->get_return_url() );

		$url = 'http://badreferer.example.com/';
		$_SERVER['HTTP_REFERER'] = wp_slash( $url );
		$this->assertNotEquals( $url, $this->manager->get_return_url() );
		$this->assertEquals( $preview_url, $this->manager->get_return_url() );

		$this->manager->set_return_url( admin_url( 'edit.php?trashed=1' ) );
		$this->assertEquals( admin_url( 'edit.php' ), $this->manager->get_return_url() );
	}

	/**
	 * Test get_autofocus()/set_autofocus() methods.
	 *
	 * @see WP_Customize_Manager::get_autofocus()
	 * @see WP_Customize_Manager::set_autofocus()
	 */
	function test_autofocus() {
		$this->assertEmpty( $this->manager->get_autofocus() );

		$this->manager->set_autofocus( array( 'unrecognized' => 'food' ) );
		$this->assertEmpty( $this->manager->get_autofocus() );

		$autofocus = array( 'control' => 'blogname' );
		$this->manager->set_autofocus( $autofocus );
		$this->assertEquals( $autofocus, $this->manager->get_autofocus() );

		$autofocus = array( 'section' => 'colors' );
		$this->manager->set_autofocus( $autofocus );
		$this->assertEquals( $autofocus, $this->manager->get_autofocus() );

		$autofocus = array( 'panel' => 'widgets' );
		$this->manager->set_autofocus( $autofocus );
		$this->assertEquals( $autofocus, $this->manager->get_autofocus() );

		$autofocus = array( 'control' => array( 'blogname', 'blogdescription' ) );
		$this->manager->set_autofocus( $autofocus );
		$this->assertEmpty( $this->manager->get_autofocus() );
	}

	/**
	 * Test customize_pane_settings() method.
	 *
	 * @see WP_Customize_Manager::customize_pane_settings()
	 */
	function test_customize_pane_settings() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$this->manager->register_controls();
		$this->manager->prepare_controls();
		$autofocus = array( 'control' => 'blogname' );
		$this->manager->set_autofocus( $autofocus );

		ob_start();
		$this->manager->customize_pane_settings();
		$content = ob_get_clean();

		$this->assertContains( 'var _wpCustomizeSettings =', $content );
		$this->assertContains( '"blogname"', $content );
		$this->assertContains( '_wpCustomizeSettings.controls', $content );
		$this->assertContains( '_wpCustomizeSettings.settings', $content );
		$this->assertContains( '</script>', $content );

		$this->assertNotEmpty( preg_match( '#var _wpCustomizeSettings\s*=\s*({.*?});\s*\n#', $content, $matches ) );
		$json = $matches[1];
		$data = json_decode( $json, true );
		$this->assertNotEmpty( $data );

		$this->assertEqualSets( array( 'theme', 'url', 'browser', 'panels', 'sections', 'nonce', 'autofocus', 'documentTitleTmpl' ), array_keys( $data ) );
		$this->assertEquals( $autofocus, $data['autofocus'] );
		$this->assertArrayHasKey( 'save', $data['nonce'] );
		$this->assertArrayHasKey( 'preview', $data['nonce'] );
	}

	/**
	 * @ticket 33552
	 */
	function test_customize_loaded_components_filter() {
		$manager = new WP_Customize_Manager();
		$this->assertInstanceOf( 'WP_Customize_Widgets', $manager->widgets );
		$this->assertInstanceOf( 'WP_Customize_Nav_Menus', $manager->nav_menus );

		add_filter( 'customize_loaded_components', array( $this, 'return_array_containing_widgets' ), 10, 2 );
		$manager = new WP_Customize_Manager();
		$this->assertInstanceOf( 'WP_Customize_Widgets', $manager->widgets );
		$this->assertEmpty( $manager->nav_menus );
		remove_all_filters( 'customize_loaded_components' );

		add_filter( 'customize_loaded_components', array( $this, 'return_array_containing_nav_menus' ), 10, 2 );
		$manager = new WP_Customize_Manager();
		$this->assertInstanceOf( 'WP_Customize_Nav_Menus', $manager->nav_menus );
		$this->assertEmpty( $manager->widgets );
		remove_all_filters( 'customize_loaded_components' );

		add_filter( 'customize_loaded_components', '__return_empty_array' );
		$manager = new WP_Customize_Manager();
		$this->assertEmpty( $manager->widgets );
		$this->assertEmpty( $manager->nav_menus );
		remove_all_filters( 'customize_loaded_components' );
	}

	/**
	 * @see Tests_WP_Customize_Manager::test_customize_loaded_components_filter()
	 *
	 * @param array                $components         Components.
	 * @param WP_Customize_Manager $customize_manager  Manager.
	 *
	 * @return array Components.
	 */
	function return_array_containing_widgets( $components, $customize_manager ) {
		$this->assertInternalType( 'array', $components );
		$this->assertContains( 'widgets', $components );
		$this->assertContains( 'nav_menus', $components );
		$this->assertInternalType( 'array', $components );
		$this->assertInstanceOf( 'WP_Customize_Manager', $customize_manager );
		return array( 'widgets' );
	}

	/**
	 * @see Tests_WP_Customize_Manager::test_customize_loaded_components_filter()
	 *
	 * @param array                $components         Components.
	 * @param WP_Customize_Manager $customize_manager  Manager.
	 *
	 * @return array Components.
	 */
	function return_array_containing_nav_menus( $components, $customize_manager ) {
		$this->assertInternalType( 'array', $components );
		$this->assertContains( 'widgets', $components );
		$this->assertContains( 'nav_menus', $components );
		$this->assertInternalType( 'array', $components );
		$this->assertInstanceOf( 'WP_Customize_Manager', $customize_manager );
		return array( 'nav_menus' );
	}

	/**
	 * @ticket 30225
	 * @ticket 34594
	 */
	function test_prepare_controls_stable_sorting() {
		$manager = new WP_Customize_Manager();
		$manager->register_controls();
		$section_id = 'foo-section';
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$manager->add_section( $section_id, array(
			'title'      => 'Section',
			'priority'   => 1,
		) );

		$added_control_ids = array();
		$count = 9;
		for ( $i = 0; $i < $count; $i += 1 ) {
			$id = 'sort-test-' . $i;
			$added_control_ids[] = $id;
			$manager->add_setting( $id );
			$control = new WP_Customize_Control( $manager, $id, array(
				'section' => $section_id,
				'priority' => 1,
				'setting' => $id,
			) );
			$manager->add_control( $control );
		}

		$manager->prepare_controls();

		$sorted_control_ids = wp_list_pluck( $manager->get_section( $section_id )->controls, 'id' );
		$this->assertEquals( $added_control_ids, $sorted_control_ids );
	}
}
