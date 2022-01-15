<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$social_id = null;
if(isset($_POST["social_id"])) {
	$social_id = intval(filter_var(trim($_POST["social_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(!is_int($social_id)) {
	serverSendAnswer(0, "Access denied");
}

$social = json_decode($GLOBALS["person_socials"], true);
unset($social[$social_id]);
$social = json_encode($social);

$update_query = $pdo->prepare("UPDATE {$prefix}_settings SET value=:content WHERE name='person_socials';");
$update_query->bindParam(":content", $social);

try {
	$update_query->execute();
	serverSendAnswer(1, "Social deleted");
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}