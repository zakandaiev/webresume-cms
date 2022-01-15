SET NAMES utf8;

CREATE TABLE IF NOT EXISTS `%prefix%_settings` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128) NOT NULL,
	`value` TEXT DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `name` (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_users` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(128) NOT NULL,
	`password` VARCHAR(256) NOT NULL,
	`email` VARCHAR(256) NOT NULL,
	`is_admin` BOOLEAN NOT NULL DEFAULT FALSE,
	`ip` VARCHAR(32) DEFAULT NULL,
	`last_sign` TIMESTAMP NULL DEFAULT NULL,
	`login_hash` VARCHAR(256) DEFAULT NULL,
	`enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE `login` (`login`),
	UNIQUE `email` (`email`),
	UNIQUE `login_hash` (`login_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_pages` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(512) NOT NULL,
	`url` VARCHAR(128) NOT NULL,
	`seo_description` TEXT DEFAULT NULL,
	`seo_keywords` TEXT DEFAULT NULL,
	`seo_image` TEXT DEFAULT NULL,
	`template` VARCHAR(256) DEFAULT NULL,
	`enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`),
	UNIQUE `title` (`title`),
	UNIQUE `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_portfolio` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(512) NOT NULL,
	`url` VARCHAR(128) NOT NULL,
	`main_image` TEXT DEFAULT NULL,
	`images` JSON DEFAULT NULL,
	`description` TEXT DEFAULT NULL,
	`details` JSON DEFAULT NULL,
	`seo_description` TEXT DEFAULT NULL,
	`seo_keywords` TEXT DEFAULT NULL,
	`enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`),
	UNIQUE `title` (`title`),
	UNIQUE `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `%prefix%_code` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(512) NOT NULL,
	`extension` VARCHAR(32) NOT NULL,
	`code` TEXT NOT NULL,
	`portfolio_id` INT UNSIGNED DEFAULT NULL,
	`order` INT UNSIGNED NOT NULL DEFAULT 1,
	`enabled` BOOLEAN NOT NULL DEFAULT TRUE,
	`cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `%prefix%_users` (`login`, `password`, `email`, `is_admin`, `login_hash`) VALUES
('%admin_login%', '%admin_password%', '%admin_email%', true, '%login_hash%');

INSERT INTO `%prefix%_settings` (`name`, `value`) VALUES
('site_logo', 'img/no_avatar.jpg'),
('site_name', '%site_name%'),
('site_background', 'img/background/macbook.jpg'),
('site_analytics_gtag', null),
('site_pagination_limit', '4'),
('person_position', null),
('person_location', null),
('person_resume', null),
('person_socials', '[{"icon":"email.svg","url":"#contact"}]'),
('person_email', '%person_email%'),
('person_is_hireable', true),
('section_about', null),
('section_timeline', null),
('section_skills', null),
('section_contact', null);

INSERT INTO `%prefix%_pages` (`title`, `url`, `template`) VALUES
('%site_name%', 'home', 'home.php');