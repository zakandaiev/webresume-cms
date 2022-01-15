<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$page_id = null;
if(isset($_POST["page_id"]) && !empty($_POST["page_id"])) {
	$page_id = intval(filter_var(trim($_POST["page_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(empty($page_id) || !is_int($page_id) || $page_id === 1) {
	serverSendAnswer(0, "Access denied");
}

$delete_query = $pdo->prepare("DELETE FROM {$prefix}_pages WHERE id=:page_id;");
$delete_query->bindParam(":page_id", $page_id, PDO::PARAM_INT);

try {
	$delete_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Page deleted");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}