<?php

/**
 * Widget API: Widget Visibility
 *
 * Forked from Jetpack module Widget conditions.
 *
 * @link www.italystrap.com
 * @since 2.3.0
 *
 * @package ItalyStrap\Widget
 */

namespace ItalyStrap\Widgets\Visibility;

/**
 * Visibility_Admin
 */
class Visibility_Admin extends Visibility_Base
{
    public static function init()
    {

        add_action('sidebar_admin_setup', [self::class, 'widget_admin_setup']);
        add_filter('widget_update_callback', [self::class, 'widget_update'], 10, 3);
        add_action('in_widget_form', [self::class, 'widget_conditions_admin'], 10, 3);
        add_action('wp_ajax_widget_conditions_options', [self::class, 'widget_conditions_options']);
        add_action('wp_ajax_widget_conditions_has_children', [self::class, 'widget_conditions_has_children']);
    }

    public static function widget_admin_setup()
    {
        if (is_rtl()) {
            wp_enqueue_style('widget-conditions', plugins_url('assets/rtl/widget-conditions-rtl.css', __FILE__));
        } else {
            wp_enqueue_style('widget-conditions', plugins_url('assets/widget-conditions.css', __FILE__));
        }
        wp_enqueue_style('widget-conditions', plugins_url('assets/widget-conditions.css', __FILE__));
        wp_enqueue_script('widget-conditions', plugins_url('assets/widget-conditions.js', __FILE__), ['jquery', 'jquery-ui-core'], 20_140_721, true);
    }

    /**
     * On an AJAX update of the widget settings, process the display conditions.
     *
     * @param array $new_instance New settings for this instance as input by the user.
     * @param array $old_instance Old settings for this instance.
     * @return array Modified settings.
     */
    public static function widget_update($instance, $new_instance, $old_instance)
    {
        if (empty($_POST['conditions'])) {
            return $instance;
        }

        $post_conditions = $_POST['conditions'];

        $conditions = [];
        $conditions['action'] = sanitize_text_field($post_conditions['action']);
        $conditions['rules'] = [];

        foreach ($post_conditions['rules_major'] as $index => $major_rule) {
            if (! $major_rule) {
                continue;
            }

            $conditions['rules'][] = ['major' => sanitize_text_field($major_rule), 'minor' => isset($post_conditions['rules_minor'][ $index ]) ? sanitize_text_field($post_conditions['rules_minor'][ $index ]) : '', 'has_children' => isset($post_conditions['page_children'][ $index ]) ? true : false];
        }

        if (! empty($conditions['rules'])) {
            $instance['conditions'] = $conditions;
        } else {
            unset($instance['conditions']);
        }

        if (
                ( isset($instance['conditions']) && ! isset($old_instance['conditions']) )
                ||
                (
                    isset($instance['conditions'], $old_instance['conditions'])
                    &&
                    serialize($instance['conditions']) != serialize($old_instance['conditions'])
                )
        ) {

            /**
             * Fires after the widget visibility conditions are saved.
             *
             * @module widget-visibility
             *
             * @since 2.4.0
             */
            do_action('widget_conditions_save');
        } elseif (! isset($instance['conditions']) && isset($old_instance['conditions'])) {

            /**
             * Fires after the widget visibility conditions are deleted.
             *
             * @module widget-visibility
             *
             * @since 2.4.0
             */
            do_action('widget_conditions_delete');
        }

        return $instance;
    }

