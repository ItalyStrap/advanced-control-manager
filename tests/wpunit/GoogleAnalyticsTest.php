<?php

class GoogleAnalyticsTest extends \Codeception\TestCase\WPTestCase {


	public function setUp(): void {
		// before
		parent::setUp();

		$this->config = (array) get_option( 'italystrap_settings' );

		$this->config = wp_parse_args( $this->config, \ItalyStrap\Core\get_default_from_config( require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php' ) ) );

		// your set up methods here
		$this->analytics = new ItalyStrap\Google\Analytics( $this->config );
		$this->dom = new DOMDocument();
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'ItalyStrap\Google\Analytics', $this->analytics );
	}

	/**
	 * @test
	 * it_should_be_instance_of_I_Fields
	 */
	// public function it_should_be_instance_of_I_Fields() {
	//     $this->assertInstanceOf( 'ItalyStrap\Fields\Fields_Interface', $this->fields_type );
	// }

	/**
	 * @test
	 * it_should_be_an_object
	 */
	public function it_should_be_an_object() {
		$this->assertTrue( is_object( $this->analytics ) );
	}

	/**
	 * @test
	 * it_should_be_return_standard_script
	 */
	public function it_should_be_return_standard_script() {

		$this->dom->loadHTML( $this->analytics->standard_script( 'ga();' ) );

		$this->assertNotEmpty( $this->dom->getElementsByTagName('script') );
	}

	/**
	 * @test
	 * it_should_be_return_alternative_script
	 */
	public function it_should_be_return_alternative_script() {

		$this->dom->loadHTML( $this->analytics->alternative_script( 'ga();' ) );

		$this->assertNotEmpty( $this->dom->getElementsByTagName('script') );
	}

	/**
	 * @test
	 * it_should_be_return_analytics_script
	 */
	public function it_should_be_return_analytics_script() {

		$this->assertNotEmpty( $this->analytics->standard_script( $this->analytics->ga_commands_queue() ) );
		$this->assertNotEmpty( $this->analytics->alternative_script( $this->analytics->ga_commands_queue() ) );
	}
}
