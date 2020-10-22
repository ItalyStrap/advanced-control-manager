<?php
declare(strict_types=1);

/**
 * Test the Query_Posts Class
 */
namespace ItalyStrap\Test;

use Codeception\TestCase\WPTestCase;
use DOMDocument;
use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;
use ItalyStrap\I18N\Translator;
use ItalyStrap\Query\Posts;
use PHPUnit\Framework\Assert;
use WP_Query;

class QueryPostTest extends WPTestCase {

	public function setUp(): void {
		// Before.
		parent::setUp();

		$this->dom = new DOMDocument();

		// Your set up methods here.
	}

	protected function getInstance() {
		$sut = new Posts(
			new WP_Query(),
			new Excerpt( new Config(),
				new Translator( 'ItalyStrap' ) ),
			'test'
		);

		Assert::assertInstanceOf( Posts::class, $sut, '' );
		return $sut;
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then.
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function instanceOk() {
		$sut = $this->getInstance();
	}

	/**
	 * @test
	 */
	public function parameters() {
		$sut = $this->getInstance();
		$sut->get_shortcode_args( [] );
		$args = $sut->get_query_args();

		Assert::assertEquals( 'post', $args['post_type'], '' );

		codecept_debug( $args['post_type'] );
	}

	/**
	 * @test
	 */
	public function parameters2() {
		$sut = $this->getInstance();
		$sut->get_shortcode_args( ['post_types' => 'test'] );
		$args = $sut->get_query_args();

		Assert::assertEquals( 'test', $args['post_type'], '' );

		codecept_debug( $args['post_type'] );
	}

	/**
	 * @test
	 */
	public function parameters3() {
		$sut = $this->getInstance();

		$sut->get_shortcode_args( ['post_types' => 'test, post'] );
		$args = $sut->get_query_args();

		Assert::assertEquals( ['test', 'post'], $args['post_type'], '' );

		codecept_debug( $args['post_type'] );
	}

	/**
	 * it should be echo_read_more_link
	 */
	public function itShouldBeEchoReadMoreLink() {

		$sut = $this->getInstance();

		ob_start();
		$sut->read_more_link();
		$out = ob_get_clean();

		$this->dom->loadHTML( $out );
		$elements = $this->dom->getElementsByTagName( 'a' );
		$this->assertNotEmpty( $elements, 'message' );

		foreach ( $elements as $key => $element ) {
			$this->assertNotNull( $element->getAttribute( 'class' ), 'Attribute class is empty' );
			$this->assertTrue( strpos( $element, 'more-link' ), 'Class more-link is empty' );
		}
	}
}
