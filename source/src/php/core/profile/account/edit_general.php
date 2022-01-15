<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$site_name = null;
if(isset($_POST["site_name"]) && !empty($_POST["site_name"])) {
	$site_name = filter_var(trim($_POST["site_name"]), FILTER_SANITIZE_STRING);
}
$site_logo = null;
if(isset($_POST["site_logo"]) && !empty($_POST["site_logo"])) {
	$site_logo = trim($_POST["site_logo"]);
}
$site_background = null;
if(isset($_POST["site_background"]) && !empty($_POST["site_background"])) {
	$site_background = trim($_POST["site_background"]);
}
$person_position = null;
if(isset($_POST["person_position"]) && !empty($_POST["person_position"])) {
	$person_position = filter_var(trim($_POST["person_position"]), FILTER_SANITIZE_STRING);
}
$person_location = null;
if(isset($_POST["person_location"]) && !empty($_POST["person_location"])) {
	$person_location = filter_var(trim($_POST["person_location"]), FILTER_SANITIZE_STRING);
}
$person_email = null;
if(isset($_POST["person_email"]) && !empty($_POST["person_email"])) {
	$person_email = filter_var(trim($_POST["person_email"]), FILTER_SANITIZE_STRING);
}
$person_is_hireable = false;
if (isset($_POST["person_is_hireable"]) && $_POST["person_is_hireable"] == "on") {
	$person_is_hireable = true;
}
$person_resume = null;
if(isset($_POST["person_resume"]) && !empty($_POST["person_resume"])) {
	$person_resume = trim($_POST["person_resume"]);
}
$site_analytics_gtag = null;
if(isset($_POST["site_analytics_gtag"]) && !empty($_POST["site_analytics_gtag"])) {
	$site_analytics_gtag = filter_var(trim($_POST["site_analytics_gtag"]), FILTER_SANITIZE_STRING);
}
$site_pagination_limit = 4;
if(isset($_POST["site_pagination_limit"]) && !empty($_POST["site_pagination_limit"])) {
	$site_pagination_limit = filter_var(trim($_POST["site_pagination_limit"]), FILTER_SANITIZE_NUMBER_INT);
}

validateEmail($person_email);

$update_query = $pdo->prepare("
	UPDATE {$prefix}_settings SET value=:site_name WHERE name='site_name';
	UPDATE {$prefix}_settings SET value=:site_logo WHERE name='site_logo';
	UPDATE {$prefix}_settings SET value=:site_background WHERE name='site_background';
	UPDATE {$prefix}_settings SET value=:person_position WHERE name='person_position';
	UPDATE {$prefix}_settings SET value=:person_location WHERE name='person_location';
	UPDATE {$prefix}_settings SET value=:person_email WHERE name='person_email';
	UPDATE {$prefix}_settings SET value=:person_is_hireable WHERE name='person_is_hireable';
	UPDATE {$prefix}_settings SET value=:person_resume WHERE name='person_resume';
	UPDATE {$prefix}_settings SET value=:site_analytics_gtag WHERE name='site_analytics_gtag';
	UPDATE {$prefix}_settings SET value=:site_pagination_limit WHERE name='site_pagination_limit';
");
$update_query->bindParam(":site_name", $site_name);
$update_query->bindParam(":site_logo", $site_logo);
$update_query->bindParam(":site_background", $site_background);
$update_query->bindParam(":person_position", $person_position);
$update_query->bindParam(":person_location", $person_location);
$update_query->bindParam(":person_email", $person_email);
$update_query->bindParam(":person_is_hireable", $person_is_hireable);
$update_query->bindParam(":person_resume", $person_resume);
$update_query->bindParam(":site_analytics_gtag", $site_analytics_gtag);
$update_query->bindParam(":site_pagination_limit", $site_pagination_limit);

try {
	$update_query->execute();
	serverSendAnswer(1, "Saved");
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}