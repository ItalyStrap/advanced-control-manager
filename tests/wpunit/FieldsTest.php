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

		$this->fields_array = require( ITALYSTRAP_PLUGIN_PATH . 'config/posts.php' );

		$this->fields_type = new \ItalyStrap\Fields\Fields;

		$this->dom = new \DOMDocument();

		$this->test_type_text = array(
				'name'      => __( 'Widget Class', 'italystrap' ),
				'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
				'id'        => 'widget_class',
				'_id'       => 'widget_class',
				'_name'     => 'widget_class',
				'type'      => 'text',
				'class'     => 'widefat widget_class',
				'placeholder'     => 'widefat widget_class',
				'default'   => true,
				'value'   	=> 'general',
				'size'		=> '',
				 );

		$this->test_type_hidden = array(
				'name'      => __( 'Widget Class', 'italystrap' ),
				'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
				'id'        => 'widget_class',
				'_id'       => 'widget_class',
				'_name'     => 'widget_class',
				'type'      => 'hidden',
				'class'     => 'widefat widget_class',
				'placeholder'     => 'widefat widget_class',
				'default'   => true,
				'value'   	=> 'general',
				'size'		=> '',
				 );

		$this->test_type_textarea = array(
				'name'      => __( 'Widget Class', 'italystrap' ),
				'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
				'id'        => 'widget_class',
				'_id'       => 'widget_class',
				'_name'     => 'widget_class',
				'class'     => 'widefat widget_class',
				'placeholder'     => 'widefat widget_class',
				'default'   => true,
				// 'value'   	=> 'Some_value',
				 );

		$this->test_type_checkbox = array(
				'name'      => __( 'Widget Class', 'italystrap' ),
				'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
				'id'        => 'widget_class',
				'_id'       => 'widget_class',
				'_name'     => 'widget_class',
				'type'      => 'checkbox',
				'class'     => 'widefat widget_class',
				// 'default'   => '',
				'value'   	=> '1',
				 );

		$this->test_type_select = array(
				'name'      => __( 'Widget Class', 'italystrap' ),
				'desc'      => __( 'Enter the widget class name.', 'italystrap' ),
				'id'        => 'widget_class',
				'_id'       => 'widget_class',
				'_name'     => 'widget_class',
				'type'      => 'text',
				'class'     => 'widefat widget_class',
				'default'   => true,
				'option'   => array( 'key' => 'val' ),
				'value'   	=> 'Some value',
				 );

		$this->attr = array(
			'type'            => 'text',
			'class'           => $this->test_type_textkey['class'],
			'name'            => $this->test_type_textkey['_name'],
			'id'              => $this->test_type_textkey['_id'],
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
        $this->assertInstanceOf( 'ItalyStrap\Fields\Fields', $this->fields_type );
    }

    /**
     * @test
     * it_should_be_instance_of_I_Fields
     */
    public function it_should_be_instance_of_I_Fields() {
        $this->assertInstanceOf( 'ItalyStrap\Fields\Fields_Interface', $this->fields_type );
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
     * it_should_be_show
     */
    public function it_should_be_show() {
    	$key[ 'show_on_cb' ] = '__return_true';
        $this->assertTrue( $this->fields_type->should_show( $key ) );
    }

    /**
     * @test
     * it_should_be_hide
     */
    public function it_should_be_hide() {
    	$key[ 'show_on_cb' ] = '__return_false';
        $this->assertTrue( ! $this->fields_type->should_show( $key ) );
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
     * Get fields_type output
     */
    public function get_fields_input_output( $type = 'text', $tag = 'input' ) {

    	$fields_type = $tag;
    
		$out = $this->fields_type->$fields_type( array(), array( '_name' => true, '_id' => 'widget_class', 'default' => true, 'placeholder' => true, 'size' => true, 'desc' => true ) );

		$this->dom->loadHTML( $out );

		return $this->dom->getElementById('widget_class');
    
    }

	public function input_types_and_attributes_provider() {
		return [
            [ 'text', 'type' ],
            [ 'text', 'class' ],
            [ 'text', 'name' ],
            [ 'text', 'id' ],
            [ 'text', 'value' ],
            [ 'text', 'placeholder' ],
            [ 'text', 'size' ],
            [ 'textarea', 'class' ],
            [ 'textarea', 'name' ],
            [ 'textarea', 'id' ],
            // [ 'textarea', 'cols' ], // Verificare perché non funziona, is empty
            // [ 'textarea', 'rows' ], // Verificare perché non funziona, is empty
            [ 'textarea', 'value' ],
            [ 'textarea', 'placeholder' ],
		];
	}

	/**
	 * @test
	 * input should have proper attributes
	 * @dataProvider  input_types_and_attributes_provider
	 */
	public function input_should_have_proper_attributes( $type, $attr ) {

		$element = $this->get_fields_input_output( $type );

		$this->assertNotEmpty( $element->getAttribute( $attr ), "Attribute $attr is empty for type $type" );

	}

    /**
     * @test
     * it_should_be_have_html_attr_input
     * Method input from abstract class
     */
	public function it_should_be_have_html_attr_input() {
		$out = $this->fields_type->input( array(), $this->test_type_text );
		foreach ( $this->attr as $key => $value ) {
			$this->assertTrue( false !== strpos( $out, $key ) );
		}
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

    	$test_type = 'test_type_' . $type;
    
		$out = $this->fields_type->$fields_type( $this->$test_type );

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
            [ 'text', 'placeholder' ],
            [ 'hidden', 'type' ],
            [ 'hidden', 'class' ],
            [ 'hidden', 'name' ],
            [ 'hidden', 'id' ],
            [ 'hidden', 'value' ],
            [ 'hidden', 'placeholder' ],
            [ 'textarea', 'class' ],
            [ 'textarea', 'name' ],
            [ 'textarea', 'id' ],
            // [ 'textarea', 'value' ],
            [ 'textarea', 'placeholder' ],
            [ 'checkbox', 'type' ],
            [ 'checkbox', 'class' ],
            [ 'checkbox', 'name' ],
            [ 'checkbox', 'id' ],
            [ 'checkbox', 'value' ],
            [ 'checkbox', 'checked' ], // da testare: se non checked, value int e string e default int e string
            [ 'select', 'class' ],
            [ 'select', 'name' ],
            [ 'select', 'id' ],
            // [ 'select', 'option' ],
            // [ 'select', 'value' ],
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
