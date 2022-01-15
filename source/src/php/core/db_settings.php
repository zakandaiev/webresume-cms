<?php

$db_settings_query = $pdo->query("SELECT * FROM ".$prefix."_settings");
while($rows = $db_settings_query->fetch(PDO::FETCH_LAZY)) {
	$GLOBALS[$rows->name] = $rows->value;
}

$GLOBALS["site_url"] = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http")."://".$_SERVER["HTTP_HOST"];