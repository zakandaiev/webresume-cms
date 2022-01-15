<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$portfolio_id = null;
if(isset($_POST["portfolio_id"]) && !empty($_POST["portfolio_id"])) {
	$portfolio_id = intval(filter_var(trim($_POST["portfolio_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(empty($portfolio_id) || !is_int($portfolio_id)) {
	serverSendAnswer(0, "Access denied");
}

$delete_query = $pdo->prepare("DELETE FROM {$prefix}_portfolio WHERE id=:portfolio_id;");
$delete_query->bindParam(":portfolio_id", $portfolio_id, PDO::PARAM_INT);

try {
	$delete_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Portfolio deleted");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}