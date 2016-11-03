<?php
/**
 * Test the Query_Posts Class
 */
// namespace ItalyStrap\Core;

// use \WP_Query;

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
	public function setUp() {
		// Before.
		parent::setUp();

		$this->query = ItalyStrap\Core\Query\Posts::init();

		// Your set up methods here.
	}

	/**
	 * [tearDown description]
	 */
	public function tearDown() {
		// Your tear down methods here.

		// Then.
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'ItalyStrap\Core\Query\Posts', $this->query );
	}

	/**
	 * @test
	 * it should be post_object_from_WP
	 */
	// public function it_should_be_post_object_from_WP() {

	// 	$isset = $this->query->get_global_post();

	// 	$this->assertTrue( isset( $isset ) );
	// }
}