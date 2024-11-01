<?php
/*
 * WP Theme ShapeShifter Extensions
 *
 * @package     WP Theme ShapeShifter Extensions
 * @author      Nora
 * @copyright   2016 Nora https://wp-works.com
 * @license     GPL-2.0+
 * 
 * @wordpress-plugin
 * Plugin Name: WP Theme ShapeShifter Extensions
 * Plugin URI: https://wp-works.com
 * Description: Extensions for WP Theme ShapeShifter
 * Version: 1.2.7
 * Author: Nora
 * Author URI: https://wp-works.com
 * Text Domain: wp-theme-shapeshifter-extensions
 * Domain Path: /languages/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
if( ! defined( 'ABSPATH' ) ) exit;

// Degine Plugin Dir Path
if( ! defined( 'SHAPESHIFTER_EXTENSIONS_MAIN_FILE' ) ) define( 'SHAPESHIFTER_EXTENSIONS_MAIN_FILE', __FILE__ );
if( ! defined( 'SHAPESHIFTER_EXTENSIONS_DIR_PATH' ) ) define( 'SHAPESHIFTER_EXTENSIONS_DIR_PATH', plugin_dir_path( __FILE__ ) );
if( ! defined( 'SHAPESHIFTER_EXTENSIONS_DIR_URL' ) ) define( 'SHAPESHIFTER_EXTENSIONS_DIR_URL', plugin_dir_url( __FILE__ ) );

include_once( 'includes/class-shapeshifter-extensions.php' );

/**
 * Init ShapeShifter_Extensions
**/
function sse()
{
	global $shapeshifter_extensions;
	if ( ! $shapeshifter_extensions instanceof ShapeShifter_Extensions ) {
		$shapeshifter_extensions = ShapeShifter_Extensions::get_instance();
	}
	return $shapeshifter_extensions;
}
global $shapeshifter_extensions;
$shapeshifter_extensions = sse();


