<?php

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Settings\Page as P;

return [
    P::PAGE_TITLE   => \__('Dashboard', 'italystrap'),
    P::MENU_TITLE   => \__('ACM by ItalyStrap', 'italystrap'), // Mandatory
    P::SLUG         => 'italystrap-dashboard', // Mandatory
    P::ICON         => 'dashicons-performance',
    P::VIEW         => ITALYSTRAP_PLUGIN_PATH . P::DS . 'admin/view/italystrap-dashboard.php',
];
