<?php
/**
 * Test the Query_Posts Class
 */
// namespace ItalyStrap\Core;

use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;
use ItalyStrap\I18N\Translator;

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

		$this->dom = new \DOMDocument();

		// Your set up methods here.
	}

	protected function getInstance() {
		$sut = new \ItalyStrap\Query\Posts( new \WP_Query(), new Excerpt( new Config(), new Translator( 'ItalyStrap' ) ), 'post'  );
		\PHPUnit\Framework\Assert::assertInstanceOf( \ItalyStrap\Query\Posts::class, $sut, '' );
		return $sut;
	}

	/**
	 * [tearDown description]
	 */
	public function tearDown(): void  {
		// Your tear down methods here.

		// Then.
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable with_static_method
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->getInstance();
	}

	/**
	 * it should be echo_read_more_link
	 */
	public function it_should_be_echo_read_more_link() {

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
