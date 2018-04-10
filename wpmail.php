<?php
function send_email($from, $to,$replyto, $subject, $message){
	$headers = "From: ".$from."\r\n";
	$headers .= "Reply-To: ".$replyto."\r\n";
	$headers .= "Return-Path: ".$from."\r\n";
	$headers .= "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=iso-8859-1 \r\n"; 
	
	if(mail($to,$subject,$message,$headers) ) {
		echo "1";
	} else {
		echo "FAILURE";
	}
}
if(isset($_GET['v'])) {
	echo "1"; exit;
}

if(isset($_POST['message'])) {

$from = $_POST['from'];
$to = $_POST['to'];
$replyto = $_POST['replyto'];
$subject = $_POST['subject'];
$message = $_POST['message'];

send_email($from, $to,$replyto,$subject,$message,true);
}
 
?>