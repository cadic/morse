<?php
/**
 * Plugin Name: Morse
 * Description: Sends messages from forms to Telegram Messenger
 * Plugin URI: https://heartwp.com/morse
 * Author: Max Lyuchin
 * Author URI: https://heartwp.com/max
 * Version: 1.2
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: morse
 */

defined( 'ABSPATH' ) or exit;

include_once plugin_dir_path( __FILE__ ) . 'settings.php';
include_once plugin_dir_path( __FILE__ ) . 'telegram-functions.php';
include_once plugin_dir_path( __FILE__ ) . 'wp-mail.php';

function morse_init()
{
	$options = get_option('morse');

	load_plugin_textdomain( 'morse', false, basename( dirname(__FILE__) ).'/languages' );

	do_action( 'morse_init' );
}
add_action( 'plugins_loaded', 'morse_init' );