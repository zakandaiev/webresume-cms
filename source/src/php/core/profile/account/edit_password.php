<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$password_current = null;
if(isset($_POST["password_current"]) && !empty($_POST["password_current"])) {
	$password_current = filter_var(trim($_POST["password_current"]), FILTER_SANITIZE_STRING);
}
$password_new = null;
if(isset($_POST["password_new"]) && !empty($_POST["password_new"])) {
	$password_new = filter_var(trim($_POST["password_new"]), FILTER_SANITIZE_STRING);
}

validatePassword($password_current);
validatePassword($password_new);

if($password_current == $password_new) {
	serverSendAnswer(0, "New password must be different");
}
if(!password_verify($password_current, $user["password"])) {
	serverSendAnswer(0, "Current password is incorrect");
}

$update_query = $pdo->prepare("
	UPDATE {$prefix}_users SET
		password=:password_hash,
		login_hash=:login_hash
	WHERE id=:uid;
");

$login_hash = generateHash($user["login"]);
$password_hash = password_hash($password_new, PASSWORD_DEFAULT);

$update_query->bindParam(":password_hash", $password_hash);
$update_query->bindParam(":login_hash", $login_hash);
$update_query->bindParam(":uid", $user["id"]);

try {
	$update_query->execute();
	if ($update_query->rowCount() > 0) {
		setUserCookie("login_hash", $login_hash, 7);
		#include_once("core/notifications/mail_password_changed.php");
		serverSendAnswer(1, "Saved");
	} else {
		serverSendAnswer(0, "You haven't changed anything");
	}
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}