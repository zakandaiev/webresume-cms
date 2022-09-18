<?php

global $pdo, $prefix, $user;

if(!is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$message = trim($_POST["message"]);

validateContactName($name);
validateEmail($email);
validateContactMessage($message);

$subject = "New message from {$_SERVER['HTTP_HOST']}";
$message = "
	<p><b>Name:</b> {$name}</p>
	<p><b>Email:</b> <a href='mailto:{$email}'>{$email}</a></p>
	<p><b>Message:</b> {$message}</p>
	<p>Sent automatically from <a href='{$GLOBALS['site_url']}'>{$_SERVER['HTTP_HOST']}</a></p>
";

if(sendMail($GLOBALS["person_email"], $subject, $message, $email)) {
	serverSendAnswer(1, "Message sent");
}

serverSendAnswer(0, "Unknown error");
