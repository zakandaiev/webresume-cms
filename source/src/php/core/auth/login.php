<?php

global $pdo, $prefix, $user;

if(!is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$login = filter_var(trim($_POST["login"]), FILTER_SANITIZE_STRING);
$password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING);

validateLogin($login);
validatePassword($password);

$login_get_query = $pdo->prepare("SELECT * FROM {$prefix}_users WHERE login=:login;");
$login_get_query->bindParam(":login", $login);
$login_get_query->execute();

$user = $login_get_query->fetch(PDO::FETCH_ASSOC);

if(empty($user)) {
	serverSendAnswer(0, "Invalid login or password");
}

if(!password_verify($password, $user["password"])) {
	serverSendAnswer(0, "Invalid login or password");
}

if(!$user["enabled"]) {
	serverSendAnswer(0, "Your account has been disabled");
}

$user_ip = filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP);
$login_hash = generateHash($login);

$login_set_query = $pdo->prepare("
	UPDATE {$prefix}_users SET 
		ip=:user_ip,
		login_hash=:login_hash,
		last_sign=CURRENT_TIMESTAMP
	WHERE id=:uid;
");
$login_set_query->bindParam(":user_ip", $user_ip);
$login_set_query->bindParam(":login_hash", $login_hash);
$login_set_query->bindParam(":uid", $user["id"]);

try {
	$login_set_query->execute();
	setUserCookie("login_hash", $login_hash, 7);
	serverSendAnswer(1, "You logged in");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}