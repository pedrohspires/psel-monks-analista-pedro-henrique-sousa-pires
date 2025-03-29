<?php

$theme_inc_path = get_template_directory() . '/inc/';

$files_to_include = [
    'scripts.php',
    'custom-post-types.php',
    'meta-box.php',
];

foreach ($files_to_include as $file)
    require_once $theme_inc_path . $file;
