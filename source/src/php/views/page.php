<?php

foreach($GLOBALS["db_pages"] as $page) {
	if($page["url"] == $current_route || ($page["url"] == "home" && $current_route == "/")) {
		foreach ($page as $key => $value) {
			if(!empty($value)) {
				$current_page[$key] = $value;
			}
		}
		if($page["url"] == "home" && $current_route == "/") {
			$current_page["url"] = $GLOBALS["site_url"];
		} else {
			$current_page["url"] = $GLOBALS["site_url"]."/".$page["url"];
		}
		break;
	}
}

foreach($GLOBALS["db_pages"] as $page) {
	if(($current_route === "/" && $page["url"] === "home") || $current_route === $page["url"]) {
		if(!empty($page["template"]) && is_file("templates/".$page["template"])) {
			include_once("partials/header.php");
			include_once("templates/".$page["template"]);
			include_once("partials/footer.php");
			return;
			break;
		} else {
			include_once("views/404.php");
		}
		return;
		break;
	}
}

include_once("views/404.php");