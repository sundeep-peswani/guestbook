<?php

require_once('lib/limonade.php');

function configure() {
	require_once('conf.php');
	
  $localhost = preg_match('/localhost/', $_SERVER['HTTP_HOST']);
  $env = $localhost ? ENV_DEVELOPMENT : ENV_PRODUCTION;
  option('env', $env);

	# set-up db
	try {
		$dsn = sprintf('mysql:host=%s;port=%u;dbname=%s', $host, $port, $dbname);
		$db = new PDO($dsn, $username, $password);
	} catch (PDOException $e) {
		halt('Connection failed: '.$e);
	}
	option('db', $db);
	
	# load settings
	$sql = 'SELECT `key`, `value` FROM `settings`';
	$result = array();

	$stmt = $db->prepare($sql);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	foreach($result as $setting) {
		option('setting_'.strtolower($setting->key), $setting->value);
	}
	
	# set timezone
	date_default_timezone_set(option('setting_timezone'));
	
	# set debug
	option('debug', intval(option('setting_debug')));
	if (option('debug')) {
		option('env', ENV_DEVELOPMENT);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		option('db', $db);
	}
}

function before() {
	if (preg_match('/admin/', request_uri())) {
		layout('layouts/admin.html.php');
	} else {
		layout('layouts/guestbook.html.php');
	}
}

dispatch('/admin', 'admin_index');
	function admin_index() {
		if (!is_admin()) {
			redirect_to('/admin/login');
		}
		
		$entries = guestbook_load(null, -1, 0);
		set('entries', $entries);
		return html('admin/index.html.php');
	}
dispatch('/admin/:id/approve', 'admin_approve');
	function admin_approve() {
		if (!is_admin()) {
			redirect_to('/admin/login');
		}

		return json(array(
			'success' => guestbook_approve(intval(params('id')))
		));
	}
dispatch('/admin/:id/disapprove', 'admin_disapprove');
	function admin_disapprove() {
		if (!is_admin()) {
			redirect_to('/admin/login');
		}

		return json(array(
			'success' => guestbook_disapprove(intval(params('id')))
		));
	}
dispatch('/admin/:id/delete', 'admin_delete');
	function admin_delete() {
		if (!is_admin()) {
			redirect_to('/admin/login');
		}

		return json(array(
			'success' => guestbook_delete(intval(params('id')))
		));
	}
dispatch('/admin/login', 'admin_login_page');
	function admin_login_page() {
		return html('admin/login.html.php');
	}
dispatch_post('/admin/login', 'admin_login');
	function admin_login() {
		if (admin_authenciate($_POST['user'])) {
			redirect_to('/admin');
		}
		
		flash('error', 'Invalid login credentials. Please try again.');
		set('user', $_POST['user']);
		return html('admin/login.html.php');
	}

dispatch('/new', 'guestbook_new');
	function guestbook_new() {
		return html('guestbook/new.html.php');
	}
dispatch_post('/new', 'guestbook_new_entry');
	function guestbook_new_entry() {
		if (guestbook_create($_POST['entry'])) {
			return html('guestbook/thanks.html.php');
		}
		
		flash('error', 'Could not save entry');
		set('entry', $_POST['entry']);
		return html('guestbook/new.html.php');
}
	
dispatch(array('/', '/:page'), 'guestbook_index');
	function guestbook_index() {
		$page = 1;
		if (params('page')) {
			$page = params('page');
		}
		$length = option('setting_page_length');
		$offset = ($page - 1) * $length;
		$entries = guestbook_load_approved($length, $offset);
		
		if (empty($entries)) {
			if (request_uri() == '/') {
				return html('guestbook/empty.html.php');
			}
			redirect_to('/');
		}
		set('entries', $entries);
		return html('guestbook/index.html.php');
	}

run();