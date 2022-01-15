<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

if(empty($_FILES["files"])) {
	serverSendAnswer(0, "");
}

$extensions = array("pdf", "doc", "docx");

uploadFiles($_FILES["files"], $extensions);