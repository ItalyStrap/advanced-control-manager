<?php
/**
 * Test the Query_Posts Class
 */
// namespace ItalyStrap\Core;

use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;

class QueryPostTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * Instance of Query_Posts
	 *
	 * @var null
	 */
	private $query;

	/**
	 * [setUp description]
	 */
	public function setUp(): void  {
		// Before.
		parent::setUp();

		$this->query = ItalyStrap\Query\Posts::init();
		$this->dom = new \DOMDocument();

		// Your set up methods here.
	}

	/**
	 * [tearDown description]
	 */
	public function tearDown(): void  {
		// Your tear down methods here.
		//
		$this->query = new ItalyStrap\Query\Posts( new \WP_Query(), new Excerpt( new Config() ), new Config(), 'test'  );

		// Then.
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable with_static_method
	 */
	public function it_should_be_instantiatable_with_static_method() {
		$this->query = ItalyStrap\Query\Posts::init();
		$this->assertInstanceOf( 'ItalyStrap\Query\Posts', $this->query );
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->query = new ItalyStrap\Query\Posts( new WP_Query(), new Excerpt( new Config() ), new Config(), 'test'  );
		$this->assertInstanceOf( 'ItalyStrap\Query\Posts', $this->query );
	}

	/**
	 * @test
	 * it should be echo_read_more_link
	 */
	public function it_should_be_echo_read_more_link() {

		ob_start();
		$this->query->read_more_link();
		$out = ob_end_clean();

		$this->dom->loadHTML( $out );
		$elements = $this->dom->getElementsByTagName( 'a' );
		$this->assertNotEmpty( $elements, 'message' );

		foreach ( $elements as $key => $element ) {
			$this->assertNotNull( $element->getAttribute( 'class' ), 'Attribute class is empty' );
			$this->assertTrue( strpos( $element, 'more-link' ), 'Class more-link is empty' );
		}
	}
}
