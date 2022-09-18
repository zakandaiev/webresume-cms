<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$title = null;
if(isset($_POST["title"]) && !empty($_POST["title"])) {
	$title = trim($_POST["title"]);
}
$url = null;
if(isset($_POST["url"]) && !empty($_POST["url"])) {
	$url = trim($_POST["url"]);
}
$template = null;
if(isset($_POST["template"]) && !empty($_POST["template"])) {
	$template = trim($_POST["template"]);
}
$seo_description = null;
if(isset($_POST["seo_description"]) && !empty($_POST["seo_description"])) {
	$seo_description = trim($_POST["seo_description"]);
}
$seo_keywords = null;
if(isset($_POST["seo_keywords"]) && !empty($_POST["seo_keywords"])) {
	$seo_keywords = trim($_POST["seo_keywords"]);
}
$seo_image = null;
if(isset($_POST["seo_image"]) && !empty($_POST["seo_image"])) {
	$seo_image = trim($_POST["seo_image"]);
}
$enabled = false;
if(isset($_POST["enabled"]) && $_POST["enabled"] == "on") {
	$enabled = true;
}

validateTitle($title);
validateUrl($url);

$add_query = $pdo->prepare("
	INSERT INTO {$prefix}_pages
		(title, url, template, seo_description, seo_keywords, seo_image, enabled)
	VALUES
		(:title, :url, :template, :seo_description, :seo_keywords, :seo_image, :enabled);
");
$add_query->bindParam(":title", $title);
$add_query->bindParam(":url", $url);
$add_query->bindParam(":template", $template);
$add_query->bindParam(":seo_description", $seo_description);
$add_query->bindParam(":seo_keywords", $seo_keywords);
$add_query->bindParam(":seo_image", $seo_image);
$add_query->bindParam(":enabled", $enabled, PDO::PARAM_BOOL);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$add_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Page added");
} catch(PDOException $error) {
	if (preg_match("/Duplicate entry .+ for key '(.+)'/", $error->getMessage(), $matches)) {
		$arr_column_names = array(
			"title" => "title",
			"url" => "url"
		);
		if (!array_key_exists($matches[1], $arr_column_names)) {
			$column_name = $matches[1];
		} else {
			$column_name = $arr_column_names[$matches[1]];
		}
		serverSendAnswer(0, "This $column_name is already taken");
	} else {
		serverSendAnswer(0, $error->getMessage());
	}
}
