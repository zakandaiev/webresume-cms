<?php

http_response_code(404);

$current_page["title"] = "Page not found - ".$GLOBALS["site_name"];

include_once("partials/header.php");
include_once("templates/404.php");
include_once("partials/footer.php");