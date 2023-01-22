# WebResume CMS
<img width=150 align="right" src="https://raw.githubusercontent.com/zakandaiev/webresume-cms/main/source/src/root-files/favicon.svg" alt="Logo">
WebResume CMS is used to easily create your resume website.

#### Content
1. [Live demo](#live-demo)
2. [Download](#download)
3. [Features](#features)
4. [Web environment requirements](#web-environment-requirements)
5. [Installation](#installation)
6. [Source code edit](#source-code-edit)

## Live demo
* [zakandaiev.com](https://zakandaiev.com)

## Download
* Download: [latest version v1.1.1](https://github.com/zakandaiev/webresume-cms/files/9593843/webresume-cms-v1.1.1.zip)
* Review: [all releases](https://github.com/zakandaiev/webresume-cms/releases)

## Features
* Automated install page
* Fine-tuning the site without programming knowledge
* Content live editing
* All CMS features
* SEO management
* Hybrid admin system (use `/login` path to login)
* and many more...

## Web environment requirements
* PHP 7.4+
* MySQL 5.7+ or MariaDB 10.3+

## Installation
1. Download latest release.
2. Extract content from .zip to your web host server.
3. Go to the site and fill out the installation form.
4. Click Install button & enjoy ;)

## Source code edit
In the process of creation, [FrontEnd Starter](https://github.com/zakandaiev/frontend-starter) was used and adapted to the PHP environment. To work with the source code, you will need to install NodeJS and Gulp4 globally. You can use OpenServer for example to create a database and process PHP code.
1. Create a local domain `webresume/`, database and user
2. Place the `source` folder into the root of the site (`webresume/source`)
3. Open a command line and go to the `source` folder
4. Install all required modules and dependencies with `npm i` command
5. Use:
	* `npm run dev` or `gulp` task to start Dev mode
	* `npm run prod` or `gulp --prod` task to start Prod mode
6. On the real site, copy all the contents of `webresume/` except for the `source` folder
