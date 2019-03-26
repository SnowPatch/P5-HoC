<?php 



	require_once('smtp/class.phpmailer.php');
	require_once('smtp/class.smtp.php');

	$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->Host = 'send.one.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'robot@swinther.com';
	$mail->Password = '********';
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;

	$mail->setFrom('robot@swinther.com', 'Qeeck Kontakt');
	$mail->addAddress($input_email); 
	$mail->addReplyTo('me@qeeck.com', 'Qeeck');

	$mail->isHTML(true); 

	$mail->Subject = 'Ny besked fra Qeeck.com';
	$mail->Body    = '
	<br><b>Fornavn:</b> '.$input_first.'
	<br><b>Efternavn:</b> '.$input_last.'
	<br><b>Email:</b> '.$input_email.'
	<br><b>Emne:</b> '.$input_subject.'
	<br><b>Land:</b> '.$input_country.'
	<br><br><b>Besked:</b><br>'.$input_message.'
	<br><br><br><b>Sendt via Qeeck.com</b>';
	$mail->AltBody = 'Besked modtaget fra '.$input_first.' '.$input_last.', sendt via Qeeck.com';

	if(!$mail->send()) {
		die("Der opstod desværre en fejl. Prøv igen senere");
	} else {
		header("Location: ../kontakt?c=green&m=Din besked er sendt. Vi svarer tilbage så hurtigt vi kan"); exit; die();
	}

?>