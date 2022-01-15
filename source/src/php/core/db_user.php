<?php

$user["is_logged"] = false;

if(isset($_COOKIE["login_hash"]) || !empty($_COOKIE["login_hash"])) {
	$user_query = $pdo->prepare("SELECT * FROM {$prefix}_users WHERE login_hash=:login_hash AND enabled is true ORDER BY cdate DESC LIMIT 1;");
	$user_query->bindParam(":login_hash", $_COOKIE["login_hash"]);
	$user_query->execute();

	$db_user = $user_query->fetch(PDO::FETCH_ASSOC);

	if(!empty($db_user)) {
		$user = $db_user;
		$user["is_logged"] = true;
	}
}