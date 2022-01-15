<?php

function validateLogin($login, $is_required = true) {
	if(empty($login) && $is_required) {
		serverSendAnswer(0, "Enter login");
	} else if(!empty($login)) {
		if(mb_strlen($login) < 4) {
			serverSendAnswer(0, "Login is too short");
		} else if(mb_strlen($login) > 128) {
			serverSendAnswer(0, "Login is too long");
		} else if(!preg_match("/^[A-Za-z0-9]{4,128}+$/", $login)) {
			serverSendAnswer(0, "Login should consist only of Latin letters or numbers");
		}
	}
	return true;
}

function validatePassword($password, $is_required = true) {
	if(empty($password) && $is_required) {
		serverSendAnswer(0, "Enter password");
	} else if(!empty($password)) {
		if(mb_strlen($password) < 4) {
			serverSendAnswer(0, "Password is too short");
		} else if(mb_strlen($password) > 256) {
			serverSendAnswer(0, "Password is too long");
		} else if(!preg_match("/^[A-Za-z0-9]{4,256}+$/", $password)) {
			serverSendAnswer(0, "Password should consist only of Latin letters or numbers");
		}
	}
	return true;
}

function validateEmail($email, $is_required = true) {
	if(empty($email) && $is_required) {
		serverSendAnswer(0, "Enter email");
	} else if(!empty($email)) {
		if(mb_strlen($email) < 6) {
			serverSendAnswer(0, "Email is too short");
		} else if(mb_strlen($email) > 128) {
			serverSendAnswer(0, "Email is too long");
		} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			serverSendAnswer(0, "Incorrect email format");
		}
	}
	return true;
}

function validateUrl($url, $is_required = true) {
	if(empty($url) && $is_required) {
		serverSendAnswer(0, "Enter url");
	} else if(!empty($url)) {
		if(mb_strlen($url) < 2) {
			serverSendAnswer(0, "Url is too short");
		} else if(mb_strlen($url) > 128) {
			serverSendAnswer(0, "Url is too long");
		} else if(!preg_match("/^[A-Za-z0-9-_]{2,128}+$/", $url)) {
			serverSendAnswer(0, "Url should consist only of Latin letters, numbers, bottom dash or hyphen");
		}
	}
	return true;
}

function validateTitle($title, $is_required = true) {
	if(empty($title) && $is_required) {
		serverSendAnswer(0, "Enter title");
	} else if(!empty($title)) {
			if(mb_strlen($title) < 2) {
			serverSendAnswer(0, "Title is too short");
		} else if(mb_strlen($title) > 512) {
			serverSendAnswer(0, "Title is too long");
		}
	}
	return true;
}

function validateContactName($name, $is_required = true) {
	if(empty($name) && $is_required) {
		serverSendAnswer(0, "Enter name");
	} else if(!empty($name)) {
			if(mb_strlen($name) < 2) {
			serverSendAnswer(0, "Name is too short");
		} else if(mb_strlen($name) > 32) {
			serverSendAnswer(0, "Name is too long");
		}
	}
	return true;
}

function validateContactMessage($message, $is_required = true) {
	if(empty($message) && $is_required) {
		serverSendAnswer(0, "Enter message");
	} else if(!empty($message)) {
			if(mb_strlen($message) < 2) {
			serverSendAnswer(0, "Message is too short");
		} else if(mb_strlen($message) > 512) {
			serverSendAnswer(0, "Message is too long");
		}
	}
	return true;
}