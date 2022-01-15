<?php

require_once('core/router.php');

if(!is_file('core/db_connect.php')) {
	get('/install', 'core/install.php');
	get('/install/del', 'core/install_del.php');
	header('Location: /install');
	exit();
}

require_once('core/db_connect.php');
require_once('core/db_settings.php');
require_once('core/db_pages.php');
require_once('core/db_user.php');
require_once('core/core.php');

############################# GET #############################
// PAGES
foreach($db_pages as $page) {
	if($page["url"] === "home") {
		get('/', 'views/page.php');
	} else {
		get('/'.$page["url"], 'views/page.php');
	}
}

// PORTFOLIO
get('/portfolio/$portfolio_url', 'views/portfolio.php');

// PROFILE
get('/profile/$section_name', 'views/profile.php');

// AUTH
get('/login', 'views/login.php');
get('/logout', 'core/auth/logout.php');

############################# POST #############################
// API
post('/api/portfolio', 'core/api/portfolio.php');

// AUTH
post('/login', 'core/auth/login.php');
post('/logout', 'core/auth/logout.php');

// CONTACT
post('/contact_form', 'core/notifications/contact_form.php');

// LIVE EDIT
post('/live_edit', 'core/profile/live_edit.php');

// TIMELINE
post('/delete_timeline', 'core/profile/timeline/delete_timeline.php');

// SKILLS
post('/delete_skills', 'core/profile/skills/delete_skills.php');

// ACCOUNT
post('/profile/edit_general', 'core/profile/account/edit_general.php');
post('/profile/edit_account', 'core/profile/account/edit_account.php');
post('/profile/edit_password', 'core/profile/account/edit_password.php');

// PAGES
post('/profile/add_page', 'core/profile/pages/add_page.php');
post('/profile/edit_page', 'core/profile/pages/edit_page.php');
post('/profile/delete_page', 'core/profile/pages/delete_page.php');

// PORTFOLIO
post('/profile/add_portfolio', 'core/profile/portfolio/add_portfolio.php');
post('/profile/edit_portfolio', 'core/profile/portfolio/edit_portfolio.php');
post('/profile/delete_portfolio', 'core/profile/portfolio/delete_portfolio.php');

// CODE SNIPPETS
post('/profile/add_code', 'core/profile/code/add_code.php');
post('/profile/edit_code', 'core/profile/code/edit_code.php');
post('/profile/delete_code', 'core/profile/code/delete_code.php');
post('/portfolio/sort_code', 'core/profile/code/sort_code.php');

// SOCIALS
post('/profile/add_social', 'core/profile/socials/add_social.php');
post('/profile/edit_social', 'core/profile/socials/edit_social.php');
post('/profile/delete_social', 'core/profile/socials/delete_social.php');

// UPLOAD IMAGES
post('/upload/images', 'core/upload/images.php');
post('/upload/resume', 'core/upload/resume.php');

// 404
any('/404', 'views/404.php');