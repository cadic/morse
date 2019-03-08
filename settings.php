<?php

function morse_create_menu()
{
	//create new top-level menu
	add_options_page( 'Morse', 'Morse', 'administrator', 'morse', 'morse_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'morse_register_settings' );
}
add_action( 'admin_menu', 'morse_create_menu' );

function morse_register_settings()
{
	add_settings_section(
		'morse-settings', // ID
		__( 'Morse Settings', 'morse' ), // Title
		'morse_print_section_info', // Callback
		'morse' // Page
	);

	register_setting( 'morse', 'morse', array( 'sanitize' => 'morse_sanitize_settings' ) );

	add_settings_field(
		'morse_telegram_api_token',
		__( 'Telegram bot API token', 'morse' ),
		'morse_field_telegram_api_token',
		'morse',
		'morse-settings'
	);

	add_settings_field(
		'morse_telegram_channelusername',
		__( 'Telegram <code>@channelusername</code>', 'morse' ),
		'morse_field_telegram_channelusername',
		'morse',
		'morse-settings'
	);

	add_settings_field(
		'morse_field_handle_admin_mail',
		__( 'Handle all mail to administrator address', 'morse' ),
		'morse_field_handle_admin_mail',
		'morse',
		'morse-settings'
	);

/*
	add_settings_field(
		'morse_skip_admin_mail',
		__( 'Disable actual mail if handled with Morse', 'morse' ),
		'morse_field_skip_admin_mail',
		'morse',
		'morse-settings'
	);

	add_settings_field(
		'morse_cf7_enable',
		__( 'Enable Contact Form 7 processing', 'morse' ),
		'morse_field_cf7_enable',
		'morse',
		'morse-settings'
	);

	add_settings_field(
		'morse_cf7_skip_mail',
		__( 'Skip Contact Form 7 Email', 'morse' ),
		'morse_field_cf7_skip_mail',
		'morse',
		'morse-settings'
	);
*/
	
	/**
	 * Add more settings hook
	 */
	do_action( 'morse_register_settings' );
}

function morse_print_section_info()
{
	$options = get_option( 'morse' );
}

function morse_field_telegram_api_token()
{
	$options = get_option( 'morse' );

	printf(
		'<input type="text" id="morse_telegram_api_token" name="morse[telegram_api_token]" value="%s" />',
		isset( $options['telegram_api_token'] ) ? esc_attr( $options['telegram_api_token']) : ''
	);
}

function morse_field_telegram_channelusername()
{
	$options = get_option( 'morse' );

	printf(
		'<input type="text" id="morse_telegram_channelusername" name="morse[telegram_channelusername]" value="%s" />',
		isset( $options['telegram_channelusername'] ) ? esc_attr( $options['telegram_channelusername']) : ''
	);
}

function morse_field_handle_admin_mail()
{
	$options = get_option( 'morse' );

	$admin_email = morse_hide_email( get_option( 'admin_email' ) );

	printf(
		'<input type="checkbox" id="morse_handle_admin_mail" name="morse[handle_admin_mail]" %s />',
		checked( $options['handle_admin_mail'], 'on', false )
	);

	echo __( 'This will catch all mail sent to <code>'.$admin_email.'</code>', 'morse' );
}

function morse_field_skip_admin_mail()
{
	$options = get_option( 'morse' );

	printf(
		'<input type="checkbox" id="morse_skip_admin_mail" name="morse[skip_admin_mail]" %s %s />',
		checked( $options['skip_admin_mail'], 'on', false ),
		disabled( $options['handle_admin_mail'], false, false )
	);

	if (!$options['handle_admin_mail']) {
		echo __( 'Enable <code>Handle all</code> first!', 'morse' );
	}
}

function morse_field_cf7_enable()
{
	$options = get_option( 'morse' );

	if ( !defined('WPCF7_VERSION') ) {
		echo __( 'Please install and activate <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> plugin to use this feature.', 'morse' );
	}

	printf(
		'<input type="checkbox" id="morse_cf7_enable" name="morse[cf7_enable]" %s %s />',
		checked( $options['cf7_enable'], 'on', false ),
		disabled( defined('WPCF7_VERSION'), false, false )
	);
}

function morse_field_cf7_skip_mail()
{
	$options = get_option( 'morse' );

	printf(
		'<input type="checkbox" id="morse_cf7_skip_mail" name="morse[cf7_skip_mail]" %s %s />',
		checked( $options['cf7_skip_mail'], 'on', false ),
		disabled( defined('WPCF7_VERSION'), false, false )
	);
}

function morse_sanitize_settings( $input )
{
	$sanitized = array();

	if ( isset( $input['telegram_api_token'] ) ) {
		$sanitized['telegram_api_token'] = $input['telegram_api_token'];
	}

	if ( isset( $input['telegram_channelusername'] ) ) {
		$sanitized['telegram_channelusername'] = $input['telegram_channelusername'];
	}

	if ( isset( $input['cf7_enable'] ) ) {
		$sanitized['cf7_enable'] = $input['cf7_enable'];
	}

	/**
	 * Sanitize additional settings fields
	 * @var (array) Sanitised fields array
	 * @var (array) Input fields array
	 */
	$sanitized = apply_filters( 'morse_sanitize_settings', $sanitized, $input );

	return $sanitized;
}

function morse_hide_email( $email )
{
    $mail_parts = explode("@", $email);
    $length = strlen($mail_parts[0]);
    $show = floor($length/2);
    $hide = $length - $show;
    $replace = str_repeat("*", $hide);

    return substr_replace ( $mail_parts[0] , $replace , $show, $hide ) . "@" . substr_replace($mail_parts[1], "**", 0, 2);
}

function morse_settings_page()
{
?>
<div class="wrap">

<h1><?php _e( 'Morse' ) ?></h1>

<form method="post" action="options.php">

<?php settings_fields( 'morse' ); ?>

<?php do_settings_sections( 'morse' ); ?>

<?php submit_button(); ?>

</form>

</div>
<?php
}