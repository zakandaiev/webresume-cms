<?php

if($user["is_logged"]) {
	header("Location: /");
}

$current_page["title"] = "Login - ".$GLOBALS["site_name"];

include_once("partials/header.php");
include_once("templates/login.php");
include_once("partials/footer.php");