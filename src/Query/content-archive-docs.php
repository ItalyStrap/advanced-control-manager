<?php

namespace ItalyStrap;

/**
 * The template part for Knowledgebase Documentations
 * This file is for display the HTML tags header and nav
 * @package ItalyStrap
 */

use WP_Query;

?>
<div class="row docs">
    <?php

    $base_categories = get_categories(['taxonomy' => 'category', 'parent' => 0, 'hide_empty' => 0]);

    foreach ((array) $base_categories as $category) :
        if (0 === $category->count) {
            continue;
        }

        $output = '<div class="col-md-6 col-sm-6">';
        $term_link = esc_url(get_term_link($category));
        $output .= '<header><h2><i class="fa fa-folder-o"></i> <a href="' . $term_link . '">' . $category->name . '</a> <small>( ' . sprintf(_n('%1$s Article', '%1$s Articles', $category->count, 'ItalyStrap'), number_format_i18n($category->count)) . ' )</small></h2>';
        $output .= '<p>' . $category->description . '</p></header>';

        $sub_categories = get_categories(['taxonomy' => 'category', 'parent' => $category->term_id, 'hide_empty' => 0, 'number' => '5']);

        if ($sub_categories) {
            $output .= '<ul class="list-unstyled">';

            foreach ((array) $sub_categories as $sub_category) {
                $output .= '<li><i class="fa fa-folder"></i> ';
                $sub_term_link = esc_url(get_term_link($sub_category));
                $output .= '<a href="' . $sub_term_link . '">' . $sub_category->name . '</a> <small>( ' . sprintf(_n('%1$s Article', '%1$s Articles', $sub_category->count, 'ItalyStrap'), number_format_i18n($sub_category->count)) . ' )</small>';
                $output .= '</li>';
            }

            $output .= '</ul>';
        }

        $category_posts = new WP_Query([
            'posts_per_page' => 5,
            // 'cat' => $category->term_id,
            'category__in' => $category->term_id,
        ]);

        if ($category_posts->have_posts()) :
            $output .= '<ul class="list-unstyled">';

            while ($category_posts->have_posts()) :
                $category_posts->the_post();

                $output .= '<li><i class="fa fa-file-text-o"></i> ';

                $output .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';

                $output .= '</li>';
            endwhile;

            $output .= '</ul>';

            $output .= '<i class="fa fa-arrow-circle-o-right"></i> <a class="hkb-category__view-all" href="' . $term_link . '">View all</a>';
        endif;

        $output .= '</div>';

        echo $output; // XSS ok.
    endforeach;
    ?>
</div>
