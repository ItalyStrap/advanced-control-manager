<?php

/**
 * Add ID column to post_type admin table
 *
 * @package ItalyStrap
 * @since   2.0.0
 */

namespace ItalyStrap\Admin;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

/**
 * This code is forked from the Ubik Admin WordPress library
 * Original author Alexander Synaptic https://github.com/synapticism/ubik-admin
 */


// Featured image/post thumbnail column in post list; adapted from http://www.rektproductions.com/display-featured-images-in-admin-post-list/
function posts_columns($defaults)
{
    $defaults['featured_image'] = __('Thumb', 'italystrap');
    return $defaults;
}

function posts_custom_columns($column_name, $id)
{
    if ($column_name === 'featured_image') {
        echo the_post_thumbnail([60, 60]);
    }
}

function posts_columns_style()
{
 // This is a bit of a cheap hack but we're not too concerned about back-end optimization
    ?><style type="text/css">
    .column-featured_image {
      width: 60px;
    }
    td.column-featured_image {
      text-align: center;
    }
  </style><?php
}
