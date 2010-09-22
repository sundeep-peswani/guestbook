<?php

function debug($stmt) {
	$env = option('env');
	if ($env !== ENV_DEVELOPMENT) return;
	
	echo '<pre>';
	$stmt->debugDumpParams();
	echo '</pre>';
}

function guestbook_load($approved = null, $limit = 10, $offset = 0) {
	$db = option('db');
	if ($approved === null) {
		$sql = 'SELECT *, UNIX_TIMESTAMP(`ctime`) AS cunixtime FROM posts ORDER BY ctime DESC LIMIT :limit OFFSET :offset';
	} else {
		$sql = 'SELECT *, UNIX_TIMESTAMP(`ctime`) AS cunixtime FROM posts WHERE approved = :approved ORDER BY ctime DESC LIMIT :limit OFFSET :offset';
	}
	$result = array();

	$stmt = $db->prepare($sql);
	if ($approved !== null) {
		$stmt->bindValue(':approved', $approved, PDO::PARAM_INT);
	}
	$stmt->bindParam(':limit', intval($limit), PDO::PARAM_INT);
	$stmt->bindParam(':offset', intval($offset), PDO::PARAM_INT);
	if ($stmt->execute()) {
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	debug($stmt);
	return false;	
}

function guestbook_load_approved($limit = 10, $offset = 0) {
	return guestbook_load(1, $limit, $offset);
}

function guestbook_load_unapproved($limit = 10, $offset = 0) {
	return guestbook_load(0, $limit, $offset);
}

function guestbook_load_disapproved($limit = 10, $offset = 0) {
	return guestbook_load(-1, $limit, $offset);
}

function guestbook_create($entry) {
	$db = option('db');
	
	$author = strip_tags($entry['author']);
	$message = strip_tags($entry['message']);
	
	$sql = 'INSERT INTO posts (`author`, `message`, `approved`) VALUES (:author, :message, :approved)';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':author', $author, PDO::PARAM_STR);
	$stmt->bindValue(':message', $message, PDO::PARAM_STR);
	$stmt->bindValue(':approved', 0, PDO::PARAM_INT);
	
	if ($stmt->execute()) return true;
	
	debug($stmt);
	return false;
}

function guestbook_set_approval($approval = 1) {

}

function guestbook_approve() {

}

function guestbook_disapprove() {

}