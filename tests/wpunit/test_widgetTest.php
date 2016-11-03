<?php

class test_widgetTest extends \Codeception\TestCase\WPTestCase
{

	public function setUp()
	{
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown()
	{
		// your tear down methods here

		// then
		parent::tearDown();
	}

	public function test_default_option_is_set_in_file() {

		$scanned_directory = array_diff( scandir( ITALYSTRAP_PLUGIN_PATH . 'config' ), array('..', '.', 'index.php', 'carousel.php', 'vcard-widget.php' ) );

		foreach ( $scanned_directory as $key => $value) {

			$pair = require( ITALYSTRAP_PLUGIN_PATH . 'config/' . $value );

			foreach ( $pair as $key => $default ) {
				$this->assertTrue( isset( $default['default'] ) );
			}

		}

		// $pair = require( ITALYSTRAP_PLUGIN_PATH . 'config/vcard.php' );

		// foreach ( $pair as $key => $default ) {
		// 	$this->assertTrue( isset( $default['default'] ) );
		// }

	}

	/**
	 * @see WP_Widget_Search::form()
	 */
	function test_italystrap_widget_media_carousel() {

		$widget = new ItalyStrap\Widget\Carousel( 'foo', 'Foo' );
		ob_start();
		$args = array(
			'before_widget' => '<section>',
			'after_widget' => "</section>\n",
			'before_title' => '<h2>',
			'after_title' => "</h2>\n",
		);
		$instance = array( 'title' => 'Buscar' );
		$widget->_set( 2 );
		$widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertNotContains( 'no-options-widget', $output );
		$this->assertContains( '<h2>Buscar</h2>', $output );
		$this->assertContains( '<section>', $output );
		$this->assertContains( '</section>', $output );

	}
}