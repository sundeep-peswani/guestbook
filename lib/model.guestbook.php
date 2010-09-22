<?php

function debug($stmt) {
	$debug = option('debug');
	if (!$debug) return;
	
	print '<pre>';
	$stmt->debugDumpParams();
	print $stmt->errorCode();
	print "\n";
	print_r($stmt->errorInfo());
	print '</pre>';
}

function guestbook_load($approved = null, $limit = 10, $offset = 0) {
	$db = option('db');
	if ($approved === null) {
		$sql = 'SELECT *, UNIX_TIMESTAMP(`ctime`) AS cunixtime FROM posts ORDER BY ctime DESC';
	} else {
		$sql = 'SELECT *, UNIX_TIMESTAMP(`ctime`) AS cunixtime FROM posts WHERE approved = :approved ORDER BY ctime DESC';
	}
	if ($limit > 0 ) {
	 $sql .= ' LIMIT :limit OFFSET :offset';
	}

	$stmt = $db->prepare($sql);
	if ($approved !== null) {
		$stmt->bindValue(':approved', $approved, PDO::PARAM_INT);
	}
	if ($limit > 0) {
		$stmt->bindParam(':limit', intval($limit), PDO::PARAM_INT);
		$stmt->bindParam(':offset', intval($offset), PDO::PARAM_INT);
	}
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

function guestbook_count_approved() {
	$db = option('db');
	
	$sql = 'SELECT COUNT(*) FROM posts WHERE approved > :approved';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':approved', 0, PDO::PARAM_INT);
	
	if ($stmt->execute()) {
		return array_pop($stmt->fetch(PDO::FETCH_NUM));
	}
	
	debug($stmt);
	return false;	
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
	
	if ($stmt->execute() && $stmt->rowCount() > 0) return true;
	
	debug($stmt);
	return false;
}

function guestbook_set_approval($id = 0, $approval = 1) {
	$db = option('db');
	$sql = 'UPDATE posts SET approved = :approval WHERE `id` = :id';
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':id', intval($id), PDO::PARAM_INT);
	$stmt->bindParam(':approval', intval($approval), PDO::PARAM_INT);
	if ($stmt->execute()) {
		return true;
	}

	debug($stmt);
	return false;		
}

function guestbook_approve($id = 0) {
	return guestbook_set_approval($id, 1);
}

function guestbook_disapprove($id = 0) {
	return guestbook_set_approval($id, -1);
}

function guestbook_delete($id = 0) {
	$db = option('db');
	$sql = 'DELETE FROM posts WHERE id = :id';
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':id', intval($id), PDO::PARAM_INT);
	if ($stmt->execute()) {
		return true;
	}

	debug($stmt);
	return false;		
}