<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$code_id = null;
if(isset($_POST["code_id"]) && !empty($_POST["code_id"])) {
	$code_id = intval(filter_var(trim($_POST["code_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(empty($code_id) || !is_int($code_id)) {
	serverSendAnswer(0, "Access denied");
}

$delete_query = $pdo->prepare("DELETE FROM {$prefix}_code WHERE id=:code_id;");
$delete_query->bindParam(":code_id", $code_id, PDO::PARAM_INT);

try {
	$delete_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "code deleted");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}