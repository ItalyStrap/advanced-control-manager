<?php
/**
 *
 * @group query
 * @group search
 */
class Tests_Query_Search extends WP_UnitTestCase {
	protected $q;
	protected $post_type;

	function setUp() {
		parent::setUp();

		$this->post_type = rand_str( 12 );
		register_post_type( $this->post_type );

		$this->q = new WP_Query();
	}

	function tearDown() {
		_unregister_post_type( $this->post_type );
		unset( $this->q );

		parent::tearDown();
	}

	function get_search_results( $terms ) {
		$args = http_build_query( array( 's' => $terms, 'post_type' => $this->post_type ) );
		return $this->q->query( $args );
	}

	function test_search_order_title_relevance() {
		foreach ( range( 1, 7 ) as $i )
			self::factory()->post->create( array( 'post_content' => $i . rand_str() . ' about', 'post_type' => $this->post_type ) );
		$post_id = self::factory()->post->create( array( 'post_title' => 'About', 'post_type' => $this->post_type ) );

		$posts = $this->get_search_results( 'About' );
		$this->assertEquals( $post_id, reset( $posts )->ID );
	}

	function test_search_terms_query_var() {
		$terms = 'This is a search term';
		$query = new WP_Query( array( 's' => 'This is a search term' ) );
		$this->assertNotEquals( explode( ' ', $terms ), $query->get( 'search_terms' ) );
		$this->assertEquals( array( 'search', 'term' ), $query->get( 'search_terms' ) );
	}

	function test_filter_stopwords() {
		$terms = 'This is a search term';
		add_filter( 'wp_search_stopwords', array( $this, 'filter_wp_search_stopwords' ) );
		$query = new WP_Query( array( 's' => $terms ) );
		remove_filter( 'wp_search_stopwords', array( $this, 'filter_wp_search_stopwords' ) );

		$this->assertNotEquals( array( 'search', 'term' ), $query->get( 'search_terms' ) );
		$this->assertEquals( array( 'This', 'is', 'search', 'term' ), $query->get( 'search_terms' ) );
	}

	function filter_wp_search_stopwords() {
		return array();
	}

	/**
	 * @ticket 33988
	 */
	public function test_s_should_exclude_term_prefixed_with_dash() {
		$p1 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has foo but also bar',
		) );
		$p2 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has only foo',
		) );

		$q = new WP_Query( array(
			's' => 'foo -bar',
			'fields' => 'ids',
		) );

		$this->assertEqualSets( array( $p2 ), $q->posts );
	}

	/**
	 * @ticket 33988
	 */
	public function test_s_should_exclude_first_term_if_prefixed_with_dash() {
		$p1 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has foo but also bar',
		) );
		$p2 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has only bar',
		) );

		$q = new WP_Query( array(
			's' => '-foo bar',
			'fields' => 'ids',
		) );

		$this->assertEqualSets( array( $p2 ), $q->posts );
	}

	/**
	 * @ticket 33988
	 */
	public function test_s_should_not_exclude_for_dashes_in_the_middle_of_words() {
		$p1 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has foo but also bar',
		) );
		$p2 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has only bar',
		) );
		$p3 = self::factory()->post->create( array(
			'post_status' => 'publish',
			'post_content' => 'This post has only foo-bar',
		) );

		$q = new WP_Query( array(
			's' => 'foo-bar',
			'fields' => 'ids',
		) );

		$this->assertEqualSets( array( $p3 ), $q->posts );
	}
}
