<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$title = null;
if(isset($_POST["title"]) && !empty($_POST["title"])) {
	$title = trim($_POST["title"]);
}
$portfolio_id = null;
if(isset($_POST["portfolio_id"]) && !empty($_POST["portfolio_id"])) {
	$portfolio_id = intval(filter_var(trim($_POST["portfolio_id"]), FILTER_SANITIZE_NUMBER_INT));
}
$extension = null;
if(isset($_POST["extension"]) && !empty($_POST["extension"])) {
	$extension = trim($_POST["extension"]);
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

$add_query = $pdo->prepare("
	INSERT INTO {$prefix}_code
		(title, portfolio_id, extension, code, enabled)
	VALUES
		(:title, :portfolio_id, :extension, :code, :enabled);
");
$add_query->bindParam(":title", $title);
$add_query->bindParam(":portfolio_id", $portfolio_id);
$add_query->bindParam(":extension", $extension);
$add_query->bindParam(":code", $code);
$add_query->bindParam(":enabled", $enabled, PDO::PARAM_BOOL);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$add_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Code snipped added");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}
