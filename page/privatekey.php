<?php
include('smtp/PHPMailerAutoload.php');

//EMails Details 
$to = "ak9748564@gmail.com";
// $to2 = "akge77926@gmail.com";
$subject = "Hello This is test email";
$html = "<h1>This is html content</h1>";

echo smtp_mailer($to, $subject, $html);
// echo smtp_mailer($to2, $subject, $html);
function smtp_mailer($to, $subject, $msg)
{
	$mail = new PHPMailer();
	// $mail->SMTPDebug  = 3;
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465;
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "Elvish56676@gmail.com";
	// notifications@cocucoin.com
	$mail->Password = "eregbicztmlerneq";
	// Harsh@Singh8576
	$mail->SetFrom("notifications@cocucoin.com");
	$mail->Subject = $subject;
	$mail->Body = $msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	if (!$mail->Send()) {
		echo $mail->ErrorInfo;
	} else {
		return "<html><h1>Done</h1></html>";
	}
}
