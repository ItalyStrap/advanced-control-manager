<?php namespace ItalyStrap\Core;
// use ItalyStrap\Core\Fields;

class FieldsTest extends \Codeception\TestCase\WPTestCase {

	private $fields_array;
	private $fields_type;
	private $widget;

	public function setUp() {
		// before
		parent::setUp();

		$this->fields_array = require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' );

		$this->fields_type = new Fields;

		$this->test_type_text = array(
				'name'      => __( 'Widget Class', 'ItalyStrap' ),
				'desc'      => __( 'Enter the widget class name.', 'ItalyStrap' ),
				'id'        => 'widget_class',
				'type'      => 'text',
				'class'     => 'widefat widget_class',
				'class-p'   => 'widget_class',
				'default'   => '',
				'validate'  => 'alpha_dash',
				'filter'    => 'sanitize_text_field',
				'section'   => 'general',
				 );
		$this->test_type_text['_id'] = $this->test_type_text['id'];
		$this->test_type_text['_name'] = $this->test_type_text['id'];

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable() {
        $this->assertInstanceOf( 'ItalyStrap\Core\Fields', $this->fields_type );
    }

	public function test_field_array_is_set() {
		$this->assertTrue( isset( $this->fields_array ) );
	}

	public function test_field_type_is_set_and_is_object() {
		$this->assertTrue( isset( $this->fields_type ) );
		$this->assertTrue( is_object( $this->fields_type ) );
	}

	public function test_field_type_is_set() {
		$this->assertTrue( isset( $this->fields_type ) );
	}

	public function test_type_text() {

		$a = array(
			'type'            => 'text',
			'class'           => esc_attr( $this->test_type_textkey['class'] ),
			'name'            => esc_attr( $this->test_type_textkey['_name'] ),
			'id'              => esc_attr( $this->test_type_textkey['_id'] ),
			'value'           => ( isset( $this->test_type_textkey['value'] ) ? $this->test_type_textkey['value'] : ( isset( $this->test_type_textkey['default'] ) ? $this->test_type_textkey['default'] : '' ) ),
		);

		$out = $this->fields_type->field_type_text( $this->test_type_text );

		$this->assertTrue( is_string( $out ) );
		foreach ( $a as $key => $value ) {
			$this->assertTrue( false !== strpos( $out, $key ) );
			// $this->assertTrue( false !== strpos( $out, $value ) );
		}
	}

}