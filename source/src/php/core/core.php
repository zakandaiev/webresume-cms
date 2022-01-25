<?php

include_once("core/validations.php");

############################# PHP 8 #############################
if(!function_exists("str_starts_with")) {
	function str_starts_with($haystack, $needle) {
		$length = strlen($needle);
		return substr($haystack, 0, $length) === $needle;
	}
}

############################# FORM ALERTS #############################
function serverSendAnswer($success, $message) {
	header("Content-Type: application/json");
	echo json_encode(array("success" => $success, "message" => $message));
	exit();
}

############################# UPLOADS #############################
function getUploadMaxSize() {
	$amount = ini_get("upload_max_filesize");
	if(is_int($amount)) {
		return $amount;
	}
	$units = ["", "K", "M", "G"];
	preg_match("/(\d+)\s?([KMG]?)/", ini_get("upload_max_filesize"), $matches);
	[$_, $nr, $unit] = $matches;
	$exp = array_search($unit, $units);
	return (int)$nr * pow(1024, $exp);
}
function uploadFiles($files, $extensions = array(), $folder = "") {
	global $user;
	if(empty($files)) {
		serverSendAnswer(0, "There was an error loading the files");
	}
	if(!file_exists("uploads")) {
		mkdir("uploads", 0755, true);
	}
	if(!empty($folder) && !file_exists("uploads/".$folder)) {
		mkdir("uploads/".$folder, 0755, true);
	}
	$files_arr = array();
	foreach($files["tmp_name"] as $key => $tmp_name) {
		$file_name = $files["name"][$key];
		$file_size = $files["size"][$key];
		$file_ext = strtolower(pathinfo($files["name"][$key], PATHINFO_EXTENSION));
		if($user["is_logged"]) {
			$file_name_prepend = $user["id"]."_";
		} else {
			$file_name_prepend = "uu_";
		}
		$file_name_prepend .= time()."_";
		$folder_prepend = "";
		if(!empty($folder)) {
			$folder_prepend = $folder."/";
		}
		$path_to_upload = "uploads/".$folder_prepend.$file_name_prepend.uniqid().".".$file_ext;
		if(is_array($extensions) && !empty($extensions) && !in_array($file_ext, $extensions)) {
			serverSendAnswer(0, "Format {$file_ext} is forbidden");
		}
		if($file_size > getUploadMaxSize()) {
			serverSendAnswer(0, "Size of {$file_name} is too large");
		}
		$files_arr[] = $path_to_upload;
		move_uploaded_file($files["tmp_name"][$key], $path_to_upload);
		resizeImage($path_to_upload);
	}
	if(!empty($files_arr)) {
		serverSendAnswer(1, $files_arr);
	} else {
		serverSendAnswer(0, "There was an error loading the files");
	}
}
function resizeImage($source_file, $folder = "small", $width = 400) {
	if(!file_exists($source_file)) {
		return false;
	}

	if(!file_exists("uploads")) {
		mkdir("uploads", 0755, true);
	}
	if(!empty($folder) && !file_exists("uploads/".$folder)) {
		mkdir("uploads/".$folder, 0755, true);
	}

	$folder_prepend = "";
	if(!empty($folder)) {
		$folder_prepend = $folder."/";
	}

	$source_filename = strtolower(pathinfo($source_file, PATHINFO_BASENAME));
	$source_ext = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));

	$path_to_save = "uploads/".$folder_prepend.$source_filename;

	if($source_ext === "jpg" || $source_ext === "jpeg") {
		$image = imagecreatefromjpeg($source_file);
	} else if($source_ext === "png") {
		$image = imagecreatefrompng($source_file);
	} else if($source_ext === "gif") {
		$image = imagecreatefromgif($source_file);
	}

	if(!isset($image)) {
		return false;
	}

	$source_width = imagesX($image);
	$source_height = imagesY($image);

	if($source_width <= $width) {
		return false;
	}

	$image_resize_ratio = $width / $source_width;
	$image_width = $source_width * $image_resize_ratio;
	$image_height = $source_height * $image_resize_ratio;

	$sample = imagecreatetruecolor($image_width, $image_height);
	imagesavealpha($sample, true);
	$trans_colour = imagecolorallocatealpha($sample, 255, 255, 255, 127);
	imagefill($sample, 0, 0, $trans_colour);
	imagecopyresampled($sample, $image, 0, 0, 0, 0, $image_width, $image_height, $source_width, $source_height);

	if($source_ext === "jpg" || $source_ext === "jpeg") {
		if(imagejpeg($sample, $path_to_save, 100)) {
			return true;
		}
	} else if($source_ext === "png") {
		if(imagepng($sample, $path_to_save, 9)) {
			return true;
		}		
	} else if($source_ext === "gif") {
		if(imagegif($sample, $path_to_save)) {
			return true;
		}
	}
	
	imagedestroy($image);
	imagedestroy($sample);

	return false;
}
function printFormFiles($files) {
	if(empty($files)) {
		return '<div class="form-files sortable"></div>';
	}
	if(!is_array($files) && $files[0] === "[") {
		$files_array = json_decode($files, true);
	} else {
		$files_array = array($files);
	}
	$files_output = '<div class="form-files sortable">';
	foreach($files_array as $file) {
		$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		$files_output .= '<div class="form-files__item sortable__handle" data-src="'.$file.'">';
		if($file_ext === "jpg" || $file_ext === "jpeg" || $file_ext === "png" || $file_ext === "gif" || $file_ext === "webp") {
			$files_output .= '<img src="/'.getSmallUploadPath($file).'">';
		} else if($file_ext === "pdf") {
			$files_output .= '<span>'.getSvg("img/icons/pdf.svg").'</span>';
		} else {
			$files_output .= '<span>'.getSvg("img/icons/document.svg").'</span>';
		}
		$files_output .= '</div>';
	}
	$files_output .= '</div>';
	return $files_output;
}

