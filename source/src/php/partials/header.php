<!DOCTYPE html>
<html lang="en">

<head>	
	<title><?=$current_page["title"]?></title>

	<meta charset="utf-8">
	<meta name="author" content="github.com/zakandaiev">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

	<meta name="description" content="<?=$current_page["seo_description"]?>">
	<meta name="keywords" content="<?=$current_page["seo_keywords"]?>">

	<meta property="og:type" content="website">
	<meta property="og:locale" content="uk_UA">
	<meta property="og:url" content="<?=$current_page["url"]?>">
	<meta property="og:title" content="<?=$current_page["title"]?>">
	<meta property="og:description" content="<?=$current_page["seo_description"]?>">
	<meta property="og:keywords" content="<?=$current_page["seo_keywords"]?>">
	<?php if(!empty($current_page["seo_image"])): ?>
	<meta property="og:image" content="/<?=$current_page["seo_image"]?>">
	<?php endif; ?>

	<link rel="canonical" href="<?=$current_page["url"]?>">

	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<?php if(!empty($current_page["seo_image"])): ?>
	<link rel="image_src" href="/<?=$current_page["seo_image"]?>">
	<?php endif; ?>
	
	<?php if(str_starts_with($current_route, "portfolio")): ?>
	<link rel="stylesheet" href="/css/portfolio_page.css">
	<?php endif; ?>
	<link rel="stylesheet" href="/css/main.css">
	<style>:root {--header-bg-image: url("/<?=$GLOBALS['site_background']?>");}</style>
</head>

<body>
	<section id="header" class="header">
		<header class="header__top">
			<div class="avatar">
				<a class="avatar__image" href="/">
					<img src="/<?=getSmallUploadPath($GLOBALS['site_logo'])?>" alt="<?=$GLOBALS["site_name"]?>">
				</a>
				<?php if($user["is_logged"]): ?>
					<a class="avatar__button avatar__button_account" href="/profile/account"><?=getSvg("img/icons/cog.svg")?></a>
					<a class="avatar__button avatar__button_socials" href="/profile/socials"><?=getSvg("img/icons/share.svg")?></a>
					<a class="avatar__button avatar__button_pages" href="/profile/pages"><?=getSvg("img/icons/sitemap.svg")?></a>
					<a class="avatar__button avatar__button_portfolio" href="/profile/portfolio"><?=getSvg("img/icons/briefcase.svg")?></a>
					<a class="avatar__button avatar__button_code" href="/profile/code"><?=getSvg("img/icons/code.svg")?></a>
				<?php endif; ?>
			</div>
			<h1 class="header__title">
				<strong <?php if($user["is_logged"]): ?>contenteditable data-name="site_name"<?php endif; ?>>
					<?php if(!empty($GLOBALS["site_name"])): ?>
						<?=$GLOBALS["site_name"]?>
					<?php elseif($user["is_logged"]): ?>
						Click here to edit
					<?php endif; ?>
				</strong>
				<?php if(!empty($GLOBALS["person_position"]) || $user["is_logged"]): ?>
					<br>
					<small <?php if($user["is_logged"]): ?>contenteditable data-name="person_position"<?php endif; ?>>
						<?php if(!empty($GLOBALS["person_position"])): ?>
							<?=$GLOBALS["person_position"]?>
						<?php elseif($user["is_logged"]): ?>
							Click here to edit
						<?php endif; ?>
					</small>
				<?php endif; ?>
			</h1>
			<div class="person-info">
				<?php if(!empty($GLOBALS["person_location"]) || $user["is_logged"]): ?>
					<p class="person-info__item">
						<span <?php if($user["is_logged"]): ?>contenteditable data-name="person_location"<?php endif; ?>>
							<?php if(!empty($GLOBALS["person_location"])): ?>
								<?=$GLOBALS["person_location"]?>
							<?php elseif($user["is_logged"]): ?>
								Click here to edit
							<?php endif; ?>
						</span>
						<?=getSvg("img/icons/pin.svg")?>
					</p>
				<?php endif; ?>
				<?php if($GLOBALS["person_is_hireable"]): ?>
					<p class="person-info__item">Looking for a job <?=getSvg("img/icons/briefcase.svg")?></p>
				<?php endif; ?>
				<?php if(!empty($GLOBALS["person_resume"])): ?>
					<a class="person-info__item" href="/<?=$GLOBALS['person_resume']?>" download="<?=$GLOBALS['site_name']?>">Download resume <?=getSvg("img/icons/download.svg")?></a>
				<?php endif; ?>
			</div>
		</header>
		<footer id="footer" class="footer">
			<div class="socials">
				<?php
					$person_socials = json_decode($GLOBALS["person_socials"], true);
				?>
				<?php if(!empty($person_socials)): foreach($person_socials as $item): ?>
					<?php
						$icon_path = "img/socials/".$item["icon"];
						$blank = ($item['url'][0] !== '#') ? 'target="_blank"' : '';
						if(substr($item["icon"], -3) === "svg") {
							$icon = getSvg($icon_path);
						} else {
							$icon = '<img src="/'.urlencode_spaces($icon_path).'" alt="'.$item['icon'].'">';
						}
					?>
					<a class="socials__item" href="<?=$item['url']?>" <?=$blank?>><?=$icon?></a>
				<?php endforeach; endif; ?>
			</div>
			<div class="copyright">
				&copy; <?=$GLOBALS["site_name"]?>
			</div>
		</footer>
	</section>

	<main class="page-content">