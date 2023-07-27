<?php

/**
 * Abstract Class for WP_Customizer_Control
 *
 * [Long Description.]
 *
 * @link http://italystra.com
 * @since 2.5.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Customizer\Control;

use WP_Customize_Control;
use WP_Customize_Manager;

/**
 * Class description
 */
class Control_Base extends WP_Customize_Control
{
    /**
     * The name of the class instantiated
     *
     * @var string
     */
    protected $class_name = '';

    /**
     * Init the constructor
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    {
     *     Optional. Arguments to override class property defaults.
     *
     *     @type int                  $instance_number Order in which this instance was created in relation
     *                                                 to other instances.
     *     @type WP_Customize_Manager $manager         Customizer bootstrap instance.
     *     @type string               $id              Control ID.
     *     @type array                $settings        All settings tied to the control. If undefined, `$id` will
     *                                                 be used.
     *     @type string               $setting         The primary setting for the control (if there is one).
     *                                                 Default 'default'.
     *     @type int                  $priority        Order priority to load the control. Default 10.
     *     @type string               $section         Section the control belongs to. Default empty.
     *     @type string               $label           Label for the control. Default empty.
     *     @type string               $description     Description for the control. Default empty.
     *     @type array                $choices         List of choices for 'radio' or 'select' type controls, where
     *                                                 values are the keys, and labels are the values.
     *                                                 Default empty array.
     *     @type array                $input_attrs     List of custom input attributes for control output, where
     *                                                 attribute names are the keys and values are the values. Not
     *                                                 used for 'checkbox', 'radio', 'select', 'textarea', or
     *                                                 'dropdown-pages' control types. Default empty array.
     *     @type array                $json            Deprecated. Use WP_Customize_Control::json() instead.
     *     @type string               $type            Control type. Core controls include 'text', 'checkbox',
     *                                                 'textarea', 'radio', 'select', and 'dropdown-pages'. Additional
     *                                                 input types such as 'email', 'url', 'number', 'hidden', and
     *                                                 'date' are supported implicitly. Default 'text'.
     * }
     */
    function __construct(WP_Customize_Manager $manager, $id, array $args = [])
    {

        /**
         * Credits:
         * @link https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace
         * @php54
         * $this->class_name =  ( new \ReflectionClass( $this ) )->getShortName();
         */
        $class_name = new \ReflectionClass($this);
        $this->class_name =  $class_name->getShortName();
        $this->class_name = strtolower($this->class_name);

        parent::__construct($manager, $id, $args);
    }

    /**
     * Enqueue and autoload scripts/styles.
     *
     * @since  2.5.0
     */
    public function enqueue()
    {
        $ver = null;
        $min = '.min';

        if (defined(WP_DEBUG) && WP_DEBUG) {
            $min = '';
            $ver = random_int(0, 100000);
        }

        if (! file_exists(__DIR__ . '/js/' . $this->class_name . $min . '.js')) {
            return;
        }

        wp_enqueue_script(
            'customizer-' . $this->class_name,
            plugins_url('js/' . $this->class_name . $min . '.js', __FILE__),
            ['jquery'],
            $ver,
            true
        );
    }
}
