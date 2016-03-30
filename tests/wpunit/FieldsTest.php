<?php

class FieldsTest extends \Codeception\TestCase\WPTestCase {

	private $fields_array;
	private $fields_type;
	private $widget;

	private $dom;

	/**
	 * Html attribute
	 *
	 * @var array
	 */
	private $attr = array();

	public function setUp() {
		// before
		parent::setUp();

		$this->fields_array = require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' );

		$this->fields_type = new \ItalyStrap\Admin\Fields;

		$this->dom = new \DOMDocument();

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

		$this->attr = array(
			'type'            => 'text',
			'class'           => esc_attr( $this->test_type_textkey['class'] ),
			'name'            => esc_attr( $this->test_type_textkey['_name'] ),
			'id'              => esc_attr( $this->test_type_textkey['_id'] ),
			'value'           => ( isset( $this->test_type_textkey['value'] ) ? $this->test_type_textkey['value'] : ( isset( $this->test_type_textkey['default'] ) ? $this->test_type_textkey['default'] : '' ) ),
		);

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
        $this->assertInstanceOf( 'ItalyStrap\Admin\Fields', $this->fields_type );
    }

    /**
     * @test
     * it_should_be_instance_of_I_Fields
     */
    public function it_should_be_instance_of_I_Fields() {
        $this->assertInstanceOf( 'ItalyStrap\Admin\I_Fields', $this->fields_type );
    }

    /**
     * @test
     * it_should_be_an_object
     */
    public function it_should_be_an_object() {
        $this->assertTrue( is_object( $this->fields_type ) );
    }

    /**
     * @test
     * it_should_be_field_type_set
     */
    public function it_should_be_field_type_set() {
        $this->assertTrue( isset( $this->fields_type ) );
    }

    /**
     * @test
     * it_should_be_field_array_settings_set
     */
	public function it_should_be_field_array_settings_set() {
		$this->assertTrue( isset( $this->fields_array ) );
	}

    /**
     * @test
     * it_should_be_the_output_a_string
     */
	public function it_should_be_the_output_a_string() {

		$out = $this->fields_type->field_type_text( $this->test_type_text );
		$this->assertTrue( is_string( $out ) );
	}

    /**
     * @test
     * it_should_be_have_html_attr
     */
	public function it_should_be_have_html_attr() {
		$out = $this->fields_type->field_type_text( $this->test_type_text );
		foreach ( $this->attr as $key => $value ) {
			$this->assertTrue( false !== strpos( $out, $key ) );
		}
	}

    /**
     * Get fields_type output
     */
    public function get_fields_type_output( $type = 'text' ) {

    	$fields_type = 'field_type_' . $type;
    
		$out = $this->fields_type->$fields_type( $this->test_type_text );

		$this->dom->loadHTML( $out );

		return $this->dom->getElementById('widget_class');
    
    }

	public function types_and_attributes_provider() {
		return [
            [ 'text', 'type' ],
            [ 'text', 'class' ],
            [ 'text', 'name' ],
            [ 'text', 'id' ],
            [ 'text', 'value' ],
		];
	}

	/**
	 * @test
	 * it should have proper attributes
	 * @dataProvider  types_and_attributes_provider
	 */
	public function it_should_have_proper_attributes( $type, $attr ) {

		$element = $this->get_fields_type_output( $type );

		$this->assertNotEmpty( $element->getAttribute( $attr ), "Attribute $attr is empty for type $type" );

	}

}
