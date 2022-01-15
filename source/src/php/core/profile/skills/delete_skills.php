<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$skills_id = null;
if(isset($_POST["skills_id"])) {
	$skills_id = intval(filter_var(trim($_POST["skills_id"]), FILTER_SANITIZE_NUMBER_INT));
}

if(!is_int($skills_id)) {
	serverSendAnswer(0, "Access denied");
}

$skills = json_decode($GLOBALS["section_skills"], true);
unset($skills[$skills_id]);
$skills = json_encode($skills);

$update_query = $pdo->prepare("UPDATE {$prefix}_settings SET value=:content WHERE name='section_skills';");
$update_query->bindParam(":content", $skills);

try {
	$update_query->execute();
	serverSendAnswer(1, "Skill deleted");
} catch(PDOException $error) { 
	serverSendAnswer(0, $error->getMessage());
}