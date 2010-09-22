<?php

function is_admin() {
	return isset($_SESSION['is_admin']);
}

function admin_authenciate($user) {
	$db = option('db');
	
	$username = h($user['username']);
	$password = md5(h($user['password']));
	
	$sql = 'SELECT `id` FROM admins WHERE `username` = :username AND `password` = :password';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':username', $username, PDO::PARAM_STR);
	$stmt->bindValue(':password', $password, PDO::PARAM_STR);
	if ($stmt->execute() && $stmt->rowCount() > 0) {
		$_SESSION['is_admin'] = array_pop($stmt->fetch(PDO::FETCH_NUM));
		return true;
	}
	
	return false;
}