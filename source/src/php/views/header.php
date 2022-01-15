<?php

if(!isset($route) || empty($route)) {
	http_response_code(404);
	return;
}

global $pdo, $prefix, $user;

############ CSRF ############
$csrf_token = set_csrf();

############ CURRENT ROUTE ############
$current_route = "/";
if($route !== "/") {
	$current_route = str_replace("/", "", $route);
}

############ PAGE DATA ############
$current_page["title"] = $GLOBALS["site_name"];
$current_page["url"] = ($current_route == "/") ? $GLOBALS["site_url"] : $GLOBALS["site_url"]."/".$current_route;
$current_page["seo_description"] = "";
$current_page["seo_keywords"] = "";
$current_page["seo_image"] = "";