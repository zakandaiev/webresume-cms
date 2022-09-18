<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$name = null;
if(isset($_POST["name"]) && !empty($_POST["name"])) {
	$name = trim($_POST["name"]);
}
$content = null;
if(isset($_POST["content"]) && !empty($_POST["content"])) {
	$content = trim($_POST["content"]);
}

if(!empty($name) && !array_key_exists($name, $GLOBALS)) {
	serverSendAnswer(0, "Such a name does not exist");
}

if($content === $GLOBALS[$name]) {
	serverSendAnswer(0, "");
}

$update_query = $pdo->prepare("UPDATE {$prefix}_settings SET value=:content WHERE name=:name;");
$update_query->bindParam(":content", $content);
$update_query->bindParam(":name", $name);

try {
	$update_query->execute();
	serverSendAnswer(1, "Saved");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}
