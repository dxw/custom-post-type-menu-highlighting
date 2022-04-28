<?php
/**
 * Custom Post Type Menu Highlighting
 *
 * @package     CustomPostTypeMenuHighlighting
 * @author      dxw
 * @copyright   2022
 * @license     MIT
 *
 * Plugin Name: Custom Post Type Menu Highlighting
 * Plugin URI: https://github.com/dxw/custom-post-type-menu-highlighting
 * Description: Highlight menu items as parents of specified custom post types
 * Author: dxw
 * Version: 1.0.0
 * Network: True
 */

$registrar = require __DIR__.'/src/load.php';
$registrar->register();