    /**
     * Add the widget conditions to each widget in the admin.
     *
     * @param $widget unused.
     * @param $return unused.
     * @param array $instance The widget settings.
     */
    public static function widget_conditions_admin($widget, $return, array $instance = [])
    {

        $conditions = [];

        if (isset($instance['conditions'])) {
            $conditions = $instance['conditions'];
        }

        if (! isset($conditions['action'])) {
            $conditions['action'] = 'show';
        }

        if (empty($conditions['rules'])) {
            $conditions['rules'][] = ['major' => '', 'minor' => '', 'has_children' => ''];
        }

        $widget_conditional = '';
        if (empty($_POST['widget-conditions-visible']) || '0' == $_POST['widget-conditions-visible']) {
            $widget_conditional = 'widget-conditional-hide';
        }

        $widget_conditions_visible_value = 0;
        if (isset($_POST['widget-conditions-visible'])) {
            $widget_conditions_visible_value = (int) $_POST['widget-conditions-visible'];
        }
        ?>
        <div class="widget-conditional <?php echo $widget_conditional; // XSS ok. ?>">

            <input type="hidden" name="widget-conditions-visible" value="<?php echo $widget_conditions_visible_value; // XSS ok. ?>" />

            <?php
            if (! isset($_POST['widget-conditions-visible'])) {
                ?><a href="#" class="button display-options"><?php _e('Visibility', 'italystrap'); ?></a><?php
            }
            ?>
            <div class="widget-conditional-inner">
                <div class="condition-top">
                    <?php printf(
                        _x('%s if:', 'placeholder: dropdown menu to select widget visibility; hide if or show if', 'italystrap'),
                        '<select name="conditions[action]"><option value="show" ' . selected($conditions['action'], 'show', false) . '>' . esc_html_x('Show', 'Used in the "%s if:" translation for the widget visibility dropdown', 'italystrap') . '</option><option value="hide" ' . selected($conditions['action'], 'hide', false) . '>' . esc_html_x('Hide', 'Used in the "%s if:" translation for the widget visibility dropdown', 'italystrap') . '</option></select>'
                    ); ?>
                </div><!-- .condition-top -->

                <div class="conditions">
                    <?php

                    foreach ($conditions['rules'] as $rule) {
                        $rule = wp_parse_args($rule, ['major' => '', 'minor' => '', 'has_children' => '']);
                        ?>
                        <div class="condition">
                            <div class="selection alignleft">
                                <select class="conditions-rule-major" name="conditions[rules_major][]">
                                    <option value="" <?php selected("", $rule['major']); ?>><?php echo esc_html_x('-- Select --', 'Used as the default option in a dropdown list', 'italystrap'); ?></option>
                                    <option value="category" <?php selected("category", $rule['major']); ?>><?php esc_html_e('Category', 'italystrap'); ?></option>
                                    <option value="author" <?php selected("author", $rule['major']); ?>><?php echo esc_html_x('Author', 'Noun, as in: "The author of this post is..."', 'italystrap'); ?></option>

                                    <?php if (! ( defined('IS_WPCOM') && IS_WPCOM )) { // this doesn't work on .com because of caching ?>
                                        <option value="loggedin" <?php selected("loggedin", $rule['major']); ?>><?php echo esc_html_x('User', 'Noun', 'italystrap'); ?></option>
                                        <option value="role" <?php selected("role", $rule['major']); ?>><?php echo esc_html_x('Role', 'Noun, as in: "The user role of that can access this widget is..."', 'italystrap'); ?></option>
                                    <?php } ?>

                                    <option value="tag" <?php selected("tag", $rule['major']); ?>><?php echo esc_html_x('Tag', 'Noun, as in: "This post has one tag."', 'italystrap'); ?></option>
                                    <option value="date" <?php selected("date", $rule['major']); ?>><?php echo esc_html_x('Date', 'Noun, as in: "This page is a date archive."', 'italystrap'); ?></option>
                                    <option value="page" <?php selected("page", $rule['major']); ?>><?php echo esc_html_x('Page', 'Example: The user is looking at a page, not a post.', 'italystrap'); ?></option>
                                    <option value="post_type" <?php selected("post_type", $rule['major']); ?>><?php echo esc_html_x('Post Type', 'Example: the user is viewing a custom post type archive.', 'italystrap'); ?></option>
                                    <?php if (get_taxonomies(['_builtin' => false])) : ?>
                                        <option value="taxonomy" <?php selected("taxonomy", $rule['major']); ?>><?php echo esc_html_x('Taxonomy', 'Noun, as in: "This post has one taxonomy."', 'italystrap'); ?></option>
                                    <?php endif; ?>
                                </select>

                                <?php _ex('is', 'Widget Visibility: {Rule Major [Page]} is {Rule Minor [Search results]}', 'italystrap'); ?>

                                <select class="conditions-rule-minor" name="conditions[rules_minor][]" <?php if (! $rule['major']) {
                                    ?> disabled="disabled"<?php
                                                                                                       } ?> data-loading-text="<?php esc_attr_e('Loading...', 'italystrap'); ?>">
                                    <?php self::widget_conditions_options_echo($rule['major'], $rule['minor']); ?>
                                </select>

                                <span class="conditions-rule-has-children">
                                    <?php self::widget_conditions_has_children_echo($rule['major'], $rule['minor'], $rule['has_children']); ?>
                                </span>
                            </div>

                            <div class="condition-control">
                                <span class="condition-conjunction"><?php echo esc_html_x('or', 'Shown between widget visibility conditions.', 'italystrap'); ?></span>
                                <div class="actions alignright">
                                    <a href="#" class="delete-condition"><?php esc_html_e('Delete', 'italystrap'); ?></a> | <a href="#" class="add-condition"><?php esc_html_e('Add', 'italystrap'); ?></a>
                                </div>
                            </div>

                        </div><!-- .condition -->
                        <?php
                    }

                    ?>
                </div><!-- .conditions -->
            </div><!-- .widget-conditional-inner -->
        </div><!-- .widget-conditional -->
        <?php
    }

