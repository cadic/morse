<?php

function morse_handle_all_mail( $args = array() )
{
	$options = get_option('morse');
	if ( $options['handle_admin_mail'] ) {
		
		$admin_email = get_option('admin_email');

		if ( $admin_email == $args['to'] ) {
			$clean_text = preg_replace( '/<style.*?<\/style>/is', '', $args['message'] );
			$clean_text = preg_replace( "/\n\s+/", "\n", rtrim( html_entity_decode( strip_tags( $clean_text ) ) ) );

			$result = morse_telegram_send_message( '<b>'.$args['subject']."</b>\n".$clean_text );
		}
	}

	return $args;
}
add_filter( 'wp_mail', 'morse_handle_all_mail' );