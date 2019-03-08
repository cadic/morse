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
include_once plugin_dir_path( __FILE__ ) . 'cf7-functions.php';
include_once plugin_dir_path( __FILE__ ) . 'wp-mail.php';

function morse_init()
{
	$options = get_option('morse');

	load_plugin_textdomain( 'morse', false, basename( dirname(__FILE__) ).'/languages' );

	if ( defined('WPCF7_VERSION') && $options['cf7_enable'] ) {
		// Hook to CF7 send mail
		add_action( 'wpcf7_before_send_mail', 'morse_cf7_send', 10, 1 );

		// Skip CF7 mail
		if ( $options['cf7_skip_mail']) {
			add_filter( 'wpcf7_skip_mail', '__return_true', 10 );
		}
	}

	do_action( 'morse_init' );
}
add_action( 'plugins_loaded', 'morse_init' );