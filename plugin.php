<?php
/**
 * Plugin Name: Custom Post Type Downloads
 * Plugin URI: https://horttcore.de
 * Description: Manage downloads
 * Version: v0.5
 * Author: Ralf Hortt
 * Author URI: https://horttcore.de
 * Text Domain: custom-post-type-downloads
 * Domain Path: /languages/
 * License: GPL2
 * 
 * Changelog:
 * - added gutenberg listing block # 13-08-2018 V 0.5
 */

require( 'classes/class.custom-post-type.downloads.php' );
require( 'classes/class.custom-post-type.downloads.admin.php' );
require( 'classes/class.custom-post-type.downloads.acl.php' );
require( 'classes/class.custom-post-type.downloads.shortcode.php' );
require( 'classes/class.custom-post-type.downloads.widget.php' );
require( 'includes/template-tags.php' );
require( 'includes/block.php' );
