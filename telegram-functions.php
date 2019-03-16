<?php

function morse_telegram_send_message( $message )
{
	$options = get_option('morse');

	$api_url = sprintf("https://api.telegram.org/bot%s/sendMessage", $options['telegram_api_token'] );

	$response = wp_remote_post( $api_url, array(
		'body' => array(
			'chat_id' => $options['telegram_channelusername'],
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => true,
			'text' => $message,
		),
	) );

	if ( is_wp_error( $response ) ) {
		return $response;
	} else {
		return $response;
	}
}