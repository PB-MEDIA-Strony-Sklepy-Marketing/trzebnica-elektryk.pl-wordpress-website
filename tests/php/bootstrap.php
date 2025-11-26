<?php
/**
 * PHPUnit Bootstrap
 */

define('VOLTMONT_TESTS', true);

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return strip_tags($str);
    }
}

echo "\nVoltmont PHPUnit Bootstrap Loaded\n\n";