############################# USERS #############################
function setUserCookie($key, $value = null, $days = 0) {
	setcookie($key, $value ? $value : "", time() + 3600 * 24 * intval($days), "/");
}

############################# MAILS #############################
function sendMail($recepient, $subject, $message, $from) {
	$site_name = $GLOBALS["site_name"];
	$to = trim($recepient);
	$subj = trim($subject);
	$headers = array(
		'Content-type' => 'text/html',
		'charset' => 'utf-8',
		'MIME-Version' => '1.0',
		'From' => $site_name . '<'.$from.'>',
		'Reply-To' => $from
	);

	return mail($to, $subj, $message, $headers);
}

############################# IMAGES #############################
function getSvg($file) {
	if(!file_exists($file)) {
		return "Image {$file} not found.";
	} else if(strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== "svg") {
		return "The file {$file} does not conform to the .svg format";
	} else {
		return file_get_contents($file);
	}
}
function urlencode_spaces($url) {
	return str_replace(" ", "%20", $url);
}
function getSmallUploadPath($path_to_full) {
	$path_to_small = str_replace("uploads/", "uploads/small/", $path_to_full);
	if(file_exists($path_to_small)) {
		return urlencode_spaces($path_to_small);
	}
	return urlencode_spaces($path_to_full);
}

############################# DATES #############################
function formatDate($timestamp, $format = null) {
	if($format === null) {
		return date("d.m.Y H:i", $timestamp);
	} else {
		return date($format, $timestamp);
	}
}
function formatDateString($timestamp, $format = null) {
	if($format === null) {
		return date("d.m.Y H:i", strtotime($timestamp));
	} else {
		return date($format, strtotime($timestamp));
	}
}

############################# GENERATORS #############################
function generateHash($string) {
	$site_name = preg_replace("/\s+/", "", $GLOBALS["site_name"]);
	$salt = "$2a$07$" . $site_name . "$";
	return md5(sha1($string.time().$salt).$salt);
}

function generatePassword($length = 16) {
	$string = "";
	$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$size = strlen($chars);
	for ($i = 0; $i < $length; $i++) {
		$string .= $chars[rand(0, $size - 1)];
	}
	return $string; 
}

function generateSitemapXml() {
	global $pdo, $prefix, $user;
	
	$sitemap_xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
	$sitemap_xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL;

	$pages = array_reverse($GLOBALS["db_pages"]);
	foreach($pages as $page) {
		if(!$page["enabled"]) {
			continue;
		}
		if($page["url"] === "home") {
			$sitemap_xml .= '<url><loc>'.$GLOBALS["site_url"].'</loc><lastmod>'.date('c').'</lastmod><priority>1.00</priority></url>' . PHP_EOL;
		} else {
			$sitemap_xml .= '<url><loc>'.$GLOBALS["site_url"].'/'.$page["url"].'</loc><lastmod>'.date('c').'</lastmod><priority>0.90</priority></url>' . PHP_EOL;
		}
	}

	$portfolio_query = $pdo->query("SELECT * FROM {$prefix}_portfolio WHERE enabled is true ORDER BY cdate DESC;");
	$portfolio = $portfolio_query->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($portfolio)) {
		foreach($portfolio as $page) {
			if(!$page["enabled"]) {
				continue;
			}
			$sitemap_xml .= '<url><loc>'.$GLOBALS["site_url"].'/portfolio/'.$page["url"].'</loc><lastmod>'.date('c').'</lastmod><priority>0.80</priority></url>' . PHP_EOL;
		}
	}

	$sitemap_xml .= '</urlset>';

	file_put_contents("sitemap.xml", $sitemap_xml, LOCK_EX);
}

############################# CLASSIFIERS #############################
function extensionLabelClass($extension) {
	$label = "primary";
	switch(strtolower($extension)) {
		case "php": {
			$label = "primary";
			break;
		}
		case "js":
		case "javascript": {
			$label = "info";
			break;
		}
		case "css":
		case "sass":
		case "scss": {
			$label = "success";
			break;
		}
		case "sql": {
			$label = "warning";
			break;
		}
		case "sma":
		case "clike": {
			$label = "error";
			break;
		}
		default: {
			$label = "primary";
			break;
		}
	}
	return $label;
}