<?php

if(!$user["is_logged"]) {
	include_once("views/login.php");
	return;
}

if(isset($section_name) && !empty($section_name)) {
	$template = "partials/profile/{$section_name}.php";
}

if(!isset($template) || !is_file($template)) {
	include_once("views/404.php");
	return;
}

$current_page["title"] = "Profile - ".$GLOBALS["site_name"];
$current_page["url"] = $GLOBALS["site_url"]."/profile/".$section_name;

include_once("partials/header.php");
include_once($template);
include_once("partials/footer.php");