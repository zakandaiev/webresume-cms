<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$name = null;
if(isset($_POST["name"]) && !empty($_POST["name"])) {
	$name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
}
$content = null;
if(isset($_POST["content"]) && !empty($_POST["content"])) {
	$content = json_decode(trim($_POST["content"]), true);
}

if($name != "sort_code") {
	serverSendAnswer(0, "Such a name does not exist");
}

$update_query = $pdo->prepare("
	UPDATE `{$prefix}_code` SET
		`order`=:order
	WHERE `id`=:code_id;
");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(is_array($content) && !empty($content)) {
	foreach ($content as $item) {
		$update_query->bindParam(":code_id", $item["code_id"], PDO::PARAM_INT);
		$update_query->bindParam(":order", $item["order"], PDO::PARAM_INT);
		try {
			$update_query->execute();
		} catch(PDOException $error) { 
			serverSendAnswer(0, $error->getMessage());
		}
	}
}

serverSendAnswer(1, "Saved");