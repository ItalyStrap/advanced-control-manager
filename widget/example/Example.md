## Widget Code example

```php
<?php
/**
 * Example for creating a new Widget with the ItalyStrap Widget API
 *
 * Creating a new widget it's like a piece of cake, just copy this class in your
 * plugin structure, change the name and the thing you don't want and load it with composer.
 *
 * @link italystrap.com
 * @since 1.0.0
 *
 * @package Vendor
 */

/**
 * You can modify the namespace as you want.
 */
namespace Vendor\Widget;

/**
 * Do not delete this line of code.
 */
use ItalyStrap\Widget\Widget;

/**
 * The class name mast reflect the file name if you use psr-4 syntax.
 * The minimum requirement of this class is to have 2 methods: CLASS::__construct() and CLASS::widget_render()
 */
class Example extends Widget {

	/**
	 * Declare the constructor
	 * From WordPress 4.6 you can add dependency injection to the constructor
	 * Class::__construct( Example_CLass $example_class[, $var, $another_var] );
	 *
	 * Then you can init it like this:
	 * $example = new \Vendor\Widget\Example( new Example_CLass[, $var, $another_var] );
	 * register_widget( $example );
	 */
	public function __construct() {

		/**
		 * In case yuo have $example_class injected to the constructor.
		 */
		// $this->example_class = $example_class;

		/**
		 * Create an array with the settings for the widget
		 *
		 * @var array
		 */
		$args = array(
			/**
			 * Widget Backend label. (Required).
			 */
			'label'				=> __( 'Widget Title', 'italystrap' ),
			/**
			 * Widget Backend Description. (Optional).
			 */
			'description'		=> __( 'Vetrina slider per la home page', 'italystrap' ),
			/**
			 * Widget fields. (Required).
			 * The field for the widget title is already setted.
			 * You can add ad many fields as you want in an array format.
			 * @see example-config.php 
			 */
			'fields'			=> require( __DIR__ . '/example-config.php' ),
			/**
			 * Widget control options. (Optional)
			 */
			'control_options'	=> array( 'width' => 450 ),
			/**
			 * Widget options. (Optional)
			 */
			'widget_options'	=> array( 'customize_selective_refresh' => true ),
		 );

		/**
		 * Now use Class::create_widget( $args ) for creating the widget.
		 */
		$this->create_widget( $args );
	}

	/**
	 * This method will dispay the widget content
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return string          Return the output
	 */
	public function widget_render( $args, $instance ) {

		/**
		 * Now you can return the result
		 */
		// return 'My widget page';
		// 
		// or if you set a dependency from external class rendering
		// 
		// return $this->example_class->output();

		/**
		 * If you have a template file with html you can use this snippet.
		 */
		$output = '';

		ob_start();

		require( 'template/output.php' );

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
```
