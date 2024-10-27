<?php
/**
 * Plugin Name: AD Publisher
 * Plugin URI - http://ad-publisher.com
 * Version: 1.1.0
 * Author: Adsstudio
 * Description: Plugin for automatic insertion of the ad code
 * License: GPL2
 * Text Domain: ad-publisher
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( 'ADP_PLUGIN_PATH' ) ) {
	define( 'ADP_PLUGIN_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'ADP_PLUGIN_URL' ) ) {
	define( 'ADP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! class_exists( 'ADP_Plugin' ) ) {
	include_once ADP_PLUGIN_PATH . '/includes/classes/class-adp-plugin.php';
}

ADP_Plugin::instance();
