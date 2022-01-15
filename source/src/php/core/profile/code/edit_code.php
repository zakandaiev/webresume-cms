<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$code_id = null;
if(isset($_POST["code_id"]) && !empty($_POST["code_id"])) {
	$code_id = intval(filter_var(trim($_POST["code_id"]), FILTER_SANITIZE_NUMBER_INT));
}
$title = null;
if(isset($_POST["title"]) && !empty($_POST["title"])) {
	$title = filter_var(trim($_POST["title"]), FILTER_SANITIZE_STRING);
}
$portfolio_id = null;
if(isset($_POST["portfolio_id"]) && !empty($_POST["portfolio_id"])) {
	$portfolio_id = intval(filter_var(trim($_POST["portfolio_id"]), FILTER_SANITIZE_NUMBER_INT));
}
$extension = null;
if(isset($_POST["extension"]) && !empty($_POST["extension"])) {
	$extension = filter_var(trim($_POST["extension"]), FILTER_SANITIZE_STRING);
}
$code = null;
if(isset($_POST["code"]) && !empty($_POST["code"])) {
	$code = htmlspecialchars(trim($_POST["code"]));
}
$enabled = false;
if(isset($_POST["enabled"]) && $_POST["enabled"] == "on") {
	$enabled = true;
}

validateTitle($title);

$update_query = $pdo->prepare("
	UPDATE {$prefix}_code SET
		title=:title,
		portfolio_id=:portfolio_id,
		extension=:extension,
		code=:code,
		enabled=:enabled
	WHERE id=:code_id;
");
$update_query->bindParam(":code_id", $code_id, PDO::PARAM_INT);
$update_query->bindParam(":title", $title);
$update_query->bindParam(":portfolio_id", $portfolio_id);
$update_query->bindParam(":extension", $extension);
$update_query->bindParam(":code", $code);
$update_query->bindParam(":enabled", $enabled, PDO::PARAM_BOOL);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$update_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Code snipped added");
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}