    /**
     * Provided a second level of granularity for widget conditions.
     */
    public static function widget_conditions_options_echo($major = '', $minor = '')
    {
        if (in_array($major, ['category', 'tag']) && is_numeric($minor)) {
            $minor = self::maybe_get_split_term($minor, $major);
        }

        switch ($major) {
            case 'category':
                ?>
                <option value=""><?php _e('All category pages', 'italystrap'); ?></option>
                <?php

                $categories = get_categories(['number' => 1000, 'orderby' => 'count', 'order' => 'DESC']);
                usort($categories, [self::class, 'strcasecmp_name']);

                foreach ($categories as $category) {
                    ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected($category->term_id, $minor); ?>><?php echo esc_html($category->name); ?></option>
                    <?php
                }
                break;
            case 'loggedin':
                ?>
                <option value="loggedin" <?php selected('loggedin', $minor); ?>><?php _e('Logged In', 'italystrap'); ?></option>
                <option value="loggedout" <?php selected('loggedout', $minor); ?>><?php _e('Logged Out', 'italystrap'); ?></option>
                <?php
                break;
            case 'author':
                ?>
                <option value=""><?php _e('All author pages', 'italystrap'); ?></option>
                <?php

                foreach (get_users(['orderby' => 'name', 'exclude_admin' => true]) as $author) {
                    ?>
                    <option value="<?php echo esc_attr($author->ID); ?>" <?php selected($author->ID, $minor); ?>><?php echo esc_html($author->display_name); ?></option>
                    <?php
                }
                break;
            case 'role':
                global $wp_roles;

                foreach ($wp_roles->roles as $role_key => $role) {
                    ?>
                    <option value="<?php echo esc_attr($role_key); ?>" <?php selected($role_key, $minor); ?> ><?php echo esc_html($role['name']); ?></option>
                    <?php
                }
                break;
            case 'tag':
                ?>
                <option value=""><?php _e('All tag pages', 'italystrap'); ?></option>
                <?php

                $tags = get_tags(['number' => 1000, 'orderby' => 'count', 'order' => 'DESC']);
                usort($tags, [self::class, 'strcasecmp_name']);

                foreach ($tags as $tag) {
                    ?>
                    <option value="<?php echo esc_attr($tag->term_id); ?>" <?php selected($tag->term_id, $minor); ?>><?php echo esc_html($tag->name); ?></option>
                    <?php
                }
                break;
            case 'date':
                ?>
                <option value="" <?php selected('', $minor); ?>><?php _e('All date archives', 'italystrap'); ?></option>
                <option value="day"<?php selected('day', $minor); ?>><?php _e('Daily archives', 'italystrap'); ?></option>
                <option value="month"<?php selected('month', $minor); ?>><?php _e('Monthly archives', 'italystrap'); ?></option>
                <option value="year"<?php selected('year', $minor); ?>><?php _e('Yearly archives', 'italystrap'); ?></option>
                <?php
                break;
            case 'page':
                // Previously hardcoded post type options.
                if (! $minor) {
                    $minor = 'post_type-page';
                } elseif ('post' == $minor) {
                    $minor = 'post_type-post';
                }

                ?>
                <option value="front" <?php selected('front', $minor); ?>><?php _e('Front page', 'italystrap'); ?></option>
                <option value="posts" <?php selected('posts', $minor); ?>><?php _e('Posts page', 'italystrap'); ?></option>
                <option value="archive" <?php selected('archive', $minor); ?>><?php _e('Archive page', 'italystrap'); ?></option>
                <option value="404" <?php selected('404', $minor); ?>><?php _e('404 error page', 'italystrap'); ?></option>
                <option value="search" <?php selected('search', $minor); ?>><?php _e('Search results', 'italystrap'); ?></option>
                <optgroup label="<?php esc_attr_e('Post type:', 'italystrap'); ?>">
                    <?php

                    $post_types = get_post_types(['public' => true, '_builtin' => true], 'objects');

                    foreach ($post_types as $post_type) {
                        ?>
                        <option value="<?php echo esc_attr('post_type-' . $post_type->name); ?>" <?php selected('post_type-' . $post_type->name, $minor); ?>><?php echo esc_html($post_type->labels->singular_name); ?></option>
                        <?php
                    }

                    ?>
                </optgroup>
                <optgroup label="<?php esc_attr_e('Static page:', 'italystrap'); ?>">
                    <?php

                    echo str_replace(' value="' . esc_attr($minor) . '"', ' value="' . esc_attr($minor) . '" selected="selected"', preg_replace('/<\/?select[^>]*?>/i', '', wp_dropdown_pages(['echo' => false])));

                    ?>
                </optgroup>
                <?php
                break;
            case 'taxonomy':
                ?>
                <option value=""><?php _e('All taxonomy pages', 'italystrap'); ?></option>
                <?php
                $taxonomies = get_taxonomies(
                    /**
                     * Filters args passed to get_taxonomies.
                     *
                     * @see https://developer.wordpress.org/reference/functions/get_taxonomies/
                     *
                     * @since 4.4.0
                     *
                     * @module widget-visibility
                     *
                     * @param array $args Widget Visibility taxonomy arguments.
                     */
                    apply_filters('italystrap_widget_visibility_tax_args', ['_builtin' => false]),
                    'objects'
                );
                usort($taxonomies, [self::class, 'strcasecmp_name']);

                $parts = explode('_tax_', $minor);

                if (2 === count($parts)) {
                    $minor_id = self::maybe_get_split_term($parts[1], $parts[0]);
                    $minor = $parts[0] . '_tax_' . $minor_id;
                }

                foreach ($taxonomies as $taxonomy) {
                    ?>
                    <optgroup label="<?php esc_attr_e($taxonomy->labels->name . ':', 'italystrap'); ?>">
                        <option value="<?php echo esc_attr($taxonomy->name); ?>" <?php selected($taxonomy->name, $minor); ?>>
                            <?php _e('All pages', 'italystrap'); ?>
                        </option>
                    <?php

                    $terms = get_terms([$taxonomy->name], ['number' => 250, 'hide_empty' => false]);
                    foreach ($terms as $term) {
                        ?>
                        <option value="<?php echo esc_attr($taxonomy->name . '_tax_' . $term->term_id); ?>" <?php selected($taxonomy->name . '_tax_' . $term->term_id, $minor); ?>><?php echo esc_html($term->name); ?></option>
                        <?php
                    }

                    ?>
                </optgroup>
                    <?php
                }
                break;

            case 'post_type':
                ?>
                <optgroup label="<?php echo esc_attr_x('Single post:', 'a heading for a list of custom post types', 'italystrap'); ?>">
                    <?php

                    $post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');

                    foreach ($post_types as $post_type) {
                        ?>
                        <option
                                value="<?php echo esc_attr('post_type-' . $post_type->name); ?>"
                                <?php selected('post_type-' . $post_type->name, $minor); ?>>
                            <?php echo esc_html($post_type->labels->singular_name); ?>
                        </option>
                        <?php
                    }

                    ?>
                </optgroup>
                <optgroup label="<?php echo esc_attr_x('Archive page:', 'a heading for a list of custom post archive pages', 'italystrap'); ?>">
                    <?php

                    $post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');

                    foreach ($post_types as $post_type) {
                        ?>
                        <option
                                value="<?php echo esc_attr('post_type_archive-' . $post_type->name); ?>"
                                <?php selected('post_type_archive-' . $post_type->name, $minor); ?>>
                            <?php
                                echo sprintf(
                                    /* translators: %s is a plural name of the custom post type, i.e. testimonials */
                                    _x(
                                        'Archive of %s',
                                        'a label in the list of custom post type archive pages',
                                        'italystrap'
                                    ),
                                    $post_type->labels->name
                                );
                            ?>
                        </option>
                        <?php
                    }

                    ?>
                </optgroup>
                <?php
                break;
        }
    }

