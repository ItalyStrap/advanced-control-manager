<?php

declare(strict_types=1);

$files = [
    'general-functions.php',
    'breadcrumbs.php',
    'booleans.php',
    'notice.php',
    'private.php',
];

foreach ($files as $file) {
    require $file;
}

unset($files);
