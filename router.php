<?php
/**
 * Router script for PHP Built-in Web Server
 * Usage: php -S localhost:8899 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file or directory exists physically (CSS, JS, images, fonts), serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Otherwise, rewrite and route through CodeIgniter's index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
