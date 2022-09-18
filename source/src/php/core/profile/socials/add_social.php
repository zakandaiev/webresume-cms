<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$icon = null;
if(isset($_POST["icon"]) && !empty($_POST["icon"])) {
	$icon = trim($_POST["icon"]);
}
$url = null;
if(isset($_POST["url"]) && !empty($_POST["url"])) {
	$url = trim($_POST["url"]);
}

$socials = json_decode($GLOBALS["person_socials"], true);
if(empty($socials)) {
	$socials = array(0 => array("icon" => $icon, "url" => $url));
} else {
	array_push($socials, array("icon" => $icon, "url" => $url));
}
$socials = json_encode($socials);

$update_query = $pdo->prepare("UPDATE {$prefix}_settings SET value=:content WHERE name='person_socials';");
$update_query->bindParam(":content", $socials);

try {
	$update_query->execute();
	serverSendAnswer(1, "Social added");
} catch(PDOException $error) {
	serverSendAnswer(0, $error->getMessage());
}
