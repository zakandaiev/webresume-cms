<?php

if(!isset($portfolio_url) || empty($portfolio_url)) {
	include_once("views/404.php");
	return;
}

$portfolio_query = $pdo->prepare("SELECT * FROM {$prefix}_portfolio WHERE url=:portfolio_url AND enabled is true ORDER BY cdate DESC LIMIT 1;");
$portfolio_query->bindParam(":portfolio_url", $portfolio_url);
$portfolio_query->execute();
$portfolio = $portfolio_query->fetch(PDO::FETCH_ASSOC);

if(empty($portfolio) || !is_file("templates/portfolio_single.php")) {
	include_once("views/404.php");
	return;
}

$current_page["title"] = "Portfolio - ".$GLOBALS["site_name"];
foreach($portfolio as $key => $value) {
	if(!empty($value)) {
		$current_page[$key] = $value;
	}
}
$current_page["url"] = $GLOBALS["site_url"]."/portfolio/".$portfolio_url;
$current_page["seo_image"] = $portfolio["main_image"];

include_once("partials/header.php");
include_once("templates/portfolio_single.php");
include_once("partials/footer.php");