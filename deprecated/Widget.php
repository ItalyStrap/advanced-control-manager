<?php
/**
 * Class description
 */

namespace ItalyStrap\Widget;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use \WP_Widget;
use ItalyStrap\Widgets\Widget as Old_Widget;
use ItalyStrap\Fields\Fields;
use ItalyStrap\Update\Sanitization;
use ItalyStrap\Update\Validation;

use InvalidArgumentException;

class Widget extends Old_Widget {

}
