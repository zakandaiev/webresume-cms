<?php

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

function tableExists($pdo, $table) {
	try {
		$result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
	} catch (Exception $e) {
		return FALSE;
	}
	return $result !== FALSE;
}

// DO LOGIN
if(isset($_GET["start_install"])) {
	$login_hash = md5($_GET["admin_login"].$_GET["admin_password"].$_GET["admin_email"].time());
	setcookie("login_hash", $login_hash, time() + 3600 * 24 * 7, "/");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="robots" content="noindex, nofollow">

	<title>Install - WebResume CMS</title>

	<meta charset="utf-8">
	<meta name="author" content="github.com/zakandaiev">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">

	<link rel="stylesheet" href="/css/main.css">
	<style>.header {background-image: url("/img/background/macbook.jpg");}</style>
</head>

<body>
	<header id="header" class="header" style="background-position: center 0px;">
		<h1 class="header__title"><strong>WebResume CMS</strong><br><small>Install page</small></h1>
	</header>
	<main class="page-content">

			<?php if(isset($_GET["start_install"])): ?>
				<section class="section">
					<?php
						// PREPARE TO INSTALL
						$pdo_dsn = "mysql:host=".$_GET['db_host'].";dbname=".$_GET['db_name'].";charset=utf8";
						$pdo_user = $_GET['db_user'];
						$pdo_password = $_GET['db_pass'];

						try {
							$pdo = new PDO($pdo_dsn, $pdo_user, $pdo_password, array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
							$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						} catch(PDOException $e) {
							echo '<h2 class="section__title">Error</h2>';
							echo "<p>Reason: {$e->getMessage()}</p>";
							echo '<a href="/" class="btn primary">Try again</a>';
							exit();
						}

						if(tableExists($pdo, $_GET["db_prefix"]."_settings") == 1) {
							echo '<h2 class="section__title">Error</h2>';
							echo '<p>Delete all tables from your DB <b>"'.$_GET["db_name"].'"</b> and try to re-install the system.</p>';
							echo '<a href="/" class="btn primary">Try again</a>';
							exit();
						}

						// BEGIN INSTALL PROCESS
						$sql_file = file_get_contents('core/install.sql');
						$sql_query = explode(";", $sql_file);
						foreach($sql_query as $sql_row) {
							$sql_row = trim($sql_row ?? "");

							if(empty($sql_row)) {
								continue;
							}

							$replace_from = [
								"%db_host%", "%db_user%", "%db_pass%", "%db_name%", "%prefix%",
								"%site_name%", "%person_email%",
								"%admin_login%", "%admin_password%", "%admin_email%", "%login_hash%"
							];
							$replace_to = [
								$_GET["db_host"], $_GET["db_user"], $_GET["db_pass"], $_GET["db_name"], $_GET["db_prefix"],
								$_GET["site_name"], $_GET["person_email"],
								$_GET["admin_login"], password_hash($_GET["admin_password"], PASSWORD_DEFAULT), $_GET["admin_email"], $login_hash
							];
							$sql_row_rep = str_replace($replace_from, $replace_to, $sql_row);
							$sql_row_rep .= ';';

							// FOR DEBUG
							// file_put_contents("install_debug.txt", $sql_row_rep . "\n", FILE_APPEND | LOCK_EX);

							$query = $pdo->prepare($sql_row_rep);
							$query->execute();
						}

						$db_connect_file = '<?php'.PHP_EOL;
						$db_connect_file .= '$pdo_dsn = "mysql:host='.$_GET['db_host'].';dbname='.$_GET['db_name'].';charset=utf8";'.PHP_EOL;
						$db_connect_file .= '$pdo_user = "'.$_GET["db_user"].'";'.PHP_EOL;
						$db_connect_file .= '$pdo_password = "'.$_GET["db_pass"].'";'.PHP_EOL;
						$db_connect_file .= '$pdo = new PDO($pdo_dsn, $pdo_user, $pdo_password, array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));'.PHP_EOL;
						$db_connect_file .= '$prefix = "'.$_GET['db_prefix'].'";'.PHP_EOL;
						file_put_contents("core/db_connect.php", $db_connect_file, LOCK_EX);

						$site_url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http")."://".$_SERVER["HTTP_HOST"];

						$robots_txt = 'User-agent: *'.PHP_EOL;
						$robots_txt .= 'Disallow: /404'.PHP_EOL;
						$robots_txt .= 'Disallow: /core/'.PHP_EOL;
						$robots_txt .= 'Disallow: /views/'.PHP_EOL;
						$robots_txt .= 'Disallow: /profile/'.PHP_EOL;
						$robots_txt .= 'Disallow: /partials/'.PHP_EOL;
						$robots_txt .= 'Disallow: /templates/'.PHP_EOL;
						$robots_txt .= 'Sitemap: '.$site_url.'/sitemap.xml';
						file_put_contents("robots.txt", $robots_txt, LOCK_EX);

						$sitemap_xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
						$sitemap_xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL;
						$sitemap_xml .= '<url><loc>'.$site_url.'</loc><lastmod>'.date('c').'</lastmod><priority>1.00</priority></url>'.PHP_EOL;
						$sitemap_xml .= '</urlset>';
						file_put_contents("sitemap.xml", $sitemap_xml, LOCK_EX);

						if(is_file("core/install.php") && is_file("core/install.sql")) {
							unlink("core/install.php");
							unlink("core/install.sql");
						}

						if(!file_exists("uploads")) {
							mkdir("uploads", 0755, true);
						}
					?>
					<h2 class="section__title">Success</h2>
					<a href="/" class="btn primary">Go to the site</a>
				</section>
			<?php else: ?>
				<form method="GET" action="/install">
					<div class="section">
						<h2 class="section__title">Database setup</h2>
						<label>Host</label>
						<input type="text" name="db_host" value="localhost" placeholder="Host" required>
						<label>User</label>
						<input type="text" name="db_user" placeholder="User" required>
						<label>Password</label>
						<input type="text" name="db_pass" placeholder="Password" required>
						<label>Name</label>
						<input type="text" name="db_name" placeholder="Name" required>
						<label>Prefix</label>
						<input type="text" name="db_prefix" value="webresume" placeholder="For example: webresume" required>
					</div>
					<div class="section">
						<h2 class="section__title">Site setup</h2>
						<label>Name</label>
						<input type="text" name="site_name" placeholder="Name" required>
						<label>Contact email</label>
						<input type="email" name="person_email" placeholder="admin@<?=$_SERVER['HTTP_HOST']?>" required>
					</div>
					<div class="section">
						<h2 class="section__title">Login setup</h2>
						<label>Login</label>
						<input type="text" name="admin_login" value="admin" placeholder="Login" required>
						<label>Password</label>
						<input type="text" name="admin_password" placeholder="Password" required>
						<label>Email</label>
						<input type="email" name="admin_email" placeholder="Email" required>
					</div>
					<button type="submit" name="start_install" class="btn primary fit">Install</button>
				</form>
			<?php endif; ?>
	</main>

</body>
</html>
