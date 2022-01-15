<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

if(empty($_FILES["files"])) {
	serverSendAnswer(0, "");
}

$extensions = array("jpg", "jpeg", "png", "gif", "webp", "svg");

uploadFiles($_FILES["files"], $extensions);