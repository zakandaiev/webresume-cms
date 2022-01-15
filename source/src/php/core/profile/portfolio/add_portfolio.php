<?php

global $pdo, $prefix, $user;

if(!$user["is_logged"] || !is_csrf_valid()) {
	serverSendAnswer(0, "Access denied");
}

$title = null;
if(isset($_POST["title"]) && !empty($_POST["title"])) {
	$title = filter_var(trim($_POST["title"]), FILTER_SANITIZE_STRING);
}
$url = null;
if(isset($_POST["url"]) && !empty($_POST["url"])) {
	$url = filter_var(trim($_POST["url"]), FILTER_SANITIZE_STRING);
}
$main_image = null;
if(isset($_POST["main_image"]) && !empty($_POST["main_image"])) {
	$main_image = trim($_POST["main_image"]);
}
$images = null;
if(isset($_POST["images"]) && !empty($_POST["images"])) {
	$images = trim($_POST["images"]);
}
$description = null;
if(isset($_POST["description"]) && !empty($_POST["description"])) {
	$description = trim($_POST["description"]);
}
$details = null;
if(isset($_POST["technologies"]) && !empty($_POST["technologies"])) {
	$details["technologies"] = filter_var(trim($_POST["technologies"]), FILTER_SANITIZE_STRING);
}
if(isset($_POST["features"]) && !empty($_POST["features"])) {
	$details["features"] = filter_var(trim($_POST["features"]), FILTER_SANITIZE_STRING);
}
if(isset($_POST["date"]) && !empty($_POST["date"])) {
	$details["date"] = filter_var(trim($_POST["date"]), FILTER_SANITIZE_STRING);
}
if(isset($_POST["link"]) && !empty($_POST["link"])) {
	$details["link"] = filter_var(trim($_POST["link"]), FILTER_SANITIZE_STRING);
}
if(isset($_POST["github"]) && !empty($_POST["github"])) {
	$details["github"] = filter_var(trim($_POST["github"]), FILTER_SANITIZE_STRING);
}
$seo_description = null;
if(isset($_POST["seo_description"]) && !empty($_POST["seo_description"])) {
	$seo_description = filter_var(trim($_POST["seo_description"]), FILTER_SANITIZE_STRING);
}
$seo_keywords = null;
if(isset($_POST["seo_keywords"]) && !empty($_POST["seo_keywords"])) {
	$seo_keywords = filter_var(trim($_POST["seo_keywords"]), FILTER_SANITIZE_STRING);
}
$cdate = null;
if(isset($_POST["cdate"]) && !empty($_POST["cdate"])) {
	$cdate = filter_var(trim($_POST["cdate"]), FILTER_SANITIZE_STRING);
}
$enabled = false;
if(isset($_POST["enabled"]) && $_POST["enabled"] == "on") {
	$enabled = true;
}

validateTitle($title);
validateUrl($url);

$details = json_encode($details, JSON_FORCE_OBJECT);

$add_query = $pdo->prepare("
	INSERT INTO {$prefix}_portfolio
		(title, url, main_image, images, description, details, seo_description, seo_keywords, cdate, enabled)
	VALUES
		(:title, :url, :main_image, :images, :description, :details, :seo_description, :seo_keywords, :cdate, :enabled);
");
$add_query->bindParam(":title", $title);
$add_query->bindParam(":url", $url);
$add_query->bindParam(":main_image", $main_image);
$add_query->bindParam(":images", $images);
$add_query->bindParam(":description", $description);
$add_query->bindParam(":details", $details);
$add_query->bindParam(":seo_description", $seo_description);
$add_query->bindParam(":seo_keywords", $seo_keywords);
$add_query->bindParam(":cdate", $cdate);
$add_query->bindParam(":enabled", $enabled, PDO::PARAM_BOOL);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$add_query->execute();
	generateSitemapXml();
	serverSendAnswer(1, "Portfolio added");
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