    /**
     * This is the AJAX endpoint for the second level of conditions.
     */
    public static function widget_conditions_options()
    {
        self::widget_conditions_options_echo(esc_attr($_REQUEST['major']), isset($_REQUEST['minor']) ? esc_attr($_REQUEST['minor']) : '');
        die;
    }

    /**
     * Provide an option to include children of pages.
     */
    public static function widget_conditions_has_children_echo($major = '', $minor = '', $has_children = false)
    {
        if (! $major || 'page' !== $major || ! $minor) {
            return null;
        }

        if ('front' == $minor) {
            $minor = get_option('page_on_front');
        }

        if (! is_numeric($minor)) {
            return null;
        }

        $page_children = get_pages(['child_of' => (int) $minor]);

        if ($page_children) {
            ?>
            <label>
                <input type="checkbox" id="include_children" name="conditions[page_children][]" value="has" <?php checked($has_children, true); ?> />
                <?php echo esc_html_x("Include children", 'Checkbox on Widget Visibility if choosen page has children.', 'italystrap'); ?>
            </label>
            <?php
        }
    }

    /**
     * This is the AJAX endpoint for the has_children input.
     */
    public static function widget_conditions_has_children()
    {
        self::widget_conditions_has_children_echo(esc_attr($_REQUEST['major']), isset($_REQUEST['minor']) ? esc_attr($_REQUEST['minor']) : '', isset($_REQUEST['has_children']) ? esc_attr($_REQUEST['has_children']) : false);
        die;
    }
}
