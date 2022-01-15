<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$timeline_id = null;
if(isset($_POST["timeline_id"])) {
	$timeline_id = intval(filter_var(trim($_POST["timeline_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(!is_int($timeline_id)) {
	serverSendAnswer(0, "Access denied");
}

$timeline = json_decode($GLOBALS["section_timeline"], true);
unset($timeline[$timeline_id]);
$timeline = json_encode($timeline);

$update_query = $pdo->prepare("UPDATE {$prefix}_settings SET value=:content WHERE name='section_timeline';");
$update_query->bindParam(":content", $timeline);

try {
	$update_query->execute();
	serverSendAnswer(1, "Timeline deleted");
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}