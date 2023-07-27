<?php

/**
 * Renders extra controls in the Gallery Settings section of the new media UI.
 *
 * @since 1.1.0
 * @see JetPack functions-gallery.php gallery-settings.js
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

use ItalyStrap\Fields\FieldsInterface;
use ItalyStrap\Event\Subscriber_Interface;

/**
 * ItalyStrapAdminGallerySettings
 */
class ItalyStrapAdminGallerySettings implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked admin_init - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'admin_init'    => 'admin_init',
        ];
    }

    /**
     * Array with default value
     *
     * @var array
     */
    private $fields_old = [];

    private ?int $randID = null;

    /**
     * Default option
     *
     * @var array
     */
    private $carousel_options = [];

    private array $indicators = ['before-inner', 'after-inner', 'after-control', 'false'];

    private array $instance_old = [];

    function __construct(FieldsInterface $fields_type, ItalyStrap\Image\Size $image_size)
    {

        $this->image_size_media = $image_size;

        $this->fields_type = $fields_type;

        /**
         * Define data by given attributes.
         */
        $this->fields_old = require(ITALYSTRAP_PLUGIN_PATH . 'config/carousel.php');

        $this->fields = require(ITALYSTRAP_PLUGIN_PATH . 'config/media-carousel.php');

        $this->carousel_options = ['orderby'       => ['menu_order'    => __('Menu order (Default)', 'italystrap'), 'title'         => __('Order by the image\'s title', 'italystrap'), 'post_date'     => __('Sort by date/time', 'italystrap'), 'rand'          => __('Order randomly', 'italystrap'), 'ID'            => __('Order by the image\'s ID', 'italystrap')], 'indicators'    => ['before-inner'  => __('before-inner', 'italystrap'), 'after-inner'   => __('after-inner', 'italystrap'), 'after-control' => __('after-control', 'italystrap'), 'false'         => __('false', 'italystrap')], 'control'       => true, 'pause'         => ['false'         => __('none', 'italystrap'), 'hover'         => __('hover', 'italystrap')], 'image_title'   => true, 'text'          => true, 'wpautop'       => true, 'responsive'    => false];

        $this->randID = random_int(2, 5);

        // add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    /**
     * @todo applicare filtro in ItalyStrapCarousel e togliere il 2° valore dell'array qui sotto
     */
    function admin_init()
    {
        $this->gallery_types =
            apply_filters(
                'italystrap_gallery_types',
                ['default' => __('Standard gallery', 'italystrap'), 'carousel' => __('Bootstrap Carousel', 'italystrap')]
            );

        // Enqueue the media UI only if needed.
        // if ( count( $this->gallery_types ) > 0 ) {
            add_action('wp_enqueue_media', [$this, 'wp_enqueue_media']);
            add_action('print_media_templates', [$this, 'print_media_templates_old']);
            // add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
        // }
    }

    /**
     * Registers/enqueues the gallery settings admin js.
     */
    function wp_enqueue_media()
    {

        if (! wp_script_is('italystrap-gallery-settings', 'registered')) {
            /**
             * This only happens if we're not in ItalyStrap, but on WPCOM instead.
             * This is the correct path for WPCOM.
             */
            wp_register_script('italystrap-gallery-settings', plugins_url('admin/js/src/gallery-settings.js', ITALYSTRAP_FILE), ['media-views'], '20121225666');
        }

        $translation_array = wp_json_encode(require(ITALYSTRAP_PLUGIN_PATH . 'config/carousel.php'));
        // var_dump( $translation_array ); die();
        wp_localize_script('italystrap-gallery-settings', 'gallery_fields', $translation_array);

        wp_enqueue_script('italystrap-gallery-settings');
    }

    /**
     * Outputs a view template which can be used with wp.media.template
     */
    function print_media_templates_old()
    {

        $default_gallery_type = apply_filters('italystrap_default_gallery_type', 'default');

        echo '<script type="text/html" id="tmpl-italystrap-gallery-settings">';


        ?><style>.setting select{float: right;}</style><?php

            $instance_old = [];

            $instance_old = wp_parse_args((array) $instance_old, (array) $this->fields_old);

            $instance_old['name'] = $instance_old['name'] . $this->randID;

?>
            <label class="setting">
                <span><?php esc_attr_e('Type', 'italystrap'); ?></span>
                <select class="type" name="type" data-setting="type">
                    <?php foreach ($this->gallery_types as $value => $caption) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $default_gallery_type); ?>><?php echo esc_html($caption); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div id="italystrap-carousel-option">
        <?php
        /**
         * Instance of list of image sizes
         *
         * @var ItalyStrapAdminMediaSettings
         */
        // $image_size_media = new ItalyStrapAdminMediaSettings;
        $image_size_media_array = $this->image_size_media->get_image_sizes();

        foreach ($this->fields_old as $key => $label) {
            $instance_old[ $key ] ??= '';

            /**
             * Save select in widget
             *
             * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
             *
             * Display select only if is schema
             */
            if ('ids' === $key || 'type' === $key || 'size' === $key) {
            } elseif ('sizetablet' === $key || 'sizephone' === $key) {
                ?>
                <?php $saved_option = $instance_old[ $key ] ?? '' ; ?>

                <label class="setting" for="<?php echo esc_attr($key); ?>">
                    <span><?php echo esc_attr($key); ?></span>
                    <select class="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" data-setting="<?php echo esc_attr($key); ?>">
                        <!-- <option value="" disabled selected>Select your option</option> -->
                        <?php
                        $option = '';

                        foreach ($image_size_media_array as $k => $v) {
                            $option .= '<option ' . ( selected($k, $saved_option) ) . ' value="' . esc_attr($k) . '">' . esc_html($v) . '</option>';
                        }

                        echo $option; // XSS ok.
                        ?>
                    </select>
                </label>
                <?php
            } else {
                ?>

                <?php if (isset($this->carousel_options[ $key ]) && is_array($this->carousel_options[ $key ])) :  ?>
                    <?php $saved_option = $instance_old[ $key ] ?? '' ; ?>

                    <label class="setting" for="<?php echo esc_attr($key); ?>">
                        <span><?php echo esc_attr($key); ?></span>
                        <select class="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" data-setting="<?php echo esc_attr($key); ?>">
                            <!-- <option value="" disabled selected>Select your option</option> -->
                            <?php
                            $option = '';

                            foreach ($this->carousel_options[ $key ] as $k => $v) {
                                $option .= '<option ' . ( selected($k, $saved_option) ) . ' value="' . $k . '">' . $v . '</option>';
                            }

                            echo $option;
                            ?>
                        </select>
                    </label>

                <?php elseif (isset($this->carousel_options[ $key ])) :
                    if ('true' === $instance_old[ $key ]) {
                        $instance_old[ $key ] = true;
                    }
                    ?>
                    <label class="setting" for="<?php echo esc_attr($key); ?>">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value='1' data-setting="<?php echo esc_attr($key); ?>" <?php checked($instance_old[ $key ], true); ?>>
                        <span><?php echo esc_attr($key); ?></span>
                    </label>

                <?php else : ?>
                    <label class="setting" for="<?php echo esc_attr($key); ?>">
                        <span><?php echo esc_attr($key); ?></span>
                        <input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($label); ?>" data-setting="<?php echo esc_attr($key); ?>" placeholder="<?php echo esc_attr($label); ?>">
                    </label>

                <?php endif; ?>

            <?php } //!- else.
        }//!- foreach.

        ?>
                <!-- <p><?php esc_attr_e('For more informations about the options below see the <a href="admin.php?page=italystrap-documentation#carousel" target="_blank">documentation.</a>', 'italystrap'); ?></p>
                <label class="setting">
                    <span><?php esc_attr_e('Indicators', 'italystrap'); ?></span>
                    <select class="indicators" name="indicators" data-setting="indicators">
                        <option value="" disabled selected>Select your option</option>
                        <?php foreach ($this->indicators as $value) : ?>
                            <option value="<?php if ('before-inner' !== $value) {
                                echo esc_attr($value);
                                           } ?>" ><?php echo esc_html($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="setting">
                    <span><?php esc_attr_e('HTML ID', 'italystrap'); ?></span>
                    <input type="text" name="name" value="" data-setting="name" placeholder="eg. myCssID">
                </label>
                <label class="setting">
                    <span><?php esc_attr_e('Interval', 'italystrap'); ?></span>
                </label>
                <input type="text" name="interval" value="" data-setting="interval" placeholder="5000"> -->

        <?php

        echo '</div></script>';
    }

    /**
     * Outputs a view template which can be used with wp.media.template
     */
    function print_media_templates()
    {

        $output = '';

        $default_gallery_type = apply_filters('italystrap_default_gallery_type', 'default');

        $instance = [];

        $key = [
            'name'      => __('Type', 'italystrap'),
            'desc'      => __('Enter the widget class name.', 'italystrap'),
            'id'        => 'type',
            '_id'       => 'type',
            '_name'     => 'type',
            'type'      => 'select',
            'class'     => 'widefat',
            'data-setting' => 'type',
            // 'class-p'    => 'setting',
            'default'   => $default_gallery_type,
            'options'   => $this->gallery_types,
        ];

        $output = sprintf(
            '<script type="text/html" id="tmpl-italystrap-gallery-settings">%s<div id="italystrap-carousel-option">%s</div></script>',
            $this->fields_type->get_field_type($key, $instance),
            $this->get_field_types($this->fields)
        );
        printf(
            '<style>.kint{margin-left: 180px !important;}</style>'
        );

        echo $output; // XSS ok.
    }

    /**
     * Create Fields
     *
     * Creates each field defined.
     *
     * @access protected
     * @param  array  $fields The fields array.
     * @param  string $out    The HTML form output.
     * @return string         Return the HTML Fields
     */
    protected function get_field_types(array $fields, $out = '')
    {

        foreach ($fields as $key) {
            $out .= $this->get_field_type($key);
        }

        return $out;
    }

    /**
     * Create the Fields
     *
     * @access protected
     * @param  array  $key The key of field's array to create the HTML field.
     *
     * @return string      Return the HTML Fields
     */
    protected function get_field_type(array $key)
    {

        /**
         * Set field id and name
         */
        $key['_id'] = $key['_name'] = $key['attributes']['data-setting'] = $key['id'];

        return $this->fields_type->get_field_type($key, []);
    }
}
