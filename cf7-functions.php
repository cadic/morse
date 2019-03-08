<?php

function morse_cf7_send( &$contact_form )
{

	$message = "";

	$form_id = $_POST['_wpcf7'];
	
	$form = get_post( $form_id );
	
	$mail = get_post_meta( $form_id, '_mail', true );

	$mail_body = $mail['body'];

	$message = "Сообщение с формы: " . $form->post_title . "\n\n";

	foreach ($_POST as $key => $value) {
		if ( !preg_match( "/^_wpcf/", $key ) ) {
			$search = "[{$key}]";
			$mail_body = str_replace( $search, $value, $mail_body );
		}
	}

	$message .= $mail_body;

	morse_telegram_send_message( $message );

}