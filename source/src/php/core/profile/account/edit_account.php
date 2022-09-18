<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$login = null;
if(isset($_POST["login"]) && !empty($_POST["login"])) {
	$login = trim($_POST["login"]);
}
$email = null;
if(isset($_POST["email"]) && !empty($_POST["email"])) {
	$email = trim($_POST["email"]);
}

validateLogin($login);
validateEmail($email);

$update_query = $pdo->prepare("
	UPDATE {$prefix}_users SET
		login=:login,
		email=:email,
		login_hash=:login_hash
	WHERE id=:uid;
");

$login_hash = generateHash($user["login"]);

$update_query->bindParam(":login", $login);
$update_query->bindParam(":email", $email);
$update_query->bindParam(":login_hash", $login_hash);
$update_query->bindParam(":uid", $user["id"]);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$update_query->execute();
	if ($update_query->rowCount() > 0) {
		setUserCookie("login_hash", $login_hash, 7);
		serverSendAnswer(1, "Saved");
	} else {
		serverSendAnswer(0, "You haven't changed anything");
	}
} catch(PDOException $error) {
	if (preg_match("/Duplicate entry .+ for key '(.+)'/", $error->getMessage(), $matches)) {
		$arr_column_names = array(
			"id" => "user ID",
			"login" => "login",
			"email" => "email"
		);
		if (!array_key_exists($matches[1], $arr_column_names)) {
			$column_name = $matches[1];
		} else {
			$column_name = $arr_column_names[$matches[1]];
		}
		serverSendAnswer(0, "This $column_name is already taken");
	} else {
		serverSendAnswer(0, $error->getMessage());
	}
}
