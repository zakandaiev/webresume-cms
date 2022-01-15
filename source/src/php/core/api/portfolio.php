<?php

global $pdo, $prefix, $user;

if(!is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$portfolio_query = $pdo->prepare("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC;");
$portfolio_query->execute();
$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($portfolio);