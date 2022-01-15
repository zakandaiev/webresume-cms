<?php

global $pdo, $prefix, $user;

if(!is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_STRING);
$message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